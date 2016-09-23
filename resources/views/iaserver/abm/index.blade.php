@extends('adminlte/theme')
@section('ng','app')
@section('title','Administracion')
@section('bodytag',' ng-controller="IAAbm" ')
@section('body')

    @include('iaserver.abm.partial.header')

    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif

    <table class="table table-bordered">
        <thead class="panel">
            <tr>
                <th></th>
                <th>User</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Roles</th>
            </tr>
            </thead>
            <tbody>
            @if(count($users)==0)
                <tr>
                    <td colspan="14">
                        <a href="{{ url('abm/create') }}" class="btn btn-info"><span class="fa fa-user-plus fa-lg"></span> Agregar usuario</a>
                    </td>
            @endif
            @foreach($users as $user)
                <tr>
                    <td style="width: 100px;">
                        {!! IABtnDropDown('abm',$user) !!}
                    </td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->profile ? $user->profile->nombre : ''}}</td>
                    <td>{{ $user->profile ? $user->profile->apellido : ''}}</td>
                    <td>
                        <a class="btn btn-xs btn-success" ng-click="modalPermisos('{{ route('iaserver.forms.prompt',['holder'=>'Permisos']) }}')">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add
                        </a>
                        @foreach($user->roles()->get() as $rol)
                            <button class="btn btn-xs btn-default">{{ $rol->display_name }}</button>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@include('iaserver.abm.partial.footer')


<script>
    app.controller("IAAbm",function($scope,IaCore){

        $scope.modalPermisos = function(route) {
            IaCore.modal({
                scope: $scope,
                route: route,
                title: 'Permisos',
                type: 'success'
            });
        };
    })

</script>

@endsection
