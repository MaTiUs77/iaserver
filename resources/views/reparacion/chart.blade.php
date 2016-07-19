<!--
    categorias: array
-->
<?php
    $valores = collect($opt['values']);
    $series = [];

    foreach($opt['collection'] as $collectionKey => $collectionItem)
    {
        foreach($valores as $propertyFind)
        {
            $property = $propertyFind;

            if(is_array($propertyFind))
            {
                $property = key($propertyFind);
            }

            if(property_exists($collectionItem,$property))
            {
                $series[ucfirst($property)]['values'][] = $collectionItem->{$property};

                // Suma el total de la propiedad
                if(!isset($series[ucfirst($property)]['total']))
                {
                    $series[ucfirst($property)]['total'] = $collectionItem->{$property};
                } else
                {
                    $series[ucfirst($property)]['total'] += $collectionItem->{$property};
                }

                // Sirve para agregar propiedades javascript a la serie
                if(is_array($propertyFind))
                {
                    foreach($propertyFind as $optionValue)
                    {
                        foreach($optionValue as $optionValueKey => $optionValueValue)
                        {
                            $series[ucfirst($property)]['options'][$optionValueKey] = $optionValueValue;
                        }
                    }
                }
            }
        }
    }
?>

<div class="panel panel-default">
    <div class="panel-body">
        <button class="pull-right btn btn-xs btn-default" onClick="{{ $opt['id'] }}expandChart($(this));" style="margin-left: 5px;"><span class="fa fa-expand"></span></button>
        <button class="pull-right btn btn-xs btn-default" style="margin-left: 5px;" onClick="{{ $opt['id'] }}drawChart('column');" ng-click="{{ $opt['id'] }}ChartPieToggle=false;" ng-class="{active : !{{ $opt['id'] }}ChartPieToggle }"><span class="fa fa-bar-chart"></span></button>

        <div class="btn-group pull-right" style="margin-left: 5px;">
            <a class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" href="#" ng-class="{active : {{ $opt['id'] }}ChartPieToggle }">
                <span class="fa fa-pie-chart"></span>
            </a>
            <ul class="dropdown-menu">
                @foreach($series as $serie => $v)
                    @if(collect($v['values'])->take($opt['top'])->sum() > 0 )
                        <li>
                            <a href="javascript:{{ $opt['id'] }}drawChart('pie','{{ strtolower($serie) }}');" ng-click="{{ $opt['id'] }}ChartPieToggle=true;">{{ $serie }}</a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>

        <div id="{{ $opt['id'] }}container" style="width: 95%;height:{{ $opt['height'] }}px;"></div>
    </div>
</div>

<script>
    function {{ $opt['id'] }}expandChart(el)
    {
        var parent = el.offsetParent();
        console.log(parent);
        if(parent.hasClass( "col-xs-6" ))
        {
            parent.removeClass( "col-xs-6" );
            parent.addClass( "col-xs-12" );
        } else
        {
            if(parent.hasClass( "col-xs-12" )) {
                parent.removeClass("col-xs-12");
                parent.addClass("col-xs-6");
            }
        }

        {{ $opt['id'] }}drawChart('column');
    }

    function {{ $opt['id'] }}drawChart(chartMode,serie)
    {
        if(chartMode==undefined)
        {
            chartMode = 'column';
        }
        var chart = null;

        var pieData = {
            @foreach($series as $serie => $v)
                '{{ strtolower($serie) }}' : [
                    @foreach($opt['collection']->take($opt['top']) as $key => $value)
                        {name: '{{ $key }}', y: {!!  $value->{strtolower($serie)} !!}  },
                    @endforeach
                ],
            @endforeach
        };

        var chartPieSeries = [{
            name: 'Torta',
            type: 'pie',
            colorByPoint: true,
            data: pieData[serie]
        }];

        var chartColumnSeries = [
            @foreach($series as $serie => $v)
                {
                    name: '{{ $serie }}',
                    @foreach($v['options'] as $serieOptionKey => $serieOptionValue )
                        {{ $serieOptionKey }}: {!! (($serieOptionValue) ? "'".$serieOptionValue."'" : 'false') !!}  ,
                    @endforeach

                    @if(isset($opt['top']))
                        data: [{!! collect($v['values'])->take($opt['top'])->implode(",") !!}]
                    @else
                        data: [{{ join(',',$v['values']) }}]
                    @endif
                },
            @endforeach
        ];

        var useChartMode = {
            'pie' : chartPieSeries,
            'column' : chartColumnSeries
        }

        var chartOptions = {
            chart: {
                renderTo: '{{ $opt['id'] }}container',
                type: 'column',
                zoomType: 'x'
            },
            credits: {
                enabled: false
            },
            legend: {
                enabled: true
            },
            xAxis: {
                categories: [
                    @if($opt['collection'] instanceof \Illuminate\Support\Collection )
                            @if(isset($opt['top']))
                            '{!!  $opt['collection']->keys()->take($opt['top'])->implode("','") !!}'
                    @else
                            '{!!  $opt['collection']->keys()->implode("','") !!}'
                    @endif
                    @endif
                ],
                labels: {
                    style: {
                        color: 'black',
                        fontSize: '14px'
                    },
                    formatter: function() {
                        return '<a href="javascript:;">'+ this.value +'</a>';
                    }
                }
            },
            yAxis: {
                title: {
                    text: 'Total',
                    style: {
                        color: '#828282',
                        fontWeight: 'bold',
                        font: 'bold 18px "Trebuchet MS", Verdana, sans-serif'
                    }
                }
            },
            tooltip: {
                headerFormat: '<b>{series.name}</b><br>',
                pointFormat: 'Total: {point.y}'
            },
            plotOptions: {
                series: {
                    dataLabels: {
                        enabled: true,
                        borderRadius: 5,
                        backgroundColor: 'rgba(252, 255, 197, 0.7)',
                        borderWidth: 1,
                        borderColor: '#AAA',
                        y: -6,
                        formatter:function(){
                            if(this.y > 0)
                                return this.y;
                        }
                    }
                },
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',

                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                },
                column: {
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'black'
                        ,style: {
                            fontSize: '16px',
                            fontFamily: 'Verdana, sans-serif'
                        },
                        formatter: function() {
                            if (this.y != 0) {
                                return this.y;
                            } else {
                                return '';
                            }
                        }
                    },
                    enableMouseTracking: true
                }
            },
            title: {
                text: '{{ $opt['titulo'] }}'
            },
            subtitle: {
                text: 'Total: {{ $series[ucfirst($opt['total'])]['total'] }}'
            },
            series: useChartMode[chartMode]
        };

        chart = new Highcharts.Chart(chartOptions);
    }

    $(function () {
        {{ $opt['id'] }}drawChart('column');
    });
</script>

