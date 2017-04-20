<?php

namespace IAServer\Http\Controllers\Molinete;

use Carbon\Carbon;
use IAServer\Http\Controllers\Molinete\Model\TarjResult;
use IAServer\Http\Requests;
use IAServer\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class Molinete extends Controller
{
    public function index() {
        $molinete = $this->chequedList();

        $output = compact('molinete');
        return Response::multiple($output,'molinete.index');
    }

    public function add() {
        $tarjeta = Input::get('tarjeta');
        $resultado = Input::get('resultado');

        $add = new TarjResult();
        $add->Tarjeta = $tarjeta;
        $add->Resultado = $resultado;
        $add->Fecha = Carbon::now()->toDateTimeString();
        $add->save();

        $toRedis = $this->lastTarjetaCheck($tarjeta);
        if($toRedis!=null) {
            \LRedis::publish('molinete', json_encode($toRedis));
        }

        return 'done';
    }

    public function check() {
        $tarjeta = Input::get('tarjeta');

        $freePass = [
            '23319471' // Mati flores
        ];

        if(in_array($tarjeta, $freePass)) {
            $valor = 'free';
        } else {
            $valor = 'check';
        }

        echo "valor=".$valor;
    }

    public function lastTarjetaCheck($tarjeta)
    {
        $query = "
        select
            TOP 1

            t.Tarjeta,
            t.Resultado,
            t.Fecha,
            v.Legajo,
            v.Nombres,
            v.Apellido,
            v.Departamento,
            v.Puesto
        from TarjResult t
        left join dbo.Digi_vwEmpTarjDpto v on v.Tarjeta = t.Tarjeta
        where
            t.Tarjeta = '$tarjeta'
        order by t.Fecha desc
        ";

        $result = DB::connection('molinete')->select(DB::raw($query));

        return collect($result)->first();
    }

    public function chequedList($fecha_en="")
    {
        $query = "
        select
            t.Tarjeta,
            t.Resultado,
            t.Fecha,
            v.Legajo,
            v.Nombres,
            v.Apellido,
            v.Departamento,
            v.Puesto
        from TarjResult t
        left join dbo.Digi_vwEmpTarjDpto v on v.Tarjeta = t.Tarjeta

        WHERE
        ";

        if(empty($fecha_en))
        {
            $query .= "
                cast(t.fecha as DATE) = cast (GETDATE() as DATE)
            ";
        } else
        {
            $query .= "
                cast(t.fecha as DATE) = '$fecha_en'
            ";
        }

        $query .= "order by t.Fecha desc";

        $result = DB::connection('molinete')->select(DB::raw($query));

        return collect($result);
    }

}
