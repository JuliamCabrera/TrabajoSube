<?php

namespace TrabajoSube;

use TrabajoSube\Tiempo;

class Colectivo {

    public $tarifa = 185;
    public $saldoNegativo = -326.6;
    public $lineaColectivo;

    public function __construct($lineaColectivo) {
        $this->lineaColectivo = $lineaColectivo;
    }

    public function pagarCon($tarjeta, $tiempo) {

        $saldoNegativoCancelado;
        $multiplicadorPrecio = $this->checkTarjeta($tarjeta, $tiempo) * $this->checkViajesMes($tarjeta, $tiempo);
        $monto = $this->tarifa * $multiplicadorPrecio;
        $saldoPrevio = $tarjeta->saldo;
        $totalAbonado = $tarjeta->acreditarsaldo();
        
        if($tarjeta->saldo >= $monto) {
            $tarjeta->saldo -= $monto;
            $tarjeta->viajesHoy += 1;
            $tarjeta->viajesMes += 1;
        }

        if ($saldoPrevio < 0 && $tarjeta->saldo > 0) {
            $saldoNegativoCancelado = true;
        }
        else {
            $saldoNegativoCancelado = false;
        }

        return new boleto($monto, $tiempo, $tarjeta->tipoTarjeta, $this->lineaColectivo, $totalAbonado, $tarjeta->saldo, $tarjeta->id, $saldoNegativoCancelado);
    }

    public function checkTarjeta($tarjeta, $tiempo) {

        $descuentoFranquicia = 1;
        $viajeDiarioDisponible = $this->checkViajesHoy($tarjeta, $tiempo);

        if($this->checkHorarios($tiempo)) {

            if($tarjeta->tipoFranquicia == "Franquicia Parcial" && $this->check5Min($tarjeta, $tiempo)) {
                $descuentoFranquicia = 0.5;
            }
            if($tarjeta->tipoFranquicia == "Franquicia Completa" && $viajeDiarioDisponible) {
                $descuentoFranquicia = 0;
            }
        }
        else {
            $descuentoFranquicia = 1;
        }
        return $descuentoFranquicia;
    }

    public function checkHorarios ($tiempo) {
        $diaSemana = date('N', $tiempo);
        $hora = date('H', $tiempo);
        if($diaSemana >= 1 && $diaSemana <= 5 && $hora >= 6 && $hora <= 22) {
            return true;
        }
        else {
            return false;
        }
    }

    public function checkViajesHoy ($tarjeta, $tiempo) {
        $ultimoDia = date("j m Y", $tarjeta->ultimoViaje);
        $diaActual = date("j m Y", $tiempo);
        if ($ultimoDia != $diaActual) {
            $tarjeta->viajesHoy = 0;
            return true;
        }
        else if ($tarjeta->viajesHoy < 2) {
            return true;
        }
        else {
            return false;
        }
    }

    public function checkViajesMes ($tarjeta, $tiempo){

        $descuentoUsoFrecuente =  0;

        $ultimoMes = date("m Y", $tarjeta->ultimoViaje);
        $mesActual = date("m Y", $tiempo);

        if ($ultimoMes != $mesActual) {
            $tarjeta->viajesMes = 0;
            return 1;
        }

        if($tarjeta->viajesMes < 30) {
            $descuentoUsoFrecuente = 1;
        }

        if($tarjeta->viajesMes >= 30 && $tarjeta->viajesMes < 80) {
            $descuentoUsoFrecuente = 0.8;
        }

        if($tarjeta->viajesMes >= 80) {
            $descuentoUsoFrecuente = 0.75;
        }

        return $descuentoUsoFrecuente;
    }

    public function check5Min($tarjeta, $tiempo) {
        if(($tiempo - $tarjeta->ultimoViaje) > 300) {
            return true;
        }
        else {
            return false;
        }
    }
}