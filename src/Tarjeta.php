<?php

namespace TrabajoSube;

class Tarjeta {
    public $id;
    public $saldo;
    public $saldoSinAcreditar;
    public $ultimaLinea;
    public $ultimoViaje;
    public $viajesHoy = 0;
    public $viajesMes = 0;
    public $tipoTarjeta = "Común";
    public $tipoFranquicia = "Sin Franquicia";
    public $limiteSaldo = 6600;
    public $cargasAceptadas = [150, 200, 250, 300, 350, 400, 450, 500, 600, 700, 800, 900, 1000, 1100, 1200, 1300, 1400, 1500, 2000, 2500, 3000, 3500, 4000];

    public function __construct() {
        $this->id = uniqid();
        $this->saldo = 0;
    }

    public function cargarSaldo($monto) {
        if (in_array($monto, $this->cargasAceptadas)) {
            $this->saldoSinAcreditar += $monto;
        }
        else {
            return false;
        }
    }

    public function acreditarSaldo(){
        $totalAbonado;
        if ($this->saldoSinAcreditar > 0) {
            if ($this->saldo + $this->saldoSinAcreditar >= $this->limiteSaldo) {
                $totalAbonado = $this->limiteSaldo - $this->saldo;
                $this->saldoSinAcreditar = $this->saldo + $this->saldoSinAcreditar - $this->limiteSaldo;
                $this->saldo = $this->limiteSaldo;
            }
            else {
                $totalAbonado = $this->saldoSinAcreditar;
                $this->saldo += $this->saldoSinAcreditar;
                $this->saldoSinAcreditar = 0;
            }
        }
        return $totalAbonado;
    }
}