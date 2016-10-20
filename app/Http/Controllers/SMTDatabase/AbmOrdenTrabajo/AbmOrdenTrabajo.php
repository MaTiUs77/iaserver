<?php
namespace IAServer\Http\Controllers\SMTDatabase\AbmOrdenTrabajo;

use IAServer\Http\Controllers\Controller;
use IAServer\Http\Controllers\SMTDatabase\Model\OrdenTrabajo;
use IAServer\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class AbmOrdenTrabajo extends Controller
{
    public function __construct()
    {
        //$this->middleware('role:admin');
    }

    public function find()
    {
        $ordenes = OrdenTrabajo::orderBy('modelo','desc')
            ->orderBy('lote','desc')
            ->orderBy('panel','desc');

        $find = trim(Input::get('find'));
        if(starts_with($find,'OP-'))
        {
            $ordenes = $ordenes->where('op',$find);
        } else
        {
            $ordenes = $ordenes->where('modelo','like',$find.'%');
        }

        $ordenes = $ordenes->paginate(50);

        $output = compact('ordenes');
        return view('smtdatabase.abmordentrabajo.index',$output);
    }

    public function index()
    {
        $ordenes = OrdenTrabajo::orderBy('modelo','desc')
            ->orderBy('lote','desc')
            ->orderBy('panel','desc');

        if(Input::get('op'))
        {
            $ordenes = $ordenes->where('op',Input::get('op'));
        }

        $ordenes = $ordenes->paginate(50);

        $output = compact('ordenes');
        return view('smtdatabase.abmordentrabajo.index',$output);
    }

    public function edit($id)
    {
        $orden = OrdenTrabajo::find($id);
        $output = compact('orden');
        return view('smtdatabase.abmordentrabajo.edit',$output);
    }

    public function update($id)
    {
        $rules = array(
            'op' => 'required',
            'modelo' => 'required',
            'panel' => 'required',
            'lote' => 'required',
            'qty' => 'numeric|required',
            'prod_aoi' => 'numeric|required',
            'prod_man' => 'numeric|required',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return redirect('abmordentrabajo/'.$id.'/edit')
                ->withErrors($validator)
                ->withInput(Input::all());
        } else {
            $orden = OrdenTrabajo::find($id);
            $orden->fill(Input::all());
            $orden->save();

            return redirect(route('smtdatabase.abmordentrabajo.index'))->with('message','Orden de Trabajo editada con exito!');
        }
    }

    public function destroy($id)
    {
        $el = OrdenTrabajo::find($id);
        if($el) {
            $el->delete();
            $message = 'Orden eliminada con exito: '.$el->op;
        } else {
            $message = 'La orden no existe!';
        }

        return redirect(route('smtdatabase.abmordentrabajo.index'))->with('message',$message);
    }
}
