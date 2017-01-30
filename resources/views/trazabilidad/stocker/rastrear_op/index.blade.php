@extends('angular')
@section('ng','app')
@section('title','Trazabilidad - Rastrear OP de Stocker')
@section('body')
    <div ng-controller="trazaController">

        <table style="width: 100%">
            <tr>
                <td style="vertical-align: top;">
                    @include('trazabilidad.partial.header')
                </td>
            </tr>
        </table>

        <div class="row">
            <div class="col-xs-12">
                <h3>
                    Stockers en {{ $op }}
                </h3>
            </div>

            @foreach($allstocker as $stocker)
                <div class="col-xs-6 col-sm-2">
                    <div class="panel panel-default" style="{{ ($stocker->error_total>0) || ($stocker->declarado_total==0) ? 'background-color:#ffcece' : '' }}">
                        <div class="panel-body">
                            <span class="label pull-right" ng-class="{{ $stocker->id_stocker_route }} == 1 ? 'label-success' : 'label-primary'" style="padding:5px;">{{ $stocker->paneles * $stocker->bloques }}</span>
                            {{ $stocker->barcode }}

                            <div style="padding-top: 5px;border-top:1px solid #e2e2e2;">
                                <div class="label" ng-class="{{ $stocker->id_stocker_route }} == 1 ? 'label-success' : 'label-primary'">{{ $stocker->name }}</div>
                            </div>

                            {{ $stocker->linea }}

                            <div>
                                <span class="label label-success">{{ $stocker->declarado_total }}</span>
                                <span class="label label-info">{{ $stocker->pendiente_total }}</span>
                                <span class="label label-danger">{{ $stocker->error_total }}</span>
                            </div>

                        </div>
                        <div style="color: #727272;font-size: 10px;text-align: center;background-color: #e3e3e3;">
                            {{ $stocker->created_at }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @include('trazabilidad.partial.footer')
@endsection