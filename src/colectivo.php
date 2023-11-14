<?php

namespace TrabajoSube;

use TrabajoSube\Tiempo;

class Colectivo {

    public $tarifa = 120;
    public $saldoNegativo = -211.84;
    public $lineaColectivo;

    public function __construct($lineaColectivo) {
        $this->lineaColectivo = $lineaColectivo;
    }

    public function pagarCon(Tarjeta $tarjeta) {

        $multiplicadorPrecio = $this->checkTarjeta($tarjeta, $tiempo);
        $saldoPrevio = $tarjeta->saldo;
        $totalAbonado = $tarjeta->acreditarsaldo();
        $nuevoSaldo = $tarjeta->saldo;
    }

    public checkTarjeta($tarjeta, $tiempo) {

        $descuentoFranquicia;

        if($tarjeta->tipoTarjeta == "Sin Franquicia") {
            $descuentoFranquicia = 1;
        }
        if($tarjeta->tipoTarjeta == "Franquicia Parcial") {
            $descuentoFranquicia = 0.5;
        }
        if($tarjeta->tipoTarjeta == "Franquicia Completa") {
            $descuentoFranquicia = 0;
        }
        return $descuentoFranquicia;
    }

    public checkViajesHoy ($tarjeta, $tiempo) {
        $ultimoDia = date("j m Y", $tarjeta->ultimoViaje);
        $diaActual = date("j m Y", $tiempo);
        if ($ultimoDia != $diaActual) {
            $tarjeta->viajesHoy = 0;
        }
    }

    public checkViajesMes ($tarjeta, $tiempo){

        $descuentoUsoFrecuente;

        $ultimoMes = date("m Y", $tarjeta->ultimoViaje);
        $mesActual = date("m Y", $tiempo);
        if ($ultimoMes != $mesActual) {
            $tarjeta->viajesMes = 0;
        }

        if($tarjeta->viajesMes < 29) {
            $descuentoUsoFrecuente = 1;
        }
        elseif ($tarjeta->viajesMes < 80) {
            $descuentoUsoFrecuente = 0.8;
        }
        else {
            $descuentoUsoFrecuente = 0.75;
        }
        return $descuentoUsoFrecuente
    }
}

class ColectivoInterurbano extends Colectivo
{
    public $tarifa = 184;
}