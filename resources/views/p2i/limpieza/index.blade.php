@extends('angular')
@section('ng','app')
@section('title','P2i - Lista de registros de limpieza')
@section('body')
    @include('p2i.common.header')
    @include('p2i.common.bread',['bread'=>['Limpieza','Ver registros']])

    <div style="float: right;">
        @include('iaserver.common.datepicker',['date_session'=>Session::get('date_session'),'route'=> url('p2i/carga')])
    </div>

    <!-- will be used to show any messages -->
    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif

    @if(count($limpieza)==0)
        <h3>No hay registros cargados en el dia {{ Session::get('date_session') }}</h3>
    @else
        <table class="table table-bordered">
            <thead class="panel">
            <tr>
                <th></th>
                <th>Camara/Secador</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Aspirado de Camara</th>
                <th>Limpieza de Laminas Laterales</th>
                <th>Limpieza de Burlete de Puerta</th>
                <th>Verificacion de Rejilla de Monomero</th>
                <th>Aspiracion de Rejillas Laterales </th>
                <th>Verificacion de Dummies</th>
                <th>Set de Jigs limpiados</th>
                <th>Limpieza externa de Secador y P2i</th>
                <th>Presion de Helio</th>
                <th>Ciclo Numero (Run Number)</th>
                <th>Operador</th>
            </tr>
            </thead>
            <tbody>
            @foreach($limpieza as $key => $value)
                <tr>
                    <td style="width: 50px;">
                        {!! IABtnDelete(route('p2i.limpieza.destroy', $value->id)) !!}
                    </td>
                    <td>
                        @if($value->camara)
                            Camara {{ $value->camara }}
                        @endif
                        @if($value->secador)
                            Secador {{ $value->secador}}
                        @endif
                    </td>
                    <td style="min-width: 100px;">
                        {{ \Carbon\Carbon::createFromFormat('Y-m-d',$value->fecha)->format('d/m/Y') }}
                    </td>
                    <td>{{ $value->hora }}</td>
                    <td>
                        @if($value->aspirado_camara) <span class="glyphicon glyphicon-ok"></span> @endif
                    </td>
                    <td>
                        @if($value->limp_laminas_laterales) <span class="glyphicon glyphicon-ok"></span> @endif
                    </td>
                    <td>
                        @if($value->limp_burlete_puerta) <span class="glyphicon glyphicon-ok"></span> @endif
                    </td>
                    <td>
                        @if($value->verif_rejilla_monomero) <span class="glyphicon glyphicon-ok"></span> @endif
                    </td>
                    <td>
                        @if($value->aspirado_rejillas_laterales) <span class="glyphicon glyphicon-ok"></span> @endif
                    </td>
                    <td>
                        @if($value->verif_dummies) <span class="glyphicon glyphicon-ok"></span> @endif
                    </td>
                    <td>
                        @if($value->limp_jigs) <span class="glyphicon glyphicon-ok"></span> @endif
                    </td>
                    <td>
                        @if($value->limp_p2i_y_secador) <span class="glyphicon glyphicon-ok"></span> @endif
                    </td>
                    <td>
                        @if($value->presion_helio) <span class="glyphicon glyphicon-ok"></span> @endif
                    </td>
                    <td>{{ $value->ciclo }}</td>
                    <td>{{ $value->id_operador }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

    @include('p2i.common.footer')
@endsection
