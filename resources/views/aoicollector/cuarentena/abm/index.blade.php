@extends('adminlte/theme')
@section('ng','app')
@section('mini',true)
@section('title','Aoicollector - Cuarentena')
@section('head')
    <style>
        .datatable tbody tr td {
            text-align: center;
        }

        .datatable thead th {
            background-color: #2D6CA2;
            color: white;
            text-align: center;
        }

    </style>
@endsection
@section('body')

<div>
    <a href="{{ url('aoicollector/cuarentena/create') }}" class="btn btn-info"><i class="fa fa-plus"></i> Crear cuarentena</a>
    <hr>

    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif

    @if(isset($cuarentenas) && count($cuarentenas)>0)
        <table class="table table-bordered table-striped table-hover datatable">
            <thead>
            <tr style="text-align: center;">
                <th>Opciones</th>
                <th>Usuario</th>
                <th>Motivo</th>
                <th>Comprometidas</th>
                <th>En cuarentena</th>
                <th>Liberadas</th>
                <th>Fecha de cuarentena</th>
                <th>Fecha de alta</th>
            </tr>
            </thead>
            <tbody>
            @foreach($cuarentenas as $cuarentena)
                <tr style="text-align: center;">
                    <td>
                        <a href="{{ route('aoicollector.cuarentena.edit',$cuarentena->id) }}" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i> Editar</a>
                    </td>
                    <td>{{ $cuarentena->joinUser->name }}</td>
                    <td>{{ $cuarentena->motivo }}</td>
                    <td>{{ $cuarentena->countTotal() }}</td>
                    <td>{{ $cuarentena->countCuarentena() }}</td>
                    <td>{{ $cuarentena->countReleased() }}</td>
                    <td>{{ $cuarentena->created_at->format('d/m/Y h:i') }}</td>
                    <td>{{ $cuarentena->released_at }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <h3>No hay cuarentenas creadas</h3>
    @endif

</div>

@include('iaserver.common.footer')
@endsection

