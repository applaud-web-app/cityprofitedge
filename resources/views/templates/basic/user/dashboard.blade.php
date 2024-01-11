@extends($activeTemplate.'layouts.master')
@section('content')
<!-- dashboard section start -->

<section class="pt-100 pb-100">
    <div class="container content-container">

        {{-- <div class="row notice"></div> --}}

        <div class="row justify-content-center gy-4">
            {{-- <div class="col-md-12 mb-3">
                <form action="#" class="transparent-form">
                    <label>@lang('Referral Link')</label>
                    <div class="input-group">
                        <input type="text" name="text" class="form-control form--control referralURL"
                            value="{{ route('home', ['reference'=>$user->username]) }}" readonly
                        >
                        <button class="input-group-text bg--base text-white border-0" id="copyBoard" type="button">
                            <span class="copytext"><i class="fa fa-copy"></i></span>
                        </button>
                    </div>
                </form>
            </div> --}}

            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="d-widget has--link">
                    <a href="{{ route('user.transactions') }}" class="item--link"></a>
                    <div class="d-widget__icon">
                        <img src="{{ getImage('assets/templates/basic/images/dashboard/' .'capital_investment.png') }}" alt="logo">
                        {{-- <i class="las la-money-bill-wave text--base"></i> --}}
                    </div>
                    <div class="d-widget__content">
                        <p class="d-widget__caption fs--14px">@lang('Invested Amount')</p>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Invested Value')</p>
                                <h3 class="d-widget__amount mt-1">
                                    {{ $general->cur_sym }} {{ showAmount($user->balance, 2) }}
                                </h3>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Current Value')</p>
                                <h3 class="d-widget__amount mt-1">
                                    {{ $general->cur_sym }} {{ showAmount($user->balance, 2) }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div><!-- d-widget end -->
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="d-widget has--link">
                    <a href="{{ route('user.stock.portfolios') }}" class="item--link"></a>
                    <div class="d-widget__icon">
                        <img src="{{ getImage('assets/templates/basic/images/dashboard/' .'portfolio.png') }}" alt="logo">
                        {{-- <i class="las la-money-bill-wave text--base"></i> --}}
                    </div>
                    <div class="d-widget__content">
                        <p class="d-widget__caption fs--14px">@lang('Stock Portfolio')</p>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Invested Value')</p>
                                <h3 class="d-widget__amount mt-1">
                                    {{ $general->cur_sym }} {{ showAmount($user->balance, 2) }}
                                </h3>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Current Value')</p>
                                <h3 class="d-widget__amount mt-1">
                                    {{ $general->cur_sym }} {{ showAmount($user->balance, 2) }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div><!-- d-widget end -->
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="d-widget has--link">
                    <a href="{{ route('user.thematic.portfolios') }}" class="item--link"></a>
                    <div class="d-widget__icon">
                        <img src="{{ getImage('assets/templates/basic/images/dashboard/' .'themeatic_portfolio.png') }}" alt="logo">
                        {{-- <i class="las la-money-bill-wave text--base"></i> --}}
                    </div>
                    <div class="d-widget__content">
                        <p class="d-widget__caption fs--14px">@lang('Thematic Portfolio')</p>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Invested Value')</p>
                                <h3 class="d-widget__amount mt-1">
                                    {{ $general->cur_sym }} {{ showAmount($user->balance, 2) }}
                                </h3>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Current Value')</p>
                                <h3 class="d-widget__amount mt-1">
                                    {{ $general->cur_sym }} {{ showAmount($user->balance, 2) }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div><!-- d-widget end -->
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="d-widget has--link">
                    <a href="{{ route('user.global.stock.portfolio') }}" class="item--link"></a>
                    <div class="d-widget__icon">
                        <img src="{{ getImage('assets/templates/basic/images/dashboard/' .'global_stocks.png') }}" alt="logo">
                        {{-- <i class="las la-money-bill-wave text--base"></i> --}}
                    </div>
                    <div class="d-widget__content">
                        <p class="d-widget__caption fs--14px">@lang('Global Stock Portfolio')</p>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Invested Value')</p>
                                <h3 class="d-widget__amount mt-1">
                                    {{ $general->cur_sym }} {{ showAmount($user->balance, 2) }}
                                </h3>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Current Value')</p>
                                <h3 class="d-widget__amount mt-1">
                                    {{ $general->cur_sym }} {{ showAmount($user->balance, 2) }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div><!-- d-widget end -->
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="d-widget has--link">
                    <a href="{{ route('user.fo.portfolio.hedging') }}" class="item--link"></a>
                    <div class="d-widget__icon">
                        <img src="{{ getImage('assets/templates/basic/images/dashboard/' .'profit.png') }}" alt="logo">
                        {{-- <i class="las la-money-bill-wave text--base"></i> --}}
                    </div>
                    <div class="d-widget__content">
                        <p class="d-widget__caption fs--14px">@lang('F&O Portfolio-Hedging')</p>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Invested Value')</p>
                                <h3 class="d-widget__amount mt-1">
                                    {{ $general->cur_sym }} {{ showAmount($user->balance, 2) }}
                                </h3>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Current Value')</p>
                                <h3 class="d-widget__amount mt-1">
                                    {{ $general->cur_sym }} {{ showAmount($user->balance, 2) }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div><!-- d-widget end -->
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="d-widget has--link">
                    <a href="{{ route('user.metals.portfolio') }}" class="item--link"></a>
                    <div class="d-widget__icon">
                        <img src="{{ getImage('assets/templates/basic/images/dashboard/' .'metals_portfolio.png') }}" alt="logo">
                        {{-- <i class="las la-money-bill-wave text--base"></i> --}}
                    </div>
                    <div class="d-widget__content">
                        <p class="d-widget__caption fs--14px">@lang('Metals Portfolio (Gold & Silver)')</p>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Invested Value')</p>
                                <h3 class="d-widget__amount mt-1">
                                    {{ $general->cur_sym }} {{ showAmount($user->balance, 2) }}
                                </h3>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Current Value')</p>
                                <h3 class="d-widget__amount mt-1">
                                    {{ $general->cur_sym }} {{ showAmount($user->balance, 2) }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div><!-- d-widget end -->
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="d-widget has--link">
                    <a href="{{ route('user.transactions') }}" class="item--link"></a>
                    <div class="d-widget__icon">
                        <img src="{{ getImage('assets/templates/basic/images/dashboard/' .'ledger.png') }}" alt="logo">
                        {{-- <i class="las la-money-bill-wave text--base"></i> --}}
                    </div>
                    <div class="d-widget__content">
                        <p class="d-widget__caption fs--14px">@lang('Invested in All Portfolios')</p>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Invested Value')</p>
                                <h3 class="d-widget__amount mt-1">
                                    {{ $general->cur_sym }} {{ showAmount($user->balance, 2) }}
                                </h3>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Current Value')</p>
                                <h3 class="d-widget__amount mt-1">
                                    {{ $general->cur_sym }} {{ showAmount($user->balance, 2) }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div><!-- d-widget end -->
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="d-widget has--link">
                    <a href="{{ route('user.transactions') }}" class="item--link"></a>
                    <div class="d-widget__icon">
                        <img src="{{ getImage('assets/templates/basic/images/dashboard/' .'pf_current_value.png') }}" alt="logo">
                        {{-- <i class="las la-money-bill-wave text--base"></i> --}}
                    </div>
                    <div class="d-widget__content">
                        <p class="d-widget__caption fs--14px">@lang('All Portfolios Current Value')</p>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Invested Value')</p>
                                <h3 class="d-widget__amount mt-1">
                                    {{ $general->cur_sym }} {{ showAmount($user->balance, 2) }}
                                </h3>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Current Value')</p>
                                <h3 class="d-widget__amount mt-1">
                                    {{ $general->cur_sym }} {{ showAmount($user->balance, 2) }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div><!-- d-widget end -->
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="d-widget has--link">
                    <a href="{{ route('user.transactions') }}" class="item--link"></a>
                    <div class="d-widget__icon">
                        <img src="{{ getImage('assets/templates/basic/images/dashboard/' .'networth.png') }}" alt="logo">
                        {{-- <i class="las la-money-bill-wave text--base"></i> --}}
                    </div>
                    <div class="d-widget__content">
                        <p class="d-widget__caption fs--14px">@lang('Networth')</p>
                        <h3 class="d-widget__amount mt-1">
                            {{ $general->cur_sym }} {{ showAmount($user->balance, 2) }}
                        </h3>
                    </div>
                </div><!-- d-widget end -->
            </div>
            {{-- <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="d-widget has--link">
                    <a href="javascript:void(0)" class="item--link {{ $user->package_id ? 'renewBtn' : null }}"
                        @if($user->package_id)
                            data-package="{{ @$user->package }}"
                        @endif
                    >
                    </a>
                    <div class="d-widget__icon v">
                        <i class="las la-calendar text--base"></i>
                    </div>
                    <div class="d-widget__content">
                        <p class="d-widget__caption fs--14px">
                            @if($user->package_id != 0)
                                {{ __(@$user->package->name) }}
                            @else
                                @lang('Package')
                            @endif
                        </p>
                        <div class="d-flex align-items-center">
                            <h3 class="d-widget__amount mt-1">
                                @if($user->package_id != 0)
                                    {{ showDateTime($user->validity, 'd M Y') }}
                                @else
                                    @lang('N/A')
                                @endif
                            </h3>
                            <small class="d-widget__caption ms-2">(@lang('Validity'))</small>
                        </div>
                    </div>
                </div><!-- d-widget end -->
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="d-widget has--link">
                    <a href="{{ route('user.deposit.history') }}" class="item--link"></a>
                    <div class="d-widget__icon">
                        <i class="las la-wallet text--base"></i>
                    </div>
                    <div class="d-widget__content">
                        <p class="d-widget__caption fs--14px">@lang('Total Deposit')</p>
                        <h3 class="d-widget__amount mt-1">
                            {{ $general->cur_sym }} {{ showAmount($totalDeposit, 2) }}
                        </h3>
                    </div>
                </div><!-- d-widget end -->
            </div>

            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="d-widget has--link">
                    <a href="{{ route('user.signals') }}" class="item--link"></a>
                    <div class="d-widget__icon">
                        <i class="las la-signal text--base"></i>
                    </div>
                    <div class="d-widget__content">
                        <p class="d-widget__caption fs--14px">@lang('Total Signal')</p>
                        <h3 class="d-widget__amount mt-1">
                            {{ $totalSignal }}
                        </h3>
                    </div>
                </div><!-- d-widget end -->
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="d-widget has--link">
                    <a href="{{ route('user.transactions') }}" class="item--link"></a>
                    <div class="d-widget__icon">
                        <i class="las la-exchange-alt text--base"></i>
                    </div>
                    <div class="d-widget__content">
                        <p class="d-widget__caption fs--14px">@lang('Total Transaction')</p>
                        <h3 class="d-widget__amount mt-1">
                            {{ $totalTrx }}
                        </h3>
                    </div>
                </div><!-- d-widget end -->
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="d-widget has--link">
                    <a href="{{ route('user.referrals') }}" class="item--link"></a>
                    <div class="d-widget__icon">
                        <i class="las la-users text--base"></i>
                    </div>
                    <div class="d-widget__content">
                        <p class="d-widget__caption fs--14px">@lang('Total Referral')</p>
                        <h3 class="d-widget__amount mt-1">
                            {{ $user->referrals->count() }}
                        </h3>
                    </div>
                </div><!-- d-widget end -->
            </div>
        </div> --}}

        <div class="row mt-4">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">@lang('Networth Graph')</h5>
                        <div id="apex-spline-chart"> </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">@lang('Sectory Wise Protfolio')</h5>
                        <div id="apex-polar-area-basic-chart"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-6">
                <h5 class="m-4 text-center">@lang('Top Gainers')</h5>
                <div class="custom--card">
                    <div class="card-body p-0">
                        <div class="table-responsive--md">
                            <table class="table custom--table">
                                <thead>
                                    <tr>
                                        <th>@lang('Stock Name')</th>
                                        <th>@lang('Avg Price')</th>
                                        <th>@lang('CMP')</th>
                                        <th>@lang('Change%')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($portfolioTopGainers as $portfolioTopGainer)
                                        <tr>
                                            <td>
                                                {{ $portfolioTopGainer->stock_name }}
                                            </td>
                                            <td>
                                                {{ showAmount($portfolioTopGainer->avg_buy_price) }}
                                            </td>
                                            <td>
                                                {{ $portfolioTopGainer->cmp }}
                                            </td>
                                            <td>{{ $portfolioTopGainer->change_percentage }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <h5 class="m-4 text-center">@lang('Top Losers')</h5>
                <div class="custom--card">
                    <div class="card-body p-0">
                        <div class="table-responsive--md">
                            <table class="table custom--table">
                                <thead>
                                    <tr>
                                        <th>@lang('Stock Name')</th>
                                        <th>@lang('Avg Price')</th>
                                        <th>@lang('CMP')</th>
                                        <th>@lang('Change%')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($portfolioTopLosers as $portfolioTopLoser)
                                        <tr>
                                            <td>
                                                {{ $portfolioTopLoser->stock_name }}
                                            </td>
                                            <td>
                                                {{ showAmount($portfolioTopLoser->avg_buy_price) }}
                                            </td>
                                            <td>
                                                {{ $portfolioTopLoser->cmp }}
                                            </td>
                                            <td>{{ $portfolioTopLoser->change_percentage }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- dashboard section end -->
@if($user->package_id)
    <div class="modal fade cmn--modal" id="renewModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title method-name"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('user.renew.package')}}" method="post">
                    @csrf
                    <div class="modal-body pt-0">
                        <div class="form-group">
                            <input type="hidden" name="id" required>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">@lang('Package') <span class="packageName"></span></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">@lang('Price') <span class="packagePrice"></span></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">@lang('Validity') <span class="packageValidity"></span></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">@lang('Your Balance')
                                <span>{{ showAmount($user->balance, 2) }} {{ __($general->cur_text) }} </span>
                            </li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--danger btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                        <div class="prevent-double-click">
                            <button type="submit" class="btn btn-sm btn--success">@lang('Confirm')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@endsection

@push('script')
<script>
    (function ($) {
        "use strict";

        @if($user->package_id != 0)
            $('.renewBtn').on('click', function () {
                var modal = $('#renewModal');

                modal.find('.modal-title').text('Are you sure to renew '+$(this).data('package').name);
                modal.find('.packageName').text($(this).data('package').name);
                modal.find('.packagePrice').text($(this).data('package').price+' '+@json( __($general->cur_text) ));
                modal.find('.packageValidity').text($(this).data('package').validity+' Days');
                modal.find('input[name=id]').val($(this).data('package').id);

                modal.modal('show');
            });
        @endif

        $('#copyBoard').click(function(){
            var copyText = document.getElementsByClassName("referralURL");
            copyText = copyText[0];
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            /*For mobile devices*/
            document.execCommand("copy");
            notify('success', "Copied: " + copyText.value)
        });

    })(jQuery);


</script>
<script src="{{ asset('assets/admin/js/vendor/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/vendor/chart.js.2.8.0.js') }}"></script>
<script>
    "use strict";
        var options = {
        series: [
            {
                name: '',
                data: [1000, 1500, 2000, 2300, 2700, 3000, 4000, 2700, 3045, 6000, 7000, 7500]
            }, {
                name: '',
                data: [300, 1200, 1500, 2000, 3000, 3500, 3700, 4000, 5000, 6000, 6200, 6900]
            }
        ],
        chart: {
            width: 775,
            height: 350,
            type: 'area'
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth'
        },
        xaxis: {
            type: 'datetime',
            categories: [
                "2023-01",
                "2023-02",
                "2023-03",
                "2023-04",
                "2023-05",
                "2023-06",
                "2023-07",
                "2023-08",
                "2023-09",
                "2023-10",
                "2023-11",
                "2023-12",
            ],
            labels: {
                style: {
                    colors: '#fff'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: '#fff'
                }
            }
        },
        tooltip: {
            x: {
                format: 'MM yyyy',
                style: {
                    color: '#fff'
                }
            },
        },
    };

    var chart = new ApexCharts(document.querySelector("#apex-spline-chart"), options);
    chart.render();


    var options = {
        series: [14, 23, 21, 17, 15, 10, 12, 17, 21],
        chart: {
            width: 775,
            height: 360,
            type: 'polarArea',
        },
        stroke: {
            colors: ['#fff']
        },
        fill: {
            opacity: 0.8
        },
        labels: ['Label1', 'Label2', 'Label3', 'Label4', 'Label5', 'Label6', 'Label7', 'Label8', 'Label9'], // Add your labels here
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    var chart = new ApexCharts(document.querySelector("#apex-polar-area-basic-chart"), options);
    chart.render();
</script>
@endpush

