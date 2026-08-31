@extends('layouts.admin')
@section('content')
    <div class="row">
        <div id="workingStatusBar" style="display: block;"></div>
    </div>

@endsection

@section('extrajssection')
    <script>
        Highcharts.setOptions({
            colors: ['#67BCE6'],
            chart: {
                style: {
                    fontFamily: 'sans-serif',
                    color: '#fff'
                }
            }
        });

        $('#workingStatusBar').highcharts({
            chart: {
                type: 'column',
                backgroundColor: '#36394B'
            },
            title: {
                text: 'Web Skills People are Envious Of',
                style: {
                    color: '#fff'
                }
            },
            xAxis: {
                tickWidth: 0,
                labels: {
                    style: {
                        color: '#fff',
                    }
                },
                categories: [
                    'Management',
                    'Marketing',
                    'Law',
                    'Back End',
                    'Front End',
                    'Audio/Video',
                    'Database',
                    'Servers',
                    'Writing',
                    'Design'
                ]
            },
            yAxis: {
                gridLineWidth: .5,
                gridLineDashStyle: 'dash',
                gridLineColor: 'black',
                title: {
                    text: '',
                    style: {
                        color: '#fff'
                    }
                },
                labels: {
                    formatter: function() {
                        return Highcharts.numberFormat(this.value, 0, '', ',');
                    },
                    style: {
                        color: '#fff',
                    }
                }
            },
            legend: {
                enabled: false,
            },
            credits: {
                enabled: false
            },
            tooltip: {
                valuePrefix: ''
            },
            plotOptions: {
                column: {
                    borderRadius: 0,
                    pointPadding: 0,
                    groupPadding: 0.05
                }
            },
            series: [{
                name: 'People',
                data: [
                    690,
                    938,
                    612,
                    4250,
                    2852,
                    1002,
                    728,
                    1156,
                    956,
                    4487
                ]
            }]
        });

    </script>
@endsection

