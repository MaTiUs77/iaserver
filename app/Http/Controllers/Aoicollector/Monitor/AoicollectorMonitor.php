<?php
namespace IAServer\Http\Controllers\Aoicollector\Monitor;

use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;

/**
 * Simple Front para el monitor del collector, se comunica con Redis para visualizar los datos
 * @package IAServer\Http\Controllers\Aoicollector\Monitor
 */
class AoicollectorMonitor extends Controller
{
    /**
     * Pagina principal del monitor
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View aoicollector.monitor.index
     */
    public function index()
    {
        return view('aoicollector.monitor.index');
    }
}
