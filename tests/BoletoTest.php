<?php 

namespace TrabajoSube;

use PHPUnit\Framework\TestCase;

class BoletoTest extends TestCase {

    public function testBoletoComun() {

        $colectivo = new colectivo(132);
        $tarjeta = new tarjeta;

        $tarjeta->cargarSaldo(500);
        $tarjeta->acreditarSaldo();

        $boleto = $colectivo->pagarCon($tarjeta, 1700226660);
        $this->assertEquals($boleto->monto, 185);
        $this->assertEquals($boleto->fecha, date("d/m/Y H:i a", 1700226660));
        $this->assertEquals($boleto->tipoTarjeta, "Común");
        $this->assertEquals($boleto->lineaColectivo, 132);
        $this->assertEquals($boleto->saldo, 315);
        $this->assertEquals($boleto->saldoNegativoCancelado, false);
    }

    public function testBoletoMBE() {

        $colectivo = new colectivo(204);
        $tarjeta = new tarjetaMBE;

        $tarjeta->cargarSaldo(1000);
        $tarjeta->acreditarSaldo();

        $boleto = $colectivo->pagarCon($tarjeta, 53247600);
        $this->assertEquals($boleto->monto, 92.5);
        $this->assertEquals($boleto->fecha, date("d/m/Y H:i a", 53247600));
        $this->assertEquals($boleto->tipoTarjeta, "Medio Boleto Estudiantil");
        $this->assertEquals($boleto->lineaColectivo, 204);
        $this->assertEquals($boleto->saldo, 907.5);
        $this->assertEquals($boleto->saldoNegativoCancelado, false);
    }

    public function testBoletoMBU() {

        $colectivo = new colectivo("K");
        $tarjeta = new tarjetaMBU;

        $tarjeta->cargarSaldo(800);
        $tarjeta->acreditarSaldo();

        $boleto = $colectivo->pagarCon($tarjeta, 106495200);
        $this->assertEquals($boleto->monto, 92.5);
        $this->assertEquals($boleto->fecha, date("d/m/Y H:i a", 106495200));
        $this->assertEquals($boleto->tipoTarjeta, "Medio Boleto Universitario");
        $this->assertEquals($boleto->lineaColectivo, "K");
        $this->assertEquals($boleto->saldo, 707.5);
        $this->assertEquals($boleto->saldoNegativoCancelado, false);
    }

    public function testBoletoBEG() {

        $colectivo = new colectivo(110);
        $tarjeta = new tarjetaBEG;

        $tarjeta->cargarSaldo(150);
        $tarjeta->acreditarSaldo();

        $boleto = $colectivo->pagarCon($tarjeta, 1656320321);
        $this->assertEquals($boleto->monto, 0);
        $this->assertEquals($boleto->fecha, date("d/m/Y H:i a", 1656320321));
        $this->assertEquals($boleto->tipoTarjeta, "Boleto Educativo Gratuito");
        $this->assertEquals($boleto->lineaColectivo, 110);
        $this->assertEquals($boleto->saldo, 150);
        $this->assertEquals($boleto->saldoNegativoCancelado, false);
    }

    public function testBoletoJubilados() {

        $colectivo = new colectivo(143);
        $tarjeta = new tarjetaJubilados;

        $tarjeta->cargarSaldo(3000);
        $tarjeta->acreditarSaldo();

        $boleto = $colectivo->pagarCon($tarjeta, 2535600);
        $this->assertEquals($boleto->monto, 0);
        $this->assertEquals($boleto->fecha, date("d/m/Y H:i a", 2535600));
        $this->assertEquals($boleto->tipoTarjeta, "Tarjeta Jubilados");
        $this->assertEquals($boleto->lineaColectivo, 143);
        $this->assertEquals($boleto->saldo, 3000);
        $this->assertEquals($boleto->saldoNegativoCancelado, false);
    }

    public function testSaldoNegativoCancelado() {

        $colectivo = new colectivo(139);
        $tarjeta = new tarjeta;

        $tarjeta->cargarSaldo(150);
        $tarjeta->acreditarSaldo();

        $colectivo->pagarCon($tarjeta, 1600226660);
        $colectivo->pagarCon($tarjeta, 1600226660);
        $tarjeta->cargarSaldo(1000);
        $boleto = $colectivo->pagarCon($tarjeta, 1600226660);
        $this->assertEquals($boleto->monto, 185);
        $this->assertEquals($boleto->fecha, date("d/m/Y H:i a", 1600226660));
        $this->assertEquals($boleto->tipoTarjeta, "Común");
        $this->assertEquals($boleto->lineaColectivo, 139);
        $this->assertEquals($boleto->saldo, 595);
        $this->assertEquals($boleto->saldoNegativoCancelado, true);
    }
}