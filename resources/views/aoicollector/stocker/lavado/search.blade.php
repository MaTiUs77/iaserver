@extends('adminlte/theme')
@section('ng','app')
@section('mini',true)
@section('nobox',true)
@section('title','Lavado de Stockers - Historial')
@section('body')
<div class="container">
    <!-- will be used to show any messages -->
    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Historial de lavados</h3>
            </div>

            <div class="box-body chart-responsive">
                <!-- BUSQUEDA -->
                <div class="row">
                    <div class="col-sm-4">
                        <form method="POST" action="{{ route('aoicollector.stocker.lavado.search') }}" >
                            <div class="input-group" >
                                <input type="text" name="stkbarcode" class="form-control" autocomplete="off" placeholder="Ingresar codigo de stocker" />
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary"> Ver historial</button>
                                </span>
                            </div>
                        </form>
                    </div>

                    <div class="col-sm-4 pull-right">
                        <form method="POST" action="{{ route('aoicollector.stocker.lavado.etiquetar') }}" >
                            <div class="input-group" >
                                <input type="text" name="stk" class="form-control" autocomplete="off" placeholder="Ingresar codigo de stocker" />
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-success"> Iniciar lavado</button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END BUSQUEDA -->
                <br>

                @if(isset($historial) && count($historial)>0)
                    <table class="table table-bordered table-striped datatable">
                        <thead>
                        <tr>
                            <th>Stocker</th>
                            <th>Ruta</th>
                            <th>Operador</th>
                            <th>Fecha</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($historial as $item)
                            <tr>
                                <td>{{ $item->barcode }}</td>
                                <td>
                                    @if($item->name==null)
                                        <div class="label label-danger">Sin ruta</div>
                                    @else
                                        <div class="label label-{{ $item->id_stocker_route == 2 ? 'primary' : 'success' }}">{{ $item->name }}</div>
                                    @endif
                                </td>
                                <td>
                                    <?php
                                    $inspector = $item->inspector();
                                    ?>
                                    @if(isset($inspector))
                                        {{ $inspector->fullname  }}
                                    @else
                                        Desconocido
                                    @endif
                                </td>
                                <td>{{ $item->created_at ? $item->created_at  : 'Desconocido' }}</td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <p>No hay historial de lavados</p>
                @endif

            </div>
        </div>

</div>

    @include('iaserver.common.footer')
@endsection