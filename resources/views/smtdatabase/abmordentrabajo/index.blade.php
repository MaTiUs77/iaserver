@extends('angular')
@section('ng','app')
@section('title','Orden de Trabajo')
@section('body')
    @include('smtdatabase.partial.header')

    <div class="container">
        <!-- will be used to show any messages -->
        @if (Session::has('message'))
            <div class="alert alert-info">{{ Session::get('message') }}</div>
        @endif

        <h3>Ordenes de trabajo</h3>

        <!-- BUSQUEDA -->
        <div class="row">
            <div class="col-lg-3 pull-right">
                <form method="POST" action="{{ route('smtdatabase.abmordentrabajo.find') }}" >
                    <div class="input-group" >
                        <input type="text" name="find" class="form-control" placeholder="OP o Modelo" value="{{ Input::get('op')  }}"/>
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-info"><i class="glyphicon glyphicon-search"></i> Buscar</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
        <!-- END BUSQUEDA -->
        <br>

        <table class="table table-bordered table-striped">
        <thead>
        <tr>
            @if (hasRole('smtdatabase_operator') || isAdmin())
                <th class="col-lg-1"></th>
            @endif
            <th>Op</th>
            <th>Modelo</th>
            <th>Panel</th>
            <th>Lote</th>
            <th>Qty</th>
            <th>Placas inspeccionadas</th>
        </tr>
        </thead>
            <tbody>
                @foreach($ordenes as $orden)
                    <tr>
                        @if(hasRole('smtdatabase_operator') || isAdmin())
                            <td>{!! IABtnDropDown('smtdatabase.abmordentrabajo',$orden) !!}</td>
                        @endif
                        <td>{{ $orden->op }}</td>
                        <td>{{ $orden->modelo }}</td>
                        <td>{{ $orden->panel }}</td>
                        <td>{{ $orden->lote }}</td>
                        <td>{{ $orden->qty }}</td>
                        <td>{{ $orden->prod_aoi }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($ordenes instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {!! $ordenes->links() !!}
        @endif
    </div>

    @include('iaserver.common.footer')
@endsection