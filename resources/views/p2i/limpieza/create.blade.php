@extends('angular')
@section('ng','app')
@section('title','P2i - Nuevo Registro de Consumibles y Limpieza')
@section('body')
    @include('p2i.common.header')
    @include('p2i.common.bread',['bread'=>['Limpieza','Nuevo registro']])

    <form class="form-horizontal"  style="margin:20px;"  role="form" method="post" action="{{ url('p2i/limpieza') }}">
        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-1">
                <h3>Registro de Consumibles y Limpieza</h3>
            </div>
        </div>

        <!-- ERROR -->
        @if (Session::has('errors'))
        <div class="form-group">
                <div class="col-sm-4 col-sm-offset-1">
                    <div class="alert alert-warning" role="alert">
                        <ul>
                            <strong>Oops! algo salio mal: </strong>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
        </div>
        @endif
        <!-- FIN -->

        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-1">
                <input type="text" class="form-control" name="ciclo" placeholder="Numero de ciclo" value="{{  Input::old('ciclo')  }}">
            </div>
        </div>

        <div class="form-group" ng-init="secadoMode=1">
            <div class="col-sm-4 col-sm-offset-1">
                <div class="btn-group" >
                    <label class="btn btn-info" ng-model="secadoMode" btn-radio="1">Secador</label>
                    <label class="btn btn-info" ng-model="secadoMode" btn-radio="0">Camara</label>
                    <input type="hidden" name="mode" value="@{{ secadoMode  }}">
                </div>
            </div>
        </div>

        <div class="form-group" ng-show="secadoMode">
            <div class="col-sm-4 col-sm-offset-1">
                <select class="form-control" name="secador">
                    <option value="" selected="selected">- Seleccionar secador -</option>
                    @for ($i = 1; $i <= $cantidad_secadores; $i++)
                        <option value="{{ $i }}">Secador {{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="form-group" ng-hide="secadoMode">
            <div class="col-sm-4 col-sm-offset-1">
                <select class="form-control" name="camara">
                    <option value="" selected="selected">- Seleccionar camara -</option>
                    @for ($i = 1; $i <= $cantidad_camaras; $i++)
                        <option value="{{ $i }}">Camara {{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>

    @foreach([
            ['aspirado_camara','Aspirado de Camara'],
            ['limp_laminas_laterales','Verificacion y Limpieza de Laminas Laterales'],
            ['limp_burlete_puerta','Limpieza de Burlete y Puerta'],
            ['verif_rejilla_monomero','Verificacion de Rejilla y Monomero'],
            ['aspirado_rejillas_laterales','Aspiracion de Rejillas Laterales'],
            ['verif_dummies','Verificacion de estado de dummies'],
            ['limp_jigs','Set de Jigs Limpiados'],
            ['limp_p2i_y_secador','Limpieza externa de Secador y P2i'],
            ['presion_helio','Verifiacion de Presion de Helio'],
        ] as $v)
            @include('p2i.common.checkbox',[
                'name'      => $v[0],
                'name_desc' => $v[1]
            ])
    @endforeach

        {{--
        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-1">
                <textarea class="form-control" rows="4" name="observacion" placeholder="Observaciones"></textarea>
            </div>
        </div>
        --}}
        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-1">
                <input id="submit" name="submit" type="submit" value="Guardar" class="btn btn-primary">
            </div>
        </div>
    </form>

    @include('p2i.common.footer')
@endsection
