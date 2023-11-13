<?php

namespace TrabajoSube;

use TrabajoSube\Tiempo;

class Colectivo {
    public $tarifaBasica = 120;
    public $saldoNegativo = -211.84;
    public $lineaColectivo;

    public function __construct($lineaColectivo) {
        $this->lineaColectivo = $lineaColectivo;
    }

    public function pagarCon(Tarjeta $tarjeta) {
        $multiplicadorPrecio = $tarjeta->multiplicadorPrecio();
        $saldoPrevio = $tarjeta->getSaldo;
        $totalAbonado = $tarjeta->acreditarsaldo();
        $nuevoSaldo = $tarjeta->getSaldo;

        if(time() - $tarjeta->getUltimoViaje >= 300 && $multiplicadorPrecio == 0.5){
            if($saldoPrevio < 0 && $nuevoSaldo >= 0) {
                $saldoNegativoCancelado = true;
            }
            else {
                $saldoNegativoCancelado = false;
            }
            if ($tarjeta->getSaldo() >= ($this->saldoNegativo + $this->tarifaBasica * $multiplicadorPrecio)) {
                $tarjeta->descontarSaldo($this->tarifaBasica * $multiplicadorPrecio);
                $tarjeta->setUltimoViaje();
                $tarjeta->sumarViaje();
                return new Boleto($this->tarifaBasica, time(), $tarjeta->getTipotarjeta, $this->lineaColectivo, $totalAbonado, $tarjeta->getSaldo, $tarjeta->getId, $saldoNegativoCancelado);
            } else {
                return false;
            }
        }
        if ($multiplicadorPrecio != 0.5){
            if($saldoPrevio < 0 && $nuevoSaldo >= 0) {
                $saldoNegativoCancelado = true;
            }
            else {
                $saldoNegativoCancelado = false;
            }
            if ($tarjeta->getSaldo() >= ($this->saldoNegativo + $this->tarifaBasica * $multiplicadorPrecio)) {
                $tarjeta->descontarSaldo($this->tarifaBasica * $multiplicadorPrecio);
                $tarjeta->setUltimoViaje();
                $tarjeta->sumarViaje();
                return new Boleto($this->tarifaBasica, time(), $tarjeta->getTipotarjeta, $this->lineaColectivo, $totalAbonado, $tarjeta->getSaldo, $tarjeta->getId, $saldoNegativoCancelado);
            } else {
                return false;
            }
        }
    }
}


class ColectivoInterurbano extends Colectivo
{
    // Tarifa para líneas interurbanas
    private $tarifaInterurbana = 184;

    public function __construct($lineaColectivo)
    {
        parent::__construct($lineaColectivo);
        $this->tarifaBasica = 184;
    }
}
