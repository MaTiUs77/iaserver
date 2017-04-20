@extends('adminlte/theme')
@section('title', 'Documentacion')
@section('mini',false)
@section('head')
    <style>
        .li_metodos {
            padding-bottom:10px;
            margin-bottom:10px;
            border-bottom: #777 solid 2px;
        }

        kbd {
            font-size:16px;
        }

        .kbd_metodo_static {
            background-color: #22abe8;
        }

        .kbd_metodo_public {
            background-color: #27ad67;
        }

        .kbd_metodo_private {
            background-color: #ec4141;
        }

        abbr.descripcion {
            font-size: 14px;
            display: block;
        }

        div.descripcion {
            font-size: 14px;
            margin-left:30px;
            padding:5px;
        }

        pre.class_descripcion {
            font-size: 16px;
        }

        .margen {
            margin-left:30px;
        }

        .method_content {
            padding-left:50px;
        }

        mark {
            font-size: 14px;
        }

        pre {
            background-color: unset;
            border: unset;
            border-radius: unset;
        }
    </style>
@endsection
@section('menuaside')

    <aside class="main-sidebar">
        <section class="sidebar">
            <ul class="sidebar-menu">
                @foreach($namespaces as $namespace => $files)
                    <li>
                        <a href="#{{ $namespace }}">
                            <span>{{ $namespace }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>
    </aside>

@endsection
@section('body')

            @foreach($namespaces as $namespace => $files)
                <div class="box box-primary box-solid" id="{{ $namespace }}">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ $namespace }}</h3>
                    </div>
                    <div class="box-body">
                        @foreach($files as $file)
                            <?php
                            $classTag  = $file['docblock']->tag;
                            $classDoc  = null;
                            $classExample = null;
                            $classMethod = [];
                            $classProperty = null;

                            $classPropertyList = [];

                            if(isset($file['docblock'])) {
                                $classDoc  = (array) $file['docblock'];

                                if(isset($classDoc['description'])) {
                                    $classDoc['description'] = preg_replace('/{@url (http:.*)}/', '<a href="$1" target="_blank">$1</a>', $classDoc['description']);
                                }

                                if(isset($classDoc['long-description'])) {
                                    $classDoc['long-description'] = preg_replace('/{@url (http:.*)}/', '<a href="$1" target="_blank">$1</a>', $classDoc['long-description']);
                                }
                            }

                            if(isset($file['method'])){
                                $classMethod = $file['method'];

                                if(!is_array($classMethod)) {
                                    $classMethod = array($classMethod);
                                }
                            }

                            if(count($classTag)) {
                                foreach($classTag as $tag) {
                                    $classTagAttr = (array) $tag;
                                    $classTagAttr = $classTagAttr['@attributes'];

                                    if($classTagAttr['name'] == 'example') {
                                        if(isset($classTagAttr['description'])) {
                                            $classExample = $classTagAttr['description'];
                                        }
                                    }
                                }
                            }

                            if(isset($file['property'])) {
                                $classProperty = $file['property'];

                                if(!is_array($classProperty)) {
                                    $classProperty = array($classProperty);
                                }

                                foreach($classProperty as $property) {
                                    $prepare = (array) $property;
                                    $add = [];

                                    $add['name'] = $prepare['name'];
                                    $add['full_name'] = $prepare['full_name'];
                                    $add['default'] = $prepare['default'];
                                    $add['static'] = $prepare['@attributes']['static'];
                                    $add['visibility'] = $prepare['@attributes']['visibility'];
                                    $add['line'] = $prepare['@attributes']['line'];
                                    $addTag = (array) $prepare['docblock']->tag;

                                    if(isset($addTag['@attributes'])) {
                                        if(isset($addTag['@attributes']['type'])) {
                                            $add['type'] = $addTag['@attributes']['type'];
                                        }
                                        $desc = $addTag['@attributes']['description'];

                                        $desc = str_replace('<p>','',$desc);
                                        $desc = str_replace('</p>','',$desc);

                                        $add['description'] = $desc;

                                    }

                                    $classPropertyList[$add['name']] = $add;
                                }
                            }
                            ?>

                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs pull-right">
                                    <li><a href="#{{ str_slug($file['name']) }}_metodos" data-toggle="tab">Metodos</a></li>
                                    @if($classExample)
                                        <li><a href="#{{ str_slug($file['name']) }}_ejemplos" data-toggle="tab">Ejemplos</a></li>
                                    @endif
                                    @if($classProperty)
                                        <li><a href="#{{ str_slug($file['name']) }}_propiedades" data-toggle="tab">Parametros</a></li>
                                    @endif
                                    <li class="active"><a href="#{{ str_slug($file['name']) }}_descripcion" data-toggle="tab">Descripcion</a></li>
                                    <li class="pull-left header"><i class="fa fa-th"></i> {{ $file['name'] }}</li>
                                </ul>
                                <div class="tab-content" style="padding-left:50px;">
                                    <div class="tab-pane active" id="{{ str_slug($file['name']) }}_descripcion">
                                        <code>{{ $file['full_name'] }}</code>
                                        @if(!empty($classDoc['description']))
                                            <pre class="class_descripcion">{!! $classDoc['description']  !!}</pre>
                                        @endif
                                        @if(!empty($classDoc['long-description']))
                                            <pre class="class_descripcion">{!! $classDoc['long-description']  !!}</pre>
                                        @endif
                                    </div>
                                    @if($classProperty)
                                        <div class="tab-pane" id="{{ str_slug($file['name']) }}_propiedades">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Tipo</th>
                                                    <th>Parametro</th>
                                                    <th>Description</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($classPropertyList as $param)
                                                    <tr class="{{ (isset($param['type']) && !empty($param['type'])) ? '' : 'danger'  }}">
                                                        <td>
                                                            @if(isset($param['type']) && !empty($param['type']))
                                                                <label class="label label-default">
                                                                    {{ str_replace('\\IAServer\\Http\\Controllers\\','',$param['type']) }}
                                                                </label>
                                                            @else
                                                                <label class="label label-danger">
                                                                    undefined
                                                                </label>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <code>{{ $param['name']  }}</code>
                                                            @if(isset($param['default']))
                                                                @if($param['default'] == '""')
                                                                    <label class="label label-primary">Opcional</label>
                                                                @else
                                                                    {{ $param['default']  }}
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(isset($param['description']))
                                                                {{ $param['description']  }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif

                                @if($classExample)
                                        <div class="tab-pane" id="{{ str_slug($file['name']) }}_ejemplos">
                                            <pre>{!! $classExample !!}</pre>
                                        </div>
                                    @endif
                                    <div class="tab-pane" id="{{ str_slug($file['name']) }}_metodos">
                                        @if(isset($file['method']))
                                            <ul class="list-unstyled">
                                                @foreach($classMethod as $method)
                                                    <?php
                                                    $method  = (array) $method;
                                                    $doc  = null;
                                                    $argument = null;

                                                    if(isset($method['docblock'])) {
                                                        $doc  = (array) $method['docblock'];

                                                        if(isset($doc['description'])) {
                                                            $doc['description'] = preg_replace('/{@url (http:.*)}/', '<a href="$1" target="_blank">$1</a>', $doc['description']);
                                                        }

                                                        if(isset($doc['long-description'])) {
                                                            $doc['long-description'] = preg_replace('/{@url (http:.*)}/', '<a href="$1" target="_blank">$1</a>', $doc['long-description']);
                                                        }
                                                    }

                                                    if(isset($method['argument'])) {
                                                        $argument  = $method['argument'];
                                                    }
                                                    ?>

                                                    @if(isset($method['name']))
                                                        <li class="li_metodos">
                                                            <!-- Parametros del metodo -->
                                                            <?php
                                                            $parametros = [];
                                                            $ejemplo = null;
                                                            $return = null;

                                                            // Crea una lista de los argumentos del metodo
                                                            if(is_array($argument)) {
                                                                foreach($argument as $arg) {
                                                                    $arg = (array) $arg;

                                                                    if(isset($arg['name'])) {
                                                                        $parametros[$arg['name']] = $arg;
                                                                    }
                                                                }
                                                            } else {
                                                                if($argument) {
                                                                    $argument = (array) $argument;
                                                                    $parametros[$argument['name']] = $argument;
                                                                }
                                                            }

                                                            // Agrega la documentacion de los argumentos
                                                            if(isset($method['docblock']->tag)) {
                                                                foreach($method['docblock']->tag as $tag) {
                                                                    $methodAttr = (array) $tag;
                                                                    $methodAttr = $methodAttr['@attributes'];

                                                                    if(isset($methodAttr['variable']) && $methodAttr['name'] == 'param') {

                                                                        if(isset($methodAttr['description'])) {
                                                                            $desc = str_replace('<p>','',$methodAttr['description']);
                                                                            $desc = str_replace('</p>','',$desc);

                                                                            $parametros[$methodAttr['variable']]['description'] = $desc;
                                                                        }

                                                                        if(isset($methodAttr['line'])) {
                                                                            $parametros[$methodAttr['variable']]['line'] = $methodAttr['line'];
                                                                        }
                                                                    }

                                                                    if($methodAttr['name'] == 'return') {
                                                                        $return = $methodAttr;
                                                                    }

                                                                    if($methodAttr['name'] == 'example') {
                                                                        if(isset($methodAttr['description'])) {
                                                                            $ejemplo = $methodAttr['description'];
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            ?>

                                                            <!-- Nombre del metodo y modo de acceso -->
                                                            @if(isset($method['@attributes']['visibility']))
                                                                @if(isset($method['@attributes']['static']) && $method['@attributes']['static'] == 'true')
                                                                    <kbd class="kbd_metodo_static">{{ $method['name'] }}</kbd>
                                                                @else
                                                                    <kbd class="kbd_metodo_{{ $method['@attributes']['visibility'] }}">{{ $method['name'] }}</kbd>
                                                                @endif
                                                            @else
                                                                <kbd>{{ $method['name'] }}</kbd>
                                                            @endif
                                                            <div class="method_content">
                                                                <!-- Descripcion del metodo -->
                                                                @if(!empty($doc['description']))
                                                                    <div class="well well-sm">
                                                                        <div class="descripcion">{!! $doc['description'] !!} </div>
                                                                        @if(!empty($doc['long-description']))
                                                                            <div class="descripcion">{!! $doc['long-description']  !!} </div>
                                                                        @endif
                                                                    </div>
                                                                @endif

                                                                @if($parametros)
                                                                    <abbr class="descripcion" title="attribute">Parametros</abbr>
                                                                    <table class="table table-bordered table-striped">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>Tipo</th>
                                                                            <th>Parametro</th>
                                                                            <th>Description</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        @foreach($parametros as $paramName => $param)
                                                                            <tr class="{{ (isset($param['type']) && !empty($param['type'])) ? '' : 'danger'  }}">
                                                                                <td>
                                                                                    @if(isset($param['type']) && !empty($param['type']))
                                                                                        <label class="label label-default">
                                                                                            {{ str_replace('\\IAServer\\Http\\Controllers\\','',$param['type']) }}
                                                                                        </label>
                                                                                    @else
                                                                                        <label class="label label-danger">
                                                                                            undefined
                                                                                        </label>
                                                                                    @endif
                                                                                </td>
                                                                                <td>
                                                                                    <code>{{ $paramName  }}</code>
                                                                                    @if(isset($param['default']))
                                                                                        @if($param['default'] == '""')
                                                                                            <label class="label label-primary">Opcional</label>
                                                                                        @else
                                                                                            {{ $param['default']  }}
                                                                                        @endif
                                                                                    @endif
                                                                                </td>
                                                                                <td>
                                                                                    @if(isset($param['description']))
                                                                                        {{ $param['description']  }}
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                @endif

                                                                @if($ejemplo)
                                                                    <abbr class="descripcion" title="attribute">Ejemplo</abbr>
                                                                    <pre>{!! $ejemplo !!}</pre>
                                                                @endif

                                                                @if($return)
                                                                    <abbr class="descripcion" title="attribute">Respuesta
                                                                        @if(isset($return['type']))
                                                                            <label class="label label-default">
                                                                                {{ $return['type'] }}
                                                                            </label>
                                                                        @endif
                                                                    </abbr>
                                                                    @if(isset($return['description']))
                                                                        <?php
                                                                        $json = json_decode($return['description']);
                                                                        ?>
                                                                        @if($json)
                                                                            <pre><php>{!!  json_encode($json, JSON_PRETTY_PRINT) !!}</php></pre>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        @endforeach
                    </div>
                </div>
            @endforeach


{!! IAScript('assets/highlight/highlight.pack.js') !!}

<script>
    $(document).ready(function() {
        $('pre php').each(function(i, block) {
            hljs.highlightBlock(block);
        });
    });
</script>
@endsection
