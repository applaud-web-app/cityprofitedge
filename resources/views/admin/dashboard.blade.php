@extends('admin.layouts.app')

@section('panel')
    @if (@json_decode($general->system_info)->version > systemDetails()['version'])
        <div class="row">
            <div class="col-md-12">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-header">
                        <h3 class="card-title"> @lang('New Version Available') <button class="btn btn--dark float-end">@lang('Version') {{ json_decode($general->system_info)->version }}</button> </h3>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-dark">@lang('What is the Update?')</h5>
                        <p>
                            <pre class="f-size--24">{{ json_decode($general->system_info)->details }}</pre>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if(@json_decode($general->system_info)->message)
        <div class="row">
            @foreach(json_decode($general->system_info)->message as $msg)
                <div class="col-md-12">
                    <div class="alert border border--primary" role="alert">
                        <div class="alert__icon bg--primary">
                            <i class="far fa-bell"></i>
                            <p class="alert__message">@php echo $msg; @endphp</p>
                            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="row gy-4">
        <div class="col-lg-4 col-md-6 col-12">
            <div class="card bg--primary overflow-hidden box--shadow2">
                <div class="card-body">
                    <div class="d-widget has--link">
                        {{-- <a href="{{ route('user.transactions') }}" class="item--link"></a>
                        <div class="d-widget__icon text-center">
                            <i class="las la-users f-size--56"></i>
                        </div> --}}
                        <div class="d-widget__content">
                            <h4 class="d-widget__caption text-center text--white">@lang('Invested Amount')</h4>
                            <div class="row">
                                <div class="col-xl-6 text-center col-lg-6 col-md-6">
                                    <p class="d-widget__caption fs--12px text--white">@lang('Invested Value')</p>
                                    <h6 class="d-widget__amount mt-1 text--white">
                                        {{ $general->cur_sym }} {{ showAmount($totalInvestedAmount, 2) }}
                                    </h6>
                                </div>
                                <div class="col-xl-6 text-center col-lg-6 col-md-6">
                                    <p class="d-widget__caption fs--12px text--white">@lang('Current Value')</p>
                                    <h6 class="d-widget__amount mt-1 text--white">
                                        {{ $general->cur_sym }} {{ showAmount($totalCurrentAmount, 2) }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div><!-- d-widget end -->
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-12">
            <div class="card bg--success overflow-hidden box--shadow2">
                <div class="card-body">
                    <div class="d-widget has--link">
                        {{-- <a href="{{ route('user.transactions') }}" class="item--link"></a>
                        <div class="d-widget__icon text-center">
                            <i class="las la-users f-size--56"></i>
                        </div> --}}
                        <div class="d-widget__content">
                            <h4 class="d-widget__caption text-center text--white">@lang('Stock Portfolio')</h4>
                            <div class="row">
                                <div class="col-xl-6 text-center col-lg-6 col-md-6">
                                    <p class="d-widget__caption fs--12px text--white">@lang('Invested Value')</p>
                                    <h6 class="d-widget__amount mt-1 text--white">
                                        {{ $general->cur_sym }} {{ showAmount($stockPortFolio->buy_value,2) }}
                                    </h6>
                                </div>
                                <div class="col-xl-6 text-center col-lg-6 col-md-6">
                                    <p class="d-widget__caption fs--12px text--white">@lang('Current Value')</p>
                                    <h6 class="d-widget__amount mt-1 text--white">
                                        {{ $general->cur_sym }} {{ showAmount($stockPortFolio->current_value,2) }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div><!-- d-widget end -->
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-12">
            <div class="card bg--danger overflow-hidden box--shadow2">
                <div class="card-body">
                    <div class="d-widget has--link">
                        {{-- <a href="{{ route('user.transactions') }}" class="item--link"></a>
                        <div class="d-widget__icon text-center">
                            <i class="las la-users f-size--56"></i>
                        </div> --}}
                        <div class="d-widget__content">
                            <h4 class="d-widget__caption text-center text--white">@lang('Thematic Portfolio')</h4>
                            <div class="row">
                                <div class="col-xl-6 text-center col-lg-6 col-md-6">
                                    <p class="d-widget__caption fs--12px text--white">@lang('Invested Value')</p>
                                    <h6 class="d-widget__amount mt-1 text--white">
                                        {{ $general->cur_sym }} {{ showAmount($user, 2) }}
                                    </h6>
                                </div>
                                <div class="col-xl-6 text-center col-lg-6 col-md-6">
                                    <p class="d-widget__caption fs--12px text--white">@lang('Current Value')</p>
                                    <h6 class="d-widget__amount mt-1 text--white">
                                        {{ $general->cur_sym }} {{ showAmount($user, 2) }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div><!-- d-widget end -->
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-12">
            <div class="card bg--warning overflow-hidden box--shadow2">
                <div class="card-body">
                    <div class="d-widget has--link">
                        {{-- <a href="{{ route('user.transactions') }}" class="item--link"></a>
                        <div class="d-widget__icon text-center">
                            <i class="las la-users f-size--56"></i>
                        </div> --}}
                        <div class="d-widget__content">
                            <h4 class="d-widget__caption text-center text--white">@lang('Global Stock Portfolio')</h4>
                            <div class="row">
                                <div class="col-xl-6 text-center col-lg-6 col-md-6">
                                    <p class="d-widget__caption fs--12px text--white">@lang('Invested Value')</p>
                                    <h6 class="d-widget__amount mt-1 text--white">
                                        {{ $general->cur_sym }} {{ showAmount($globalStockPortFolio->buy_value,2) }}
                                    </h6>
                                </div>
                                <div class="col-xl-6 text-center col-lg-6 col-md-6">
                                    <p class="d-widget__caption fs--12px text--white">@lang('Current Value')</p>
                                    <h6 class="d-widget__amount mt-1 text--white">
                                        {{ $general->cur_sym }} {{ showAmount($globalStockPortFolio->current_value,2) }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div><!-- d-widget end -->
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-12">
            <div class="card bg--info overflow-hidden box--shadow2">
                <div class="card-body">
                    <div class="d-widget has--link">
                        {{-- <a href="{{ route('user.transactions') }}" class="item--link"></a>
                        <div class="d-widget__icon text-center">
                            <i class="las la-users f-size--56"></i>
                        </div> --}}
                        <div class="d-widget__content">
                            <h4 class="d-widget__caption text-center text--white">@lang('F&O Portfolio-Hedging')</h4>
                            <div class="row">
                                <div class="col-xl-6 text-center col-lg-6 col-md-6">
                                    <p class="d-widget__caption fs--12px text--white">@lang('Invested Value')</p>
                                    <h6 class="d-widget__amount mt-1 text--white">
                                        {{ $general->cur_sym }} {{ showAmount($foglobalStockPortFolio->buy_value,2) }}
                                    </h6>
                                </div>
                                <div class="col-xl-6 text-center col-lg-6 col-md-6">
                                    <p class="d-widget__caption fs--12px text--white">@lang('Current Value')</p>
                                    <h6 class="d-widget__amount mt-1 text--white">
                                        {{ $general->cur_sym }} {{ showAmount($foglobalStockPortFolio->current_value,2) }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div><!-- d-widget end -->
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-12">
            <div class="card bg--primary overflow-hidden box--shadow2">
                <div class="card-body">
                    <div class="d-widget has--link">
                        {{-- <a href="{{ route('user.transactions') }}" class="item--link"></a>
                        <div class="d-widget__icon text-center">
                            <i class="las la-users f-size--56"></i>
                        </div> --}}
                        <div class="d-widget__content">
                            <h4 class="d-widget__caption text-center text--white">@lang('Metals Portfolio (Gold & Silver)')</h4>
                            <div class="row">
                                <div class="col-xl-6 text-center col-lg-6 col-md-6">
                                    <p class="d-widget__caption fs--12px text--white">@lang('Invested Value')</p>
                                    <h6 class="d-widget__amount mt-1 text--white">
                                        {{ $general->cur_sym }} {{ showAmount($metalsPortFolio->buy_value,2) }}
                                    </h6>
                                </div>
                                <div class="col-xl-6 text-center col-lg-6 col-md-6">
                                    <p class="d-widget__caption fs--12px text--white">@lang('Current Value')</p>
                                    <h6 class="d-widget__amount mt-1 text--white">
                                        {{ $general->cur_sym }} {{ showAmount($metalsPortFolio->current_value,2) }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div><!-- d-widget end -->
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-12">
            <div class="card bg--danger overflow-hidden box--shadow2">
                <div class="card-body">
                    <div class="d-widget has--link">
                        {{-- <a href="{{ route('user.transactions') }}" class="item--link"></a>
                        <div class="d-widget__icon text-center">
                            <i class="las la-users f-size--56"></i>
                        </div> --}}
                        <div class="d-widget__content">
                            <h4 class="d-widget__caption text-center text--white">@lang('Invested in All Portfolios')</h4>
                            <div class="row">
                                <div class="col-xl-6 text-center col-lg-6 col-md-6">
                                    <p class="d-widget__caption fs--12px text--white">@lang('Invested in All Portfolios')</p>
                                    <h6 class="d-widget__amount mt-1 text--white">
                                        {{ $general->cur_sym }} {{ showAmount($user, 2) }}
                                    </h6>
                                </div>
                                <div class="col-xl-6 text-center col-lg-6 col-md-6">
                                    <p class="d-widget__caption fs--12px text--white">@lang('Current Value')</p>
                                    <h6 class="d-widget__amount mt-1 text--white">
                                        {{ $general->cur_sym }} {{ showAmount($user, 2) }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div><!-- d-widget end -->
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-12">
            <div class="card bg--success overflow-hidden box--shadow2">
                <div class="card-body">
                    <div class="d-widget has--link">
                        {{-- <a href="{{ route('user.transactions') }}" class="item--link"></a>
                        <div class="d-widget__icon text-center">
                            <i class="las la-users f-size--56"></i>
                        </div> --}}
                        <div class="d-widget__content">
                            <h4 class="d-widget__caption text-center text--white">@lang('All Portfolios Current Value')</h4>
                            <div class="row">
                                <div class="col-xl-6 text-center col-lg-6 col-md-6">
                                    <p class="d-widget__caption fs--12px text--white">@lang('Invested in All Portfolios')</p>
                                    <h6 class="d-widget__amount mt-1 text--white">
                                        {{ $general->cur_sym }} {{ showAmount($user, 2) }}
                                    </h6>
                                </div>
                                <div class="col-xl-6 text-center col-lg-6 col-md-6">
                                    <p class="d-widget__caption fs--12px text--white">@lang('Current Value')</p>
                                    <h6 class="d-widget__amount mt-1 text--white">
                                        {{ $general->cur_sym }} {{ showAmount($user, 2) }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div><!-- d-widget end -->
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-12">
            <div class="card bg--warning overflow-hidden box--shadow2">
                <div class="card-body">
                    <div class="d-widget has--link">
                        {{-- <a href="{{ route('user.transactions') }}" class="item--link"></a>
                        <div class="d-widget__icon text-center">
                            <i class="las la-users f-size--56"></i>
                        </div> --}}
                        <div class="d-widget__content">
                            <h4 class="d-widget__caption text-center text--white">@lang('Users Networth')</h4>
                            <div class="row">
                                <div class="col-xl-6 text-center col-lg-6 col-md-6">
                                    <p class="d-widget__caption fs--12px text--white">@lang('Invested in All Portfolios')</p>
                                    <h6 class="d-widget__amount mt-1 text--white">
                                        {{ $general->cur_sym }} {{ showAmount($user, 2) }}
                                    </h6>
                                </div>
                                <div class="col-xl-6 text-center col-lg-6 col-md-6">
                                    <p class="d-widget__caption fs--12px text--white">@lang('Current Value')</p>
                                    <h6 class="d-widget__amount mt-1 text--white">
                                        {{ $general->cur_sym }} {{ showAmount($user, 2) }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div><!-- d-widget end -->
                </div>
            </div>
        </div>
    </div>

    <div class="row gy-4 mt-2">
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                link="{{route('admin.users.all')}}"
                icon="las la-users f-size--56"
                title="Total Users"
                value="{{$widget['total_users']}}"
                bg="primary"
            />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                link="{{route('admin.users.active')}}"
                icon="las la-user-check f-size--56"
                title="Active Users"
                value="{{$widget['verified_users']}}"
                bg="success"
            />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                link="{{route('admin.users.email.unverified')}}"
                icon="lar la-envelope f-size--56"
                title="Email Unverified Users"
                value="{{$widget['email_unverified_users']}}"
                bg="danger"
            />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                link="{{route('admin.users.mobile.unverified')}}"
                icon="las la-comment-slash f-size--56"
                title="Mobile Unverified Users"
                value="{{$widget['mobile_unverified_users']}}"
                bg="red"
            />
        </div>
    </div><!-- row end-->

    {{-- <div class="row gy-4 mt-2">
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                style="2"
                link="{{route('admin.deposit.list')}}"
                icon="fas fa-hand-holding-usd"
                icon_style="false"
                title="Total Deposited"
                value="{{ $general->cur_sym }}{{showAmount($deposit['total_deposit_amount'])}}"
                color="success"
            />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                style="2"
                link="{{route('admin.deposit.pending')}}"
                icon="fas fa-spinner"
                icon_style="false"
                title="Pending Deposits"
                value="{{$deposit['total_deposit_pending']}}"
                color="warning"
            />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                style="2"
                link="{{route('admin.deposit.rejected')}}"
                icon="fas fa-ban"
                icon_style="false"
                title="Rejected Deposits"
                value="{{$deposit['total_deposit_rejected']}}"
                color="danger"
            />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                style="2"
                link="{{route('admin.deposit.list')}}"
                icon="fas fa-percentage"
                icon_style="false"
                title="Deposited Charge"
                value="{{ $general->cur_sym }}{{showAmount($deposit['total_deposit_charge'])}}"
                color="primary"
            />
        </div>
    </div>--}}

    {{-- <div class="row gy-4 mt-2">
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                style="2"
                link="{{route('admin.signal.all')}}"
                icon="las la-signal"
                icon_style="outline"
                title="Total Signals"
                value="{{ @$signalStatistics['total'] }}"
                color="primary"
            />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                style="2"
                link="{{ route('admin.signal.sent') }}"
                icon="las la-paper-plane"
                icon_style="outline"
                title="Sent Signals"
                value="{{ @$signalStatistics['sent'] }}"
                color="success"
            />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                style="2"
                link="{{ route('admin.signal.not.send') }}"
                icon="las la-spinner"
                icon_style="outline"
                title="Not Sent Signals"
                value="{{ @$signalStatistics['notSent'] }}"
                color="warning"
            />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                style="2"
                link="{{ route('admin.package.all') }}"
                icon="las la-box" 
                icon_style="outline"
                title="Total Products"
                value="{{ $totalPackage }}"
                color="primary"
            />
        </div>
    </div>--}}

    <div class="row mb-none-30 mt-30">
        <div class="col-xl-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Monthly Deposit Report') (@lang('Last 12 Month'))</h5>
                    <div id="apex-bar-chart"> </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Transactions Report') (@lang('Last 30 Days'))</h5>
                    <div id="apex-line"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-none-30 mt-5">
        <div class="col-xl-4 col-lg-6 mb-30">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <h5 class="card-title">@lang('Login By Browser') (@lang('Last 30 days'))</h5>
                    <canvas id="userBrowserChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Login By OS') (@lang('Last 30 days'))</h5>
                    <canvas id="userOsChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Login By Country') (@lang('Last 30 days'))</h5>
                    <canvas id="userCountryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    @include('admin.partials.cron_modal')
@endsection

@push('breadcrumb-plugins')
    <button class="btn btn-outline--primary" data-bs-toggle="modal" data-bs-target="#cronModal">
        <i class="las la-server"></i>@lang('Cron Setup')
    </button>
@endpush

@push('script')
    <script src="{{ asset('assets/admin/js/vendor/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/chart.js.2.8.0.js') }}"></script>

    <script>
        "use strict";

        $(document).ready(function() {
            if (@json($showCronModal)) {
                $('#cronModal').modal('show');
            }
        });

        var options = {
            series: [{
                name: 'Total Deposit',
                data: [
                    @foreach ($months as $month)
                        {{ getAmount(@$depositsMonth->where('months', $month)->first()->depositAmount) }},
                    @endforeach
                ]
            }],
            chart: {
                type: 'bar',
                height: 450,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '50%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: @json($months),
            },
            yaxis: {
                title: {
                    text: "{{ __($general->cur_sym) }}",
                    style: {
                        color: '#7c97bb'
                    }
                }
            },
            grid: {
                xaxis: {
                    lines: {
                        show: false
                    }
                },
                yaxis: {
                    lines: {
                        show: false
                    }
                },
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return "{{ __($general->cur_sym) }}" + val + " "
                    }
                }
            }
        };
        var chart = new ApexCharts(document.querySelector("#apex-bar-chart"), options);
        chart.render();



        var ctx = document.getElementById('userBrowserChart');
        var myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($chart['user_browser_counter']->keys()),
                datasets: [{
                    data: {{ $chart['user_browser_counter']->flatten() }},
                    backgroundColor: [
                        '#ff7675',
                        '#6c5ce7',
                        '#ffa62b',
                        '#ffeaa7',
                        '#D980FA',
                        '#fccbcb',
                        '#45aaf2',
                        '#05dfd7',
                        '#FF00F6',
                        '#1e90ff',
                        '#2ed573',
                        '#eccc68',
                        '#ff5200',
                        '#cd84f1',
                        '#7efff5',
                        '#7158e2',
                        '#fff200',
                        '#ff9ff3',
                        '#08ffc8',
                        '#3742fa',
                        '#1089ff',
                        '#70FF61',
                        '#bf9fee',
                        '#574b90'
                    ],
                    borderColor: [
                        'rgba(231, 80, 90, 0.75)'
                    ],
                    borderWidth: 0,

                }]
            },
            options: {
                aspectRatio: 1,
                responsive: true,
                maintainAspectRatio: true,
                elements: {
                    line: {
                        tension: 0 // disables bezier curves
                    }
                },
                scales: {
                    xAxes: [{
                        display: false
                    }],
                    yAxes: [{
                        display: false
                    }]
                },
                legend: {
                    display: false,
                }
            }
        });



        var ctx = document.getElementById('userOsChart');
        var myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($chart['user_os_counter']->keys()),
                datasets: [{
                    data: {{ $chart['user_os_counter']->flatten() }},
                    backgroundColor: [
                        '#ff7675',
                        '#6c5ce7',
                        '#ffa62b',
                        '#ffeaa7',
                        '#D980FA',
                        '#fccbcb',
                        '#45aaf2',
                        '#05dfd7',
                        '#FF00F6',
                        '#1e90ff',
                        '#2ed573',
                        '#eccc68',
                        '#ff5200',
                        '#cd84f1',
                        '#7efff5',
                        '#7158e2',
                        '#fff200',
                        '#ff9ff3',
                        '#08ffc8',
                        '#3742fa',
                        '#1089ff',
                        '#70FF61',
                        '#bf9fee',
                        '#574b90'
                    ],
                    borderColor: [
                        'rgba(0, 0, 0, 0.05)'
                    ],
                    borderWidth: 0,

                }]
            },
            options: {
                aspectRatio: 1,
                responsive: true,
                elements: {
                    line: {
                        tension: 0 // disables bezier curves
                    }
                },
                scales: {
                    xAxes: [{
                        display: false
                    }],
                    yAxes: [{
                        display: false
                    }]
                },
                legend: {
                    display: false,
                }
            },
        });


        // Donut chart
        var ctx = document.getElementById('userCountryChart');
        var myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($chart['user_country_counter']->keys()),
                datasets: [{
                    data: {{ $chart['user_country_counter']->flatten() }},
                    backgroundColor: [
                        '#ff7675',
                        '#6c5ce7',
                        '#ffa62b',
                        '#ffeaa7',
                        '#D980FA',
                        '#fccbcb',
                        '#45aaf2',
                        '#05dfd7',
                        '#FF00F6',
                        '#1e90ff',
                        '#2ed573',
                        '#eccc68',
                        '#ff5200',
                        '#cd84f1',
                        '#7efff5',
                        '#7158e2',
                        '#fff200',
                        '#ff9ff3',
                        '#08ffc8',
                        '#3742fa',
                        '#1089ff',
                        '#70FF61',
                        '#bf9fee',
                        '#574b90'
                    ],
                    borderColor: [
                        'rgba(231, 80, 90, 0.75)'
                    ],
                    borderWidth: 0,

                }]
            },
            options: {
                aspectRatio: 1,
                responsive: true,
                elements: {
                    line: {
                        tension: 0 // disables bezier curves
                    }
                },
                scales: {
                    xAxes: [{
                        display: false
                    }],
                    yAxes: [{
                        display: false
                    }]
                },
                legend: {
                    display: false,
                }
            }
        });

        // apex-line chart
        var options = {
            chart: {
                height: 450,
                type: "area",
                toolbar: {
                    show: false
                },
                dropShadow: {
                    enabled: true,
                    enabledSeries: [0],
                    top: -2,
                    left: 0,
                    blur: 10,
                    opacity: 0.08
                },
                animations: {
                    enabled: true,
                    easing: 'linear',
                    dynamicAnimation: {
                        speed: 1000
                    }
                },
            },
            dataLabels: {
                enabled: false
            },
            series: [{
                    name: "Plus Transactions",
                    data: [
                        @foreach ($trxReport['date'] as $trxDate)
                            {{ @$plusTrx->where('date', $trxDate)->first()->amount ?? 0 }},
                        @endforeach
                    ]
                },
                {
                    name: "Minus Transactions",
                    data: [
                        @foreach ($trxReport['date'] as $trxDate)
                            {{ @$minusTrx->where('date', $trxDate)->first()->amount ?? 0 }},
                        @endforeach
                    ]
                }
            ],
            fill: {
                type: "gradient",
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.9,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: [
                    @foreach ($trxReport['date'] as $trxDate)
                        "{{ $trxDate }}",
                    @endforeach
                ]
            },
            grid: {
                padding: {
                    left: 5,
                    right: 5
                },
                xaxis: {
                    lines: {
                        show: false
                    }
                },
                yaxis: {
                    lines: {
                        show: false
                    }
                },
            },
        };

        var chart = new ApexCharts(document.querySelector("#apex-line"), options);

        chart.render();
    </script>
@endpush
