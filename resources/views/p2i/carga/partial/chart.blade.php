<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
{!! IAScript('assets/highchart/js/highcharts.js') !!}

<script>
    $(function () {
        new Highcharts.Chart({
            chart: {
                renderTo: 'container',
                type: 'column'
            },
            credits: {
                enabled: false
            },
            legend: {
                enabled: false
            },
            title: {
                text: 'Monthly Average Rainfall'
            },
            subtitle: {
                text: 'Source: WorldClimate.com'
            },
            xAxis: {
                categories: [
                    '13-09',
                    '14-09',
                    '15-09'
                ],
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Rainfall (mm)'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: '1001',
                data: [8, null, null, 10]

            }, {
                name: '1005',
                data: [5, null]
            }]
        });
    });
</script>
