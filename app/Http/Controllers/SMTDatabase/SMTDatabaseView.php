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


}
