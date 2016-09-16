<?php

namespace IAServer\Http\Controllers\Flor;

use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;

class Flor extends Controller
{
    public $level = 1;
    public $id;

    public $agua;
    public $tierra = [];
    public $aire = [];
    public $fuego = [];

    public function __construct($level)
    {
        $this->level=$level;
        $this->id = rand(1,100);
    }

    public function fill()
    {
        $this->setAgua();
        $this->setFakeTierra();
        $this->setFakeAire();
        $this->setFakeFuego();
    }

    public function setAgua()
    {
        $pt = new Petalo();
        $pt->name('Matias');

        $this->agua = $pt;
    }

    public function setFakeTierra()
    {
        for($i=1;$i<=2;$i++)
        {
            $pt = new Petalo();
            $pt->name('F'.$this->id.$this->level.'-'.$i);
            $this->tierra[] = $pt;
        }
    }

    public function setFakeAire()
    {
        for($i=1;$i<=4;$i++)
        {
            $pt = new Petalo();
            $pt->name('F'.$this->id.$this->level.'-'.$i);
            $this->aire[] = $pt;
        }
    }

    public function setFakeFuego()
    {
        for($i=1;$i<=8;$i++)
        {
            $pt = new Petalo();
            $pt->name('F'.$this->id.$this->level.'-'.$i);
            $this->fuego[] = $pt;
        }
    }
}
