<?php

namespace TrabajoSube;

class Boleto {
    public $monto;
    public $fecha;
    public $tipoTarjeta;
    public $lineaColectivo;
    public $totalAbonado;
    public $saldo;
    public $tarjetaID;
    public $saldoNegativoCancelado;

    public function __construct($monto, $tiempo, $tipoTarjeta, $lineaColectivo, $totalAbonado, $saldo, $tarjetaID, $saldoNegativoCancelado) {
        $this->monto = $monto;
        $this->fecha = date("d/m/Y H:i a", $tiempo);
        $this->tipoTarjeta = $tipoTarjeta;
        $this->lineaColectivo = $lineaColectivo;
        $this->totalAbonado = $totalAbonado;
        $this->saldo = $saldo;
        $this->tarjetaID = $tarjetaID;
        $this->saldoNegativoCancelado = $saldoNegativoCancelado;
    }
}