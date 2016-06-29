<?php
namespace IAServer\Http\Controllers\SMTDatabase;

use IAServer\Http\Controllers\Controller;
use IAServer\Http\Controllers\SMTDatabase\Model\Materiales;
use IAServer\Http\Requests;

use Illuminate\Support\Facades\Input;

class SMTDatabaseView extends Controller
{
    public static function index() {
        return view('smtdatabase.index');
    }

    public static function buscarComponente() {
        $componente = Input::get('componente');
        $materiales = Materiales::modelsWithComponente($componente)->get();

        $output = compact('materiales','componente');

        return view('smtdatabase.componente.buscar',$output);
    }

    public static function transportIndex() {
        return view('smtdatabase.transport.index');
    }

    public static function transportForm()
    {
        $op = Input::get('op');
        $output = SMTDatabaseTransport::handle($op,true);
        return view('smtdatabase.transport.index',$output);
    }

    public static function transportSubmit()
    {
        $output = SMTDatabaseTransport::transport();
        return view('smtdatabase.transport.index',$output);
    }
}
