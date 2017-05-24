<?php

namespace IAServer\Http\Controllers\Aoicollector\Cuarentena;

use Carbon\Carbon;
use IAServer\Http\Controllers\Aoicollector\Inspection\FindInspection;
use IAServer\Http\Controllers\Aoicollector\Model\Cuarentena;
use IAServer\Http\Controllers\Aoicollector\Model\CuarentenaDetalle;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class AgregarCuarentena extends Controller
{
    public function multiple()
    {
        $create = false;

        $id_cuarentena = Input::get('id_cuarentena');

        $regex = '/(STK[0-9]+|[0-9]+)/';

        $input = Input::get('agregarmultiple');
        preg_match_all($regex, $input, $matches);

        $cuarentena = Cuarentena::find($id_cuarentena);

        if($cuarentena == null && !empty(Input::get('motivo')))
        {
            $cuarentena = new Cuarentena();
            $cuarentena->id_user_calidad = Auth::user()->id;
            $cuarentena->motivo = Input::get('motivo');
            $cuarentena->created_at = new Carbon();
            $cuarentena->updated_at = new Carbon();
            $cuarentena->released_at= null;

            $cuarentena->save();

            $id_cuarentena = $cuarentena->id;

            $create = true;
        }

        if($cuarentena==null) {
            return back()->with('message','Por favor complete todos los campos para crear la cuarentena');
        }

        foreach ($matches[0] as $barcode)
        {
            if(starts_with($barcode,'STK')) {

                dd('Agregar stocker completo '.$barcode);
            } else {
                $cuarentena = CuarentenaDetalle::where('barcode',$barcode)->first();

                if(isset($cuarentena))
                {
                    // Cuarentena existe!, quito release y actualizo update_at
                    $cuarentena->updated_at = new Carbon();
                    $cuarentena->released_at = null;
                    $cuarentena->save();
                } else {
                    // Nueva cuarentena
                    $find = new FindInspection();
                    $find->onlyLast = true;
                    $result = $find->barcode($barcode);

                    if(isset($result->last))
                    {
                        $add = new CuarentenaDetalle();
                        $add->id_cuarentena = $id_cuarentena;
                        $add->barcode = $barcode;
                        $add->created_at = new Carbon();
                        $add->updated_at = new Carbon();
                        $add->released_at= null;
                        $add->save();
                    }
                }
            }
        }

        if($create) {
            return redirect('aoicollector/cuarentena')->with('message','Cuarentena creada con exito!');
        } else {
            return back()->with('message','Adjuntar cuarentena ejecutado con exito!');
        }
    }

    public function single($barcode, $id_cuarentena)
    {

    }
}