<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes"/>
    <meta name="MobileOptimized" content="width"/>
    <meta name="HandheldFriendly" content="true"/>
    <title>Trazabilidad de stockers</title>
<style>
        body {

            font-family: "Segoe WP",Tahoma,Geneva,Verdana;
            background-color: #F1F1F1;
            padding: 2px;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family:"Segoe WP Semibold";
            margin-bottom:7px;
        }
</style>

    <!-- Bootstrap -->
    {!! IAStyle('assets/bootstrap/css/bootstrap.css') !!}
    {!! IAStyle('assets/bootstrap/css/bootstrap-theme.min.css') !!}
    <!-- Font Awesome -->
    {!! IAStyle('assets/font-awesome/css/font-awesome.min.css') !!}</head>

<body>

<form method="GET" action="{{ route('aoicollector.stocker.view.pocket') }}">
    <input type="text" name="element" class="form-control" placeholder="Ingresar codigo" value="{{ Input::get('element')  }}"/>
    <button type="submit" class="btn btn-block btn-info"><i class="glyphicon glyphicon-search"></i> Buscar</button>
</form>

    @if(isset($stockerBarcode) && !empty($stockerBarcode))
        @if(isset($find->error))
            <h3>{{ $find->error }}</h3>
        @else
            <div class="row">
                <div class="col-lg-3">
                    <blockquote>
                        <small>Declarado</small>
                        @if($detalle->stocker_declarado)
                            <span class="label label-success">Si</span>
                        @else
                            <span class="label label-danger">Error en declaraciones</span>
                        @endif

                        <small>Stocker ID</small>
                        {{ $find->stocker->barcode }}

                        <small>Linea de produccion</small>
                        {{ $find->linea }}

                        <small>Op</small>
                        {{ $find->stocker->op }}

                        <small>Semielaborado</small>
                        {{ $find->stocker->semielaborado }}

                        <small>Unidades</small>
                        {{ $find->stocker->unidades }}
                    </blockquote>
                </div>

                <div class="col-lg-9">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Panel</th>
                            <th>Declarado</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($detalle->paneles as $item)
                            <?php
                            $panel = $item->panel;
                            ?>
                            @if(isset($panel))
                                <tr>
                                    <td>{{ $panel->panel_barcode }}</td>
                                    <td>

                                        @if(head($item->bloques) == null)
                                            <span class="label label-danger">Sin declarar</span>
                                        @else
                                            @if($item->panel_declarado)
                                                <span class="label label-success">Si</span>
                                            @endif

                                            @if($item->panel_errores)
                                                <span class="label label-info">Con errores</span>
                                            @endif

                                            @if($item->panel_pendiente)
                                                <span class="label label-warning">Pendiente</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endif

</body>
</html>
