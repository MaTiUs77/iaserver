@extends('angular')
@section('ng','app')
@section('title','Stocker - Lavados')
@section('body')
    <div class="container">

        @include('aoicollector.stocker.lavado.menu')

        @if(hasRole('stocker_lavado') || isAdmin())
            <!-- BUSQUEDA -->
            <div class="row">
                <div class="col-lg-4">
                    <form method="GET" action="{{ route('aoicollector.stocker.lavado.index') }}" >
                        <div class="input-group" >
                            <input type="text" name="find" class="form-control" placeholder="Stocker" />
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-info"> Ingresar al lavado</button>
                            </span>
                        </div>
                    </form>
                </div>

                <div class="col-lg-4">
                    <form method="POST" action="{{ route('aoicollector.stocker.lavado.etiquetar') }}" >
                        <div class="input-group" >
                            <input type="text" name="stk" class="form-control" placeholder="Stocker" />
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-info"> Re etiquetar</button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END BUSQUEDA -->
        @endif
        <br>

        <h3>Lavado de Stockers</h3>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Stocker</th>
                        <th>Ruta</th>
                        <th>Lavados</th>
                        <th>Operador</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stockers as $item)
                        <tr>
                            <td>{{ $item->barcode }}</td>
                            <td>
                                @if($item->name==null)
                                    <div class="label label-danger">Sin ruta</div>
                                @else
                                    <div class="label label-{{ $item->id_stocker_route == 2 ? 'primary' : 'success' }}">{{ $item->name }}</div>
                                @endif
                            </td>
                            <td>{{ $item->lavados()->count() }}</td>
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
        </div>
    </div>

    @include('iaserver.common.footer')
@endsection