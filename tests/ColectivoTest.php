<?php 

namespace TrabajoSube;

use PHPUnit\Framework\TestCase;
use TrabajoSube\colectivo;
use TrabajoSube\ColectivoInterUrbano;
use TrabajoSube\tarjeta;
use TrabajoSube\TarjetaMBE;
use TrabajoSube\TarjetaJubilados;
use TrabajoSube\Boleto;
use TrabajoSube\Tiempo;

class ColectivoTest extends TestCase {

    public function testPagarCon() {

        // En esta función se va a testear el funcionamiento de pagarCon.

        $colectivo = new colectivo(132);
        $tarjeta = new tarjeta;

        $tarjeta->cargarSaldo(200);
        $tarjeta->acreditarSaldo();

        $colectivo->pagarCon($tarjeta, 0);
        $this->assertEquals($tarjeta->saldo, 15);
        $colectivo->pagarCon($tarjeta, 0);
        $this->assertEquals($tarjeta->saldo, -170);
        $tarjeta->cargarSaldo(500);
        $this->assertEquals($tarjeta->saldo, -170);
        $colectivo->pagarCon($tarjeta, 0);
        $this->assertEquals($tarjeta->saldo, 145);

        $colectivo->pagarCon($tarjeta, 0);
        $colectivo->pagarCon($tarjeta, 0);
        $colectivo->pagarCon($tarjeta, 0);
        $this->assertFalse($colectivo->pagarCon($tarjeta, 0));
    }

    public function testColectivoInterUrbano() {

        $colectivo = new ColectivoInterUrbano(132);
        $tarjeta = new tarjeta;

        $tarjeta->cargarSaldo(300);
        $tarjeta->acreditarSaldo();

        $colectivo->pagarCon($tarjeta, 0);
        $this->assertEquals($tarjeta->saldo, 0);
    }

    public function testCheckTarjeta() {

        /* En esta función se va a testear el funcionamiento de checkTarjeta, además de comprobar
        las diferencias entre las distintas franquicias. */

        $colectivo = new colectivo(132);
        $tarjeta = new tarjeta;
        $tarjetaJubilados = new TarjetaJubilados;
        $tarjetaMBE = new TarjetaMBE;

        $tarjeta->cargarSaldo(4000);
        $tarjeta->acreditarSaldo();
        $tarjetaJubilados->cargarSaldo(4000);
        $tarjetaJubilados->acreditarSaldo();
        $tarjetaMBE->cargarSaldo(4000);
        $tarjetaMBE->acreditarSaldo();

        // Tarjeta sin franquicia:
        $colectivo->pagarCon($tarjeta, 43200);
        $this->assertEquals($tarjeta->saldo, 3815);
        $this->assertEquals($colectivo->checktarjeta($tarjeta, 43200),1);

        // Tarjeta con franquicia parcial:
        $this->assertEquals($colectivo->checktarjeta($tarjetaMBE, 43200),0.5);
        $colectivo->pagarCon($tarjetaMBE, 43200);
        $this->assertEquals($tarjetaMBE->saldo, 3907.5);
        $this->assertEquals($colectivo->checktarjeta($tarjetaMBE, 43201),1);
        $colectivo->pagarCon($tarjetaMBE, 43201);
        $this->assertEquals($tarjetaMBE->saldo, 3722.5);
        $this->assertEquals($colectivo->checktarjeta($tarjetaMBE, 43600),0.5);
        $colectivo->pagarCon($tarjetaMBE, 43600);
        $this->assertEquals($tarjetaMBE->saldo, 3630);

        // Tarjeta con franquicia completa: 
        $this->assertEquals($colectivo->checktarjeta($tarjetaJubilados, 0),1);
        $colectivo->pagarCon($tarjetaJubilados,0);
        $this->assertEquals($tarjetaJubilados->saldo, 3815);
        $this->assertEquals($colectivo->checktarjeta($tarjetaJubilados, 43200),0);
        $colectivo->pagarCon($tarjetaJubilados,43200);
        $this->assertEquals($tarjetaJubilados->saldo, 3815);
        $this->assertEquals($colectivo->checktarjeta($tarjetaJubilados, 43200),1);
        $colectivo->pagarCon($tarjetaJubilados,43200);
        $this->assertEquals($tarjetaJubilados->saldo, 3630);
    }

    public function testTransbordos() {

        $colectivo1 = new colectivo(131);
        $colectivo2 = new colectivo(132);
        $tarjeta = new tarjeta;

        $tarjeta->cargarSaldo(4000);
        $tarjeta->acreditarSaldo();

        // Transbordar correctamente.
        $colectivo1->pagarCon($tarjeta, 43200);
        $this->assertEquals($tarjeta->saldo, 3815);
        $this->assertEquals($colectivo2->checktarjeta($tarjeta, 43200),0);
        $colectivo2->pagarCon($tarjeta, 43200);
        $this->assertEquals($tarjeta->saldo, 3815);

        // Intentar transbordar cuando ya pasó una hora desde el ultimo viaje en colectivo.
        $this->assertEquals($colectivo1->checktarjeta($tarjeta, 46800),1);
        $colectivo1->pagarCon($tarjeta, 46800);
        $this->assertEquals($tarjeta->saldo, 3630);

        // Intentar transbordar entre colectivos de una misma linea.
        $this->assertEquals($colectivo1->checktarjeta($tarjeta, 46800),1);
        $colectivo1->pagarCon($tarjeta, 46800);
        $this->assertEquals($tarjeta->saldo, 3445);

        //Intentar transbordar fuera de horario.
        $colectivo1->pagarCon($tarjeta, 86400);
        $this->assertEquals($tarjeta->saldo, 3260);
        $this->assertEquals($colectivo2->checktarjeta($tarjeta, 86400),1);
        $colectivo2->pagarCon($tarjeta, 86400);
        $this->assertEquals($tarjeta->saldo, 3075);
    }

    public function test5Min() {

        /* En esta función se va a testear el funcionamiento de check5Min. */

        $colectivo = new colectivo(132);
        $tarjeta = new TarjetaJubilados;

        $tarjeta->cargarSaldo(4000);
        $tarjeta->acreditarSaldo();

        $colectivo->pagarcon($tarjeta, 0);
        $this->assertFalse($colectivo->check5Min($tarjeta,299));
        $this->assertFalse($colectivo->check5Min($tarjeta,300));
    }


    public function testHorario() {

        /* En esta función se va a testear el funcionamiento de checkHorarios. */

        $colectivo = new colectivo(132);

        $this->assertFalse($colectivo->checkHorarios(0));
        $this->assertTrue($colectivo->checkHorarios(43200));
    }

    public function testViajesMes() {

        /* En esta función se va a testear el funcionamiento de checkViajesMes y que la variable de ViajesMes 
        se mantenga actualizada al pagar un boleto. */

        $colectivo = new colectivo(132);
        $tarjeta = new tarjeta();
        $descuento;

        $tarjeta->cargarSaldo(4000);
        $tarjeta->acreditarSaldo();

        $colectivo->pagarCon($tarjeta, 0); // Mes 0
        $this->assertEquals($tarjeta->viajesMes, 1);
        $this->assertEquals($colectivo->checkViajesMes($tarjeta, 0), 1);
        $tarjeta->viajesMes = 40;
        $this->assertEquals($colectivo->checkViajesMes($tarjeta, 0), 0.8);
        $tarjeta->viajesMes = 80;
        $this->assertEquals($colectivo->checkViajesMes($tarjeta, 0), 0.75);
        $colectivo->pagarCon($tarjeta, 2678400); // Mes 1
        $this->assertEquals($tarjeta->viajesMes, 1);
        $this->assertEquals($colectivo->checkViajesMes($tarjeta, 2678400), 1);
    }

    public function testViajesHoy() {

        /* En esta función se va a testear el funcionamiento de checkViajesHoy y que la variable de ViajesHoy 
        se mantenga actualizada al pagar un boleto. */

        $colectivo = new colectivo(132);
        $tarjeta = new TarjetaJubilados;

        $tarjeta->cargarSaldo(4000);
        $tarjeta->acreditarSaldo();

        $colectivo->pagarCon($tarjeta, 43200); //Día 0
        $this->assertTrue($colectivo->checkViajesHoy($tarjeta, 43200));
        $colectivo->pagarCon($tarjeta, 43200);
        $this->assertFalse($colectivo->checkViajesHoy($tarjeta, 43200));
        $colectivo->pagarCon($tarjeta, 43200);
        $this->assertFalse($colectivo->checkViajesHoy($tarjeta, 43200));
        $colectivo->pagarCon($tarjeta, 86400); //Día 1
        $this->assertEquals($tarjeta->viajesHoy, 1);
        $this->assertTrue($colectivo->checkViajesHoy($tarjeta, 86400));
    }
}