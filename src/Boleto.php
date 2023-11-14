<?php

namespace TrabajoSube;

use TrabajoSube\Tiempo;

class Boleto {
    public $monto;
    public $fecha;
    public $tipoTarjeta;
    public $lineaColectivo;
    public $totalAbonado;
    public $saldo;
    public $tarjetaID;
    public $saldoNegativoCancelado;

    public function __construct($monto, $tipoTarjeta, $lineaColectivo, $totalAbonado, $saldo, $tarjetaID, $saldoNegativoCancelado) {
        $this->monto = $monto;
        $this->fecha = date('l jS \of F Y h:i:s A', time()-10800);
        $this->tipoTarjeta = $tipoTarjeta;
        $this->lineaColectivo = $lineaColectivo;
        $this->totalAbonado = $totalAbonado;
        $this->saldo = $saldo;
        $this->tarjetaID = $tarjetaID;
        $this->saldoNegativoCancelado = $saldoNegativoCancelado;
    }
}