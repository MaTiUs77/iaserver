@extends('adminlte/theme')
@section('ng','app')
@section('title','Aoicollector - Route OP')
@section('body')

    <div class="container">
        <form class="form-horizontal" role="form" method="post" action="{{ url('aoicollector/prod/routeop') }}">
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-1">
                    <h3>Configurar: {{  ($op!="") ? $op : Input::old('op') }}</h3>
                </div>
            </div>

            <!-- will be used to show any messages -->
            @if (Session::has('message'))
                <div class="form-group" id="message">
                    <div class="col-sm-4 col-sm-offset-1">
                        <div class="alert alert-info">{{ Session::get('message') }}</div>
                    </div>
                </div>
            @endif

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
                        <input type="text" class="form-control" name="puesto" placeholder="Puesto ej: SMT" value="{{  Input::old('puesto')  }}">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-1">
                        <input type="text" class="form-control" name="regex" placeholder="Expresion Regular" value="{{  Input::old('regex')  }}">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-1">
                        <input type="number" class="form-control" name="qty_etiquetas" placeholder="Cantidad de etiquetas" value="{{  Input::old('qty_etiquetas')  }}">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-1">
                        <input type="number" class="form-control" name="qty_bloques" placeholder="Cantidad de bloques" value="{{  Input::old('qty_bloques')  }}">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-1">
                        <input type="hidden" name="op" value="{{  ($op!="") ? $op : Input::old('op') }}">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-1">
                        <input type="checkbox" name="declare" />
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-1">
                        <input id="submit" name="submit" type="submit" value="Guardar configuracion" class="btn btn-block btn-primary">
                    </div>
                </div>

        </form>


    </div>



    @include('aoicollector.prod.partial.footer')

    <script type="text/javascript" src="http://www.jqueryscript.net/demo/Simple-Toggle-Switch-Plugin-With-jQuery-Bootstrap-Bootstrap-Switch/bootstrap-switch.js"></script>

    <script>
        $("[name='declare']").bootstrapSwitch({
            on: 'Declara',
            off: 'No declara',
            onClass: 'success',
            offClass: 'warning'
        });
    </script>
@endsection
