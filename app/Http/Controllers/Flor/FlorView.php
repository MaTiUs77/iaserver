<?php

namespace IAServer\Http\Controllers\Flor;

use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;

class FlorView extends Controller
{
    public function index()
    {
        $game = new FlorGame();
        $game->play();

        dump($game);

        $game->uplevel();

        dump($game);



        /* $output = compact('flor');
         return view('flor.index', $output);*/
    }
}
