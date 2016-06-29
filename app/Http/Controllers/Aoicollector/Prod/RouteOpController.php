<?php

namespace IAServer\Http\Controllers\Aoicollector\Prod;

use IAServer\Http\Controllers\Aoicollector\Model\Produccion;
use IAServer\Http\Controllers\Aoicollector\Model\RouteOp;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;


class RouteOpController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $op = Input::get('op');
        $routeop = RouteOp::where('op',$op)->get();

        $output = compact('op','routeop');
        return Response::multiple_output($output,'aoicollector.prod.routeop.index');
    }

    public function create()
    {
        $op = Input::get('op');
        $routeop = RouteOp::where('op',$op)->get();

        $output = compact('op','routeop');
        return Response::multiple_output($output,'aoicollector.prod.routeop.create');
    }

    public function store()
    {
        $rules = array(
            'declara'  => 'required|numeric',
            'op' => 'required',
            'puesto' => 'required'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return redirect('aoicollector/prod/routeop/create')
                ->withErrors($validator)
                ->withInput(Input::except('password'))
                ->with(['op'=>Input::get('op')]);
        } else {
            // store
            $store = new RouteOp();

            $store->op = Input::get('op');
            $store->name = strtoupper(Input::get('puesto'));
            $store->declare = Input::get('declara');

            $parent_op = trim(Input::get('op_puesto_anterior'));
            if(!empty($parent_op))
            {
                $store->parent_op = $parent_op;
            }

            $store->save();

            return redirect('aoicollector/prod/routeop?op='.Input::get('op'))->with('message','Ruta creada con exito!');
        }
    }

    public function destroy($id)
    {
        $message = 'Eliminado con exito!';
        $el = RouteOp::find($id);
        $op = $el->op;
        if($el) {
            // SI HAY OP EN PRODUCCION DEBERIA ELIMINAR CONFIGURACION
            $prod = Produccion::where('id_route_op',$el->id)->first();
            if($prod!=null)
            {
                $prod->id_route_op = null;
                $prod->save();
            }

            $el->delete();
        } else {
            $message = 'El elemento no existe!';
        }

        return redirect('aoicollector/prod/routeop?op='.$op)->with('message',$message);
    }
}