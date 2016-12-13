@extends('angular')
@section('ng','app')
@section('title','Trazabilidad - Transportar OP')
@section('body')
    <div ng-controller="trazaController">

        <table style="width: 100%">
            <tr>
                <td style="vertical-align: top;">
                    @{{ modal }}
                    <div style="padding: 5px;">
                        <!-- will be used to show any messages -->
                        @if (Session::has('message'))
                            <div class="alert alert-info">{{ Session::get('message') }}</div>
                        @endif

                        <div>
                            <form method="POST" action="?">
                                <!-- BUSQUEDA -->
                                <input type="text" name="newop" class="form-control" placeholder="Ingresar nueva OP">
                                <div style="width: 500px;margin-bottom: 5px;">
                                    <textarea name="barcodes" rows="6" class="form-control" placeholder="Ingresar multiples barcode" ng-required="true" required="required" style="margin-top: 0px; margin-bottom: 0px; height: 234px;"></textarea>
                                </div>

                                <button type="submit" style="float:left;" class="btn btn-info"><i class="glyphicon glyphicon-search"></i> Consultar</button>
                                <button type="submit" style="float:left;" name="execute" value="execute" class="btn btn-info"><i class="glyphicon glyphicon-search"></i> Cambiar</button>
                                <!-- END BUSQUEDA -->
                            </form>
                        </div>

                        <hr>
                        @foreach($inspecciones as $inspeccion)
                            {{ $inspeccion->panel_barcode }}
                            {{ $inspeccion->inspected_op }}

                            <hr>
                        @endforeach

                    </div>
                </td>
            </tr>
        </table>
    </div>

    @include('trazabilidad.partial.footer')
@endsection