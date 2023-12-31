<?php

namespace TrabajoSube;

use TrabajoSube\Tiempo;

class Colectivo {

    public $tarifa = 185;
    public $saldoMinimo = -326.6;
    public $lineaColectivo;

    public function __construct($lineaColectivo) {
        $this->lineaColectivo = $lineaColectivo;
    }

    public function pagarCon($tarjeta, $tiempo) {

        //$saldoNegativoCancelado;
        $multiplicadorPrecio = $this->checkTarjeta($tarjeta, $tiempo) * $this->checkViajesMes($tarjeta, $tiempo);
        $monto = $this->tarifa * $multiplicadorPrecio;
        $saldoPrevio = $tarjeta->saldo;
        $totalAbonado = $tarjeta->acreditarsaldo();
        
        if($this->saldoMinimo <= $tarjeta->saldo - $monto) {

            $tarjeta->saldo -= $monto;
            $tarjeta->viajesHoy += 1;
            $tarjeta->viajesMes += 1; 
            $tarjeta->ultimoViaje = $tiempo;
            $tarjeta->ultimaLinea = $this->lineaColectivo;

            if ($saldoPrevio < 0 && $tarjeta->saldo > 0) {
                $saldoNegativoCancelado = true;
            }
            else {
                $saldoNegativoCancelado = false;
            }

            return new boleto($monto, $tiempo, $tarjeta->tipoTarjeta, $this->lineaColectivo, $totalAbonado, $tarjeta->saldo, $tarjeta->id, $saldoNegativoCancelado);
        }
        else {
            return false;
        }
    }

    public function checkTarjeta($tarjeta, $tiempo) {

        $descuentoFranquicia = 1;
        $viajeDiarioDisponible = $this->checkViajesHoy($tarjeta, $tiempo);
        $transbordoDisponible = $this->checkTransbordos($tarjeta, $tiempo);

        if($transbordoDisponible) {
            $descuentoFranquicia = 0;
        }
        elseif($this->checkHorarios($tiempo)) {

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

    public function checkTransbordos ($tarjeta, $tiempo) {

        //$horariosTransbordo;
        //$checkHora;

        $diaSemana = date('N', $tiempo);
        $hora = date('H', $tiempo);
        if($diaSemana >= 1 && $diaSemana <= 6 && $hora >= 7 && $hora <= 22) {
            $horariosTransbordo = true;
        }
        else {
            return false;
        }

        if(($tiempo - $tarjeta->ultimoViaje) < 3600) {
            $checkHora = true;
        }
        else {
            return false;
        }

        if ($horariosTransbordo && $checkHora && $tarjeta->ultimaLinea != $this->lineaColectivo && $tarjeta->ultimaLinea != null) {
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

    function usoFrecuente($viajesEnElMes) {
        if ($viajesEnElMes >= 30 && $viajesEnElMes < 80) {
            return 0.8; // Descuento del 20%
        } elseif ($viajesEnElMes >= 80) {
            return 0.75; // Descuento del 25%
        } else {
            return 1; // Sin descuento
        }
    }
}