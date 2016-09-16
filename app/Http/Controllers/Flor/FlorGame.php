<?php

namespace IAServer\Http\Controllers\Flor;

use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;

class FlorGame extends Controller
{
    public $level = 1;
    public $flores = [];

    public function play()
    {
        $flor = new Flor($this->level);
        $flor->fill();

        $this->flores[] = $flor;
    }

    public function uplevel()
    {
        foreach ($this->flores as $flor) {
            $this->upFuego($flor);
        }

        $this->level++;
    }

    public function upFuego(Flor $flor)
    {
        foreach ($flor->fuego as $fuego) {
            $this->upFuego($flor);
        }
    }
}
