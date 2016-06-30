@extends('angular')
@section('body')

    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand"></a>@yield('title','')
            </div>
            <ul class="nav navbar-nav">
                <li><a href="{{ url('monitorop/op/huawei') }}">Huawei</a></li>
                <li><a href="{{ url('monitorop/insaut') }}">Insaut</a></li>
            </ul>
        </div>
    </nav>
    <div class="container-fluid">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>OP</th>
                <th>TOTAL</th>
                <th>CONSUMO</th>
                <th>RESTANTE</th>
                <th>MODELO</th>
                <th>DESCRIPCION</th>
                <th>PORCENTAJE</th>
                <th>ULTIMA DECLARACION</th>

            </tr>
            </thead>
            <tbody>

            @foreach($resume as $modelo)

                <tr>

                    <td><a data-toggle="modal" data-target="#myModal" id="driver" class="btn btn-primary">{{$modelo->WIP_ENTITY_NAME}}</a></td>

                    <td>{{$modelo->START_QUANTITY}}</td>

                    <td>{{$modelo->QUANTITY_COMPLETED}}</td>

                    <td>{{$diferencia = $modelo->START_QUANTITY - $modelo->QUANTITY_COMPLETED}}</td>

                    <td>{{$modelo->SEGMENT1}}</td>

                    <td width="20%">{{$modelo->DESCRIPTION}}</td>

                    <td><div class="progress"><div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar"
                                                   aria-valuenow="0"
                                                   aria-valuemin="0"
                                                   aria-valuemax="{{$modelo->START_QUANTITY}}"
                                                   {{$TOTAL=$modelo->QUANTITY_COMPLETED*100/$modelo->START_QUANTITY}}

                                                   style="width:{{$TOTAL}}%">{{number_format($TOTAL,'2')}}%
                            </div></div></td>
                    <td>
                        @if(isset($modelo->ULTIMO_SERIE))
                            {{ \IAServer\Http\Controllers\MonitorOp\GetWipOtInfo::ultimaDeclaracion($modelo->ULTIMO_SERIE) }}
                        @else
                            @if(isset($modelo->ULTIMO_HISTORY))
                                {{ \IAServer\Http\Controllers\MonitorOp\GetWipOtInfo::ultimaDeclaracion($modelo->ULTIMO_HISTORY) }}
                            @else
                                Nunca
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
        <div class="container-fluid"  id="test">
            <div class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content" ID="stage">
                        <P>NO DISPONIBLE</P>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
