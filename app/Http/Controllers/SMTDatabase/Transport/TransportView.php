<?php
namespace IAServer\Http\Controllers\SMTDatabase\Transport;

use IAServer\Http\Controllers\Controller;
use IAServer\Http\Requests;

use Illuminate\Support\Facades\Input;

class TransportView extends Controller
{
    public static function index() {
        return view('smtdatabase.transport.index');
    }

    public static function form()
    {
        $op = Input::get('op');
        $output = Transport::handle($op,true);
        return view('smtdatabase.transport.index',$output);
    }

    public static function submit()
    {
        $output = Transport::transport();
        return view('smtdatabase.transport.index',$output);
    }
}
