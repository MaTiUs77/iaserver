@extends('angular')
@section('ng','app')
@section('title','SMTDatabase')
@section('body')
    @include('smtdatabase.partial.header')

    <div class="container">
        <!-- will be used to show any messages -->
        @if (Session::has('message'))
            <div class="alert alert-info">{{ Session::get('message') }}</div>
        @endif

        <form class="form-horizontal" role="form" method="post" action="{{ route('smtdatabase.componentes.buscar') }}">
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-1">
                    <h3>Buscar componente</h3>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-1">
                    <input ng-required="true"  type="text" class="form-control" placeholder="Ej: EAG63530103" name="componente">
                </div>
                <div class="col-sm-4">
                    <input type="submit" value="Buscar" class="btn btn-primary">
                </div>
            </div>
        </form>
    </div>

    @include('iaserver.common.footer')
@endsection