<?php

namespace IAServer\Http\Controllers\Flor;

use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;

class Petalo extends Controller
{
    public $name;

    public function name($name)
    {
        $this->name = $name;
    }
}
