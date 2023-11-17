<?php 

namespace TrabajoSube;

use PHPUnit\Framework\TestCase;
use TrabajoSube\colectivo;
use TrabajoSube\Tarjeta;
use TrabajoSube\Boleto;

class TarjetaTest extends TestCase {

    public function testCargar_Acreditar() {

        $tarjeta = new Tarjeta();

        $this->assertFalse($tarjeta->cargarSaldo(123));

        $tarjeta->cargarSaldo(4000);
        $this->assertEquals($tarjeta->saldoSinAcreditar, 4000);
        $tarjeta->acreditarSaldo();
        $this->assertEquals($tarjeta->saldo, 4000);
        $this->assertEquals($tarjeta->saldoSinAcreditar, 0);
        $tarjeta->cargarSaldo(4000);
        $tarjeta->acreditarSaldo();
        $this->assertEquals($tarjeta->saldo, 6600);
        $this->assertEquals($tarjeta->saldoSinAcreditar, 1400);
    }

}