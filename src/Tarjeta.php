<?php

namespace TrabajoSube;

class Tarjeta {
    public $id;
    public $saldo;
    public $saldoSinAcreditar;
    public $ultimoViaje;
    public $viajesHoy;
    public $viajesRealizados = 1;
    protected $tipoTarjeta = "Sin Franquicia";
    public $limiteSaldo = 6600;
    public $cargasAceptadas = [150, 200, 250, 300, 350, 400, 450, 500, 600, 700, 800, 900, 1000, 1100, 1200, 1300, 1400, 1500, 2000, 2500, 3000, 3500, 4000];

    public function __construct($saldo) {
        $this->id = uniqid();
        if (in_array($saldo, $this->cargasAceptadas)) {
            $this->saldo = $saldoInicial;
        }
    }

    public function setUltimoViaje() {
        $this->ultimoViaje = time();
    }

    public function resetViajesHoy() {
        $this->viajesHoy = 0;
    }

    public function sumarViaje() {
        $this->viajesHoy += 1;
    }

    public function descontarSaldo($monto) {
        $this->saldo -= $monto;
        $this->viajesRealizados++;
    }

    public function cargarSaldo($monto) {
        if (in_array($monto, $this->cargasAceptadas)) {
            if(($this->saldo + $monto) <= $this->limiteSaldo) {
                $this->saldo += $monto;
                echo "Saldo acreditado correctamente. Nuevo saldo: " . $this->saldo;
            }
            else {
                $this->saldoSinAcreditar = $this->saldo + $monto - $this->limiteSaldo;
                $this->saldo = $this->limiteSaldo;
                echo "Saldo acreditado correctamente. Nuevo saldo: " . $this->saldo;
                echo "Saldo sin acreditar: " . $this->getSaldoSinAcreditar;
            }
        }
        else {
         echo "Error al intentar acreditar saldo. Saldo actual: " . $this->saldo;
         return false;
        }
    }

    public function acreditarSaldo(){
        if ($this->saldoSinAcreditar > 0) {
            if ($this->saldo + $this->saldoSinAcreditar >= $this->limiteSaldo) {
                $this->saldoSinAcreditar = $this->saldo + $this->saldoSinAcreditar - $this->limiteSaldo;
                $this->saldo = $this->limiteSaldo;
            }
            else {
                $this->saldo + $this->saldoSinAcreditar;
                $this->saldoSinAcreditar = 0;
            }
        }
    }

    public function resetearViajes()
    {
        $diaDelMes = date('j');
        if ($diaDelMes === '1') {
            $this->viajesRealizados = 1;
        }
    }

    public function multiplicadorPrecio() {
        $this->resetearViajes();
        if ($this->viajesRealizados >= 1 && $this->viajesRealizados <= 29) {
            return 1;
        } elseif ($this->viajesRealizados >= 30 && $this->viajesRealizados <= 79) {
            return 0.8; 
        } else {
            return 0.75; 
        }
    }
}

class FranquiciaParcial extends Tarjeta {
    public $tipoTarjeta = "Franquicia Parcial"
}

class FranquiciaCompleta extends Tarjeta {
    public $tipoTarjeta = "Franquicia Completa"
}