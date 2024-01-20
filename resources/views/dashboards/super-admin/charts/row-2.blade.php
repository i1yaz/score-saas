@push('page_css')
    <link rel="stylesheet" href="{{ asset('plugins/chart.js/Chart.min.css') }}">
@endpush
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                    <h3 class="card-title">Sessions</h3>
{{--                    <a href="javascript:void(0);">View Report</a>--}}
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex">
                    <p class="d-flex flex-column">
                        <span class="text-bold text-lg">{{$data['sessionsThisWeek']}}</span>
                        <span>Last 30 Days</span>
                    </p>
{{--                    <p class="ml-auto d-flex flex-column text-right">--}}
{{--                        <span class="text-success">--}}
{{--                            <i class="fas fa-arrow-up"></i> 12.5%--}}
{{--                        </span>--}}
{{--                        <span class="text-muted">Since last week</span>--}}
{{--                    </p>--}}
                </div>

                <div class="position-relative mb-4">
                    <div class="chartjs-size-monitor">
                        <div class="chartjs-size-monitor-expand">
                            <div class=""></div>
                        </div>
                        <div class="chartjs-size-monitor-shrink">
                            <div class=""></div>
                        </div>
                    </div>
                    <canvas id="sessions-chart" height="400" style="display: block; height: 200px; width: 708px;"
                        width="1416" class="chartjs-render-monitor"></canvas>
                </div>
                <div class="d-flex flex-row justify-content-end">
{{--                    <span class="mr-2">--}}
{{--                        <i class="fas fa-square text-primary"></i> This Week--}}
{{--                    </span>--}}
{{--                    <span>--}}
{{--                        <i class="fas fa-square text-gray"></i> Last Week--}}
{{--                    </span>--}}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                    <h3 class="card-title">Income</h3>
{{--                    <a href="javascript:void(0);">View Report</a>--}}
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex">
                    <p class="d-flex flex-column">
                        <span class="text-bold text-lg">{{formatAmountWithCurrency($data['thisMonthEarning'])}}</span>
                        <span>Income This Month</span>
                    </p>
{{--                    <p class="ml-auto d-flex flex-column text-right">--}}
{{--                        <span class="text-success">--}}
{{--                            <i class="fas fa-arrow-up"></i> 33.1%--}}
{{--                        </span>--}}
{{--                        <span class="text-muted">Since last month</span>--}}
{{--                    </p>--}}
                </div>

                <div class="position-relative mb-4">
                    <div class="chartjs-size-monitor">
                        <div class="chartjs-size-monitor-expand">
                            <div class=""></div>
                        </div>
                        <div class="chartjs-size-monitor-shrink">
                            <div class=""></div>
                        </div>
                    </div>
                    <canvas id="sales-chart" height="400" style="display: block; height: 200px; width: 708px;"
                        width="1416" class="chartjs-render-monitor"></canvas>
                </div>
{{--                <div class="d-flex flex-row justify-content-end">--}}
{{--                    <span class="mr-2">--}}
{{--                        <i class="fas fa-square text-primary"></i> This year--}}
{{--                    </span>--}}
{{--                    <span>--}}
{{--                        <i class="fas fa-square text-gray"></i> Last year--}}
{{--                    </span>--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
    <script>
        $(function() {
            'use strict'
            var ticksStyle = {
                fontColor: '#495057',
                fontStyle: 'bold'
            }
            var mode = 'index'
            var intersect = true
            var $salesChart = $('#sales-chart')
            var salesChart = new Chart($salesChart, {
                type: 'bar',
                data: {
                    labels: @json($data['twelveMonthsName']),
                    datasets: [{
                        backgroundColor: '#007bff',
                        borderColor: '#007bff',
                        data: @json($data['oneYearEarnings'])
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        mode: mode,
                        intersect: intersect
                    },
                    hover: {
                        mode: mode,
                        intersect: intersect
                    },
                    legend: {
                        display: false
                    },
                    scales: {
                        yAxes: [{
                            gridLines: {
                                display: true,
                                lineWidth: '4px',
                                color: 'rgba(0, 0, 0, .2)',
                                zeroLineColor: 'transparent'
                            },
                            ticks: $.extend({
                                beginAtZero: true,
                                callback: function(value) {
                                    if (value >= 1000) {
                                        value /= 1000
                                        value += 'k'
                                    }
                                    return '$' + value
                                }
                            }, ticksStyle)
                        }],
                        xAxes: [{
                            display: true,
                            gridLines: {
                                display: false
                            },
                            ticks: ticksStyle
                        }]
                    }
                }
            })
            var $sessionsChart = $('#sessions-chart')
            var sessionsChart = new Chart($sessionsChart, {
                data: {
                    labels: @json($data['lastThirtyDays']),
                    datasets: [{
                        type: 'line',
                        data: @json($data['sessionsCountOfThirtyDays']),
                        backgroundColor: 'transparent',
                        borderColor: '#007bff',
                        pointBorderColor: '#007bff',
                        pointBackgroundColor: '#007bff',
                        fill: false
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        mode: mode,
                        intersect: intersect
                    },
                    hover: {
                        mode: mode,
                        intersect: intersect
                    },
                    legend: {
                        display: false
                    },
                    scales: {
                        yAxes: [{
                            gridLines: {
                                display: true,
                                lineWidth: '4px',
                                color: 'rgba(0, 0, 0, .2)',
                                zeroLineColor: 'transparent'
                            },
                            ticks: $.extend({
                                beginAtZero: true,
                                suggestedMax: 200
                            }, ticksStyle)
                        }],
                        xAxes: [{
                            display: true,
                            gridLines: {
                                display: false
                            },
                            ticks: ticksStyle
                        }]
                    }
                }
            })
        })
    </script>
@endpush
