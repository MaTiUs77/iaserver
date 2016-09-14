@extends('monitorpedidos.index')
@section('body')
<div class="container">


        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Buscar por linea
                <span class="caret"></span></button>
            <ul class="dropdown-menu">
                <li><a href="{{url('amr/pedidos/transito')}}">Todas las lineas</a></li>
                <li><a href="{{url('amr/pedidos/transito/SMT - 1')}}">SMT - 1</a></li>
                <li><a href="{{url('amr/pedidos/transito/SMT - 2')}}">SMT - 2</a></li>
                <li><a href="{{url('amr/pedidos/transito/SMT - 3')}}">SMT - 3</a></li>
                <li><a href="{{url('amr/pedidos/transito/SMT - 4')}}">SMT - 4</a></li>
                <li><a href="{{url('amr/pedidos/transito/SMT - 5')}}">SMT - 5</a></li>
                <li><a href="{{url('amr/pedidos/transito/SMT - 6')}}">SMT - 6</a></li>
            </ul>
        </div>
<br>
    @if($reserva->count()==0)
        <label class="alert-danger">SIN RESULTADOS</label>
    @endif
</div>

        @if(!$reserva->isEmpty())
            <div class="container">
                <div class="panel panel-primary">
                    <div class="panel-heading" align="center">reservas</div>
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>OP</th>
                            <th>LINEA</th>
                            <th>MAQUINA</th>
                            <th>FEEDER</th>
                            <th>PARTNUMBER</th>
                            <th>LPN</th>
                            <th>UBICACION</th>
                            <th>ID PEDIDO</th>
                            <th>FECHA</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($reserva as $history_reservas)
                            <tr>
                                <?php  $lpn = $history_reservas->lpn;
                                $lpn = substr($lpn,0,-19)?>
                                <td> {{$history_reservas->op}} </td>
                                <td> {{$history_reservas->linea}} </td>
                                <td> {{$history_reservas->maquina}} </td>
                                <td> {{$history_reservas->feeder}} </td>
                                <td> {{$history_reservas->pn}} </td>
                                <td> <?php echo $lpn ?> </td>
                                <td> {{$history_reservas->ubicacion}} </td>
                                <td> {{$history_reservas->id_pedido}} </td>
                                <td> {{$history_reservas->tiempopedido}} </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
@endsection