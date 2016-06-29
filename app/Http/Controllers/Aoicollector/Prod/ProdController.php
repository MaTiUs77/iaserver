<?php

namespace IAServer\Http\Controllers\Aoicollector\Prod;

use IAServer\Http\Controllers\Aoicollector\Model\Produccion;
use IAServer\Http\Controllers\Aoicollector\Model\RouteOp;
use IAServer\Http\Controllers\IAServer\Debug;
use IAServer\Http\Controllers\SMTDatabase\SMTDatabase;
use IAServer\Http\Controllers\Trazabilidad\Declaracion\Wip\Wip;
use IAServer\Http\Controllers\Trazabilidad\Sfcs\Sfcs;
use IAServer\Http\Controllers\Trazabilidad\Trazabilidad;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use IAServer\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;

class ProdController extends Controller
{
    /**
     * El metodo __construct define si se realiza Debug del controlador
     *
     * @var Debug|null
     */
    public $debug = null;

    function __construct()
    {
        //$this->debug = new Debug($this);
    }

    public function index()
    {
        return view('aoicollector.prod.index');
    }

    public function aoiProductionInfo($aoibarcode,$first=false)
    {
        $prod = Produccion::fullInfo($aoibarcode);

/*        if($first)
        {
            $prod['produccion'] = head($prod['produccion']);
        }*/

        return Response::multiple_output($prod);
    }

    public function infoOp($op,$aoibarcode="")
    {
        $op = trim(strtoupper($op));
        $w = new Wip();
        $wip = $w->findOp($op,false);
        $routeop = RouteOp::where('op',$op)->get();
        $smt = SMTDatabase::findOp($op);

        $sfcs = new Sfcs();
        $sfcs = $sfcs->puestosOp($op);

        $output = compact('aoibarcode','op','wip','smt','routeop','sfcs');
        return Response::multiple_output($output,'aoicollector.prod.partial.infoop');
    }

    public function infoOpSubmit()
    {
        $var = (object) Input::all();

        $prod = Produccion::where('barcode',$var->aoibarcode)->first();

        if(isset($prod->id))
        {
            $prod->op = $var->op;
            $prod->line_id = $var->line_id;
            $prod->puesto_id = $var->puesto_id;
            $prod->modelo_id = $var->modelo_id;
            $prod->id_stocker = null;
            $prod->id_route_op = null;

            $prod->save();
        }

        return redirect(route('aoicollector.prod.index'));
    }

    public function infoOpRemove($aoibarcode)
    {
        $prod = Produccion::where('barcode',$aoibarcode)->first();

        if(isset($prod->id))
        {
            $prod->op = null;
            $prod->line_id = null;
            $prod->puesto_id = null;
            $prod->modelo_id = null;
            $prod->id_stocker =null;
            $prod->id_route_op =null;

            $prod->save();
        }

        return redirect(route('aoicollector.prod.index'));
    }

    public function userLogin()
    {
        $output = array();

        $user = User::where('id',Input::get('userid'))->where('name',Input::get('name'))->first();

        if(isset($user->id)) {
            $user = Auth::loginUsingId(17,true);
            if ($user->hasProfile()) {
                $user->fullname = $user->profile->fullname();
            } else
            {
                $user->fullname = $user->name;
            }

            $prod = Produccion::where('barcode',Input::get('aoibarcode'))->first();
            if(isset($prod->id))
            {
                if($prod->id_user == $user->id)
                {
                    $prod->id_user = null;

                } else
                {
                    $prod->id_user = $user->id;
                }

                $prod->save();
            }

            $output = $user;
        }else{
            $output = array('error'=>'Usuario/Password desconocido');
        }

        return $output;
    }

    public Function userLogout(){
        Auth::logout();
        $output = array('done'=>'logout');

        return redirect(route('aoicollector.prod.index'));
    }
}