@extends('adminlte/theme')
@section('ng','app')
@section('title','SMTDatabase')
@section('body')
    @include('smtdatabase.partial.header')

    <div class="container">
        <!-- will be used to show any messages -->
        @if (Session::has('message'))
            <div class="alert alert-info">{{ Session::get('message') }}</div>
        @endif

        <h3>Resultados de busqueda</h3>

        <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th></th>
            <th>Modelo</th>
            <th>Panel</th>
            <th>Componente</th>
            <th>Descripcion</th>
            <th>Asignacion</th>
        </tr>
        </thead>
            <tbody>
                @foreach($materiales as $material)
                    <tr>
                        <td class="text-center"> <a href="#" class="btn btn-info btn-xs"><i class="fa fa-plus"></i> Info</a> </td>
                        <td> {{ $material->modelo }}</td>
                        <td> {{ $material->logop }}</td>
                        <td> {{ $material->componente }}</td>
                        <td> {{ $material->descripcion_componente }}</td>
                        <td> {{ $material->asignacion }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    @include('iaserver.common.footer')
@endsection