<!--
    categorias: array
-->
<?php
    $valores = collect($opt['values']);
    $series = [];
    $top = 0;

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
<div id="{{ $opt['id'] }}piechart" style="width: 95%;height:{{ $opt['height'] }}px;"></div>
<script>
    $(function () {
        var prodchart = null;
        var prodchartoptions = {
            chart: {
                renderTo: '{{ $opt['id'] }}piechart',
                type: 'pie',
                zoomType: 'x'
            },
            credits: {
                enabled: false
            },
            legend: {

                enabled: true
            },
            tooltip: {
                headerFormat: '<b>{series.name}</b><br>',
                pointFormat: 'Total: {point.y}'
            },
            plotOptions: {
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
                }
            },
            title: {
                text: '{{ $opt['titulo'] }}'
            },
            subtitle: {
                text: 'Total: {{ $series[ucfirst($opt['total'])]['total'] }}'
            },
            series: [{
                name: 'Brands',
                colorByPoint: true,
                data: [
                    @foreach($opt['collection']->take($opt['top']) as $key => $value)
                    {
                        name: '{{ $key }}',
                        y: {{ $value->rechazos }}
                    },
                    @endforeach
                ]
            }]
        }

        prodchart = new Highcharts.Chart(prodchartoptions);
    });
</script>
