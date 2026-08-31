@extends('layouts.admin')
@section('content')
    <div class="row">
        <div id="workingStatus" style="display: block; float: left; width: 50%"></div>
        <div id="requestComplain" style="display: block; float: right; width: 50%"></div>
    </div>
    <div class="row" style="padding-bottom: 5%">
        <div id="workingStatusBar" style="display: block; float: left; width: 50%"></div>
        <div id="requestComplainBar" style="display: block; float: right; width: 50%"></div>
    </div>

@endsection

@section('extrajssection')
<script type="text/javascript">
    //var data_viewer = JSON.parse('');

    Highcharts.chart('workingStatus', {
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45
            }
        },
        title: {
            text: 'Working Status'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        series: [{
            name: 'Brands',
            colorByPoint: true,
            data: [{
                name: 'Chrome',
                y: 60
            }, {
                name: 'Edge',
                y: 40,
                sliced: true,
                // selected: true
            }]
        }]
    });
</script>
<script type="text/javascript">
    Highcharts.chart('requestComplain', {
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45
            }
        },
        title: {
            text: 'Request & Complain Rate'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        series: [{
            name: 'Brands',
            colorByPoint: true,
            data: [{
                name: 'Chrome',
                y: 60
            }, {
                name: 'Edge',
                y: 40,
                sliced: true,
                // selected: true
            }]
        }]
    });
</script>
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
                text: 'Working status',
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
<script type="text/javascript">
    $(function () {
        $('#requestComplainBar').highcharts({
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Number of Request & Complain'
            },
            subtitle: {
                text: 'Source: P.A. Dept. of Education'
            },
            xAxis: {
                categories: ['Young Scholars Charter',
                    'Folk Arts-Cultural Treasures',
                    'Christopher Columbus',
                    'MaST',
                    'Franklin Towne HS',
                    'Laboratory Charter',
                    ],
                title: {
                    text: null
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: '*Renaissance charter school',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            tooltip: {
                valueSuffix: ''
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            /*
    legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -40,
                y: 100,
                floating: true,
                borderWidth: 1,
                backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                shadow: true
            },
    */
            credits: {
                enabled: false
            },
            series: [{
                data: [89.2,
                    88.4,
                    87.7,
                    86.6,
                    86.1,
                    ],
                name: 'School Performance Profile (SPP) Score'
            }]

        });
    });
</script>
@endsection

