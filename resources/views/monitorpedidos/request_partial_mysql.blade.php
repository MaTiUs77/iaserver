@extends('monitorpedidos.index')
@section('body')
    <?php
    $self = url('amr/parciales/almacen'); //Obtenemos la página en la que nos encontramos
    header("refresh:600; url=$self"); //Refrescamos cada 300 segundos
    ?>
    {{--FORMULARIO DE BUSQUEDA--}}
    <div  class="container-fluid">

        <div class="row">
                <div class="col-lg-4" align="center">
                    <form class="navbar-form navbar-left" role="search" method="GET" action="{{url ('amr/parciales/almacen')}}">

                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="PART_NUMBER" name="valor" required="true">
                        </div>

                        <button type="submit" class="btn btn-info"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
                    </form>
                </div>
            <div class="col-lg-4">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Buscar por linea
                        <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><a href="{{url('amr/parciales/almacen')}}">Todas las lineas</a></li>
                        <li><a href="{{url('amr/parciales/almacen/SMT - 2')}}">SMT - 2</a></li>
                        <li><a href="{{url('amr/parciales/almacen/SMT - 3')}}">SMT - 3</a></li>
                    </ul>
                </div>
            </div>
                <div class="col-lg-4">
                    <label>TOTAL DE REGISTROS:@if($reserva->count() != 0)
                            {{ $reserva->count()}} de {{\IAServer\Http\Controllers\MonitorPedidos\Model\reservas::all()->count()}}</label>
                    @else <label class="alert-danger">SIN RESULTADOS</label>
                    @endif

                </div>
        </div>
        <div class="row">
        @if (Session::has('message'))
            <div class="alert alert-info">{{ Session::get('message') }}</div>
        @endif
        </div>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>NRO_OP</th>
                    <th>LINEA</th>
                    <th>MAQUINA</th>
                    <th>FEEDER</th>
                    <th>PARTNUMBER</th>
                    <th>LPN</th>
                    <th>UBICACION</th>
                    <th>TIEMPO</th>
                    <th>ACCIONES</th>
                </tr>
                </thead>
                <tbody>
                {{--{{dd($reserva)}}--}}
                @foreach($reserva as $r)
                    <tr>
                <td><a class="btn btn-primary">{{$r->op}}</a></td>

                <td>{{$r->linea}}</td>

                <td>{{$r->maquina}}</td>

                <td>{{$r->feeder}}</td>

                <td>{{$r->pn}}</td>

                <td>{{$r->lpn}}</td>

                <td>{{$r->ubicacion}}</td>

                <td>{{\IAServer\Http\Controllers\MonitorOp\GetWipOtInfo::tiempoAlmacen($r->tiempopedido)}}</td>

                <td width="10px"><form method="post" action="{{url('amr/entregar/'.$r->id)}}"><button class="btn btn-success btn-xs btn-detail" name="entregar">ENTREGAR</button></form>

                    {{--<button class="btn btn-default" data-record-id="{{$r->id}}" data-record-title="Something cool" data-toggle="modal" data-target="#confirm-delete">--}}
                        {{--Delete "Something cool", #54--}}
                    {{--</button>--}}

                @endforeach
                    {{--{{dd($reserva)}}--}}
                    @foreach($pedido as $modelo)
                        <?php
                            $lpn = \IAServer\Http\Controllers\MonitorPedidos\CogiscanPedidos::lpnInDb2Tools($modelo->codMat);
                        ?>

                    @foreach($lpn as $totalLpn)
                    <tr>
                        {{--{{dd($totalLpn)}}--}}
                        <?php $existeReserva = \IAServer\Http\Controllers\MonitorPedidos\CogiscanPedidos::existInReserva($modelo->PROD_LINE,$modelo->MAQUINA,$modelo->UBICACION,$totalLpn->field2,$totalLpn->field1,$modelo->id);?>

                        @if($existeReserva == 1)

                        <?php
//                            dd($existeReserva);
                            $reservas = new \IAServer\Http\Controllers\MonitorPedidos\CogiscanPedidos();
                            $insertReserva = $reservas->insertReserva($modelo->op,$modelo->PROD_LINE,$modelo->MAQUINA,$modelo->UBICACION,$totalLpn->field2,$totalLpn->field1,$totalLpn->field5,$modelo->id,$modelo->timestamp);
                        ?>
                            @else
                    </tr>
                    @endif
                        @endforeach
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>
                </div>
                <div class="modal-body">
                    <p>You are about to delete <b><i class="title"></i></b> record, this procedure is irreversible.</p>
                    <p>Do you want to proceed?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger btn-ok" id="boton">Delete</button>
                </div>
            </div>
        </div>
    </div>

    {!! IAScript('vendor/monitorpedidos/app.js') !!}
    {!! IAScript('vendor/monitorpedidos/request.js') !!}

    <script>
        $('#confirm-delete').on('click', '.btn-ok', function(e) {
            var $modalDiv = $(e.delegateTarget);
            var id = $(this).data('recordId');
            $.ajax({url: '{{url('/amr/cancelar/')}}'+'/'+id, TYPE: 'get'})
           // $.post('{{url('/amr/cancelar/')}}' +'/'+id).then()
            console.log(id);

            $modalDiv.addClass('loading');
            setTimeout(function() {
                $modalDiv.modal('hide').removeClass('loading');
            }, 1000)
        });
        $('#confirm-delete').on('show.bs.modal', function(e) {
            var data = $(e.relatedTarget).data();
            $('.title', this).text(data.recordTitle);
            $('.btn-ok', this).data('recordId', data.recordId);
        });

    </script>

@endsection
