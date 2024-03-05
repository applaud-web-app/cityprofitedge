@extends($activeTemplate.'layouts.master')
@section('content')
<!-- dashboard section start -->

<section class="pt-100 pb-100">
    <div class="container content-container">

        {{-- <div class="row notice"></div> --}}
        <div class="row justify-content-center g-3">
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
                        <h4 class="d-widget__caption text-center">@lang('Invested Amount')</h4>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Invested Value')</p>
                                <h6 class="d-widget__amount mt-1">
                                    {{ $general->cur_sym }} {{ showAmount($totalInvestedAmount, 2) }}
                                </h6>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Current Value')</p>
                                <h6 class="d-widget__amount mt-1">
                                    {{ $general->cur_sym }} {{ showAmount($totalCurrentAmount, 2) }}
                                </h6>
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
                        <h4 class="d-widget__caption text-center">@lang('Stock Portfolio')</h4>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Invested Value')</p>
                                <h6 class="d-widget__amount mt-1">
                                    {{ $general->cur_sym }} {{ showAmount($stockPortFolio->buy_value,2) }}
                                </h6>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Current Value')</p>
                                <h6 class="d-widget__amount mt-1">
                                    {{ $general->cur_sym }} {{ showAmount($stockPortFolio->current_value,2) }}
                                </h6>
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
                        <h4 class="d-widget__caption text-center">@lang('Thematic Portfolio')</h4>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Invested Value')</p>
                                <h6 class="d-widget__amount mt-1">
                                    ----
                                    {{-- {{ $general->cur_sym }} {{ showAmount($user->balance, 2) }} --}}
                                </h6>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Current Value')</p>
                                <h6 class="d-widget__amount mt-1">
                                    ----
                                    {{-- {{ $general->cur_sym }} {{ showAmount($user->balance, 2) }} --}}
                                </h6>
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
                        <h4 class="d-widget__caption text-center">@lang('Global Stock Portfolio')</h4>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Invested Value')</p>
                                <h6 class="d-widget__amount mt-1">
                                    {{ $general->cur_sym }} {{ showAmount($globalStockPortFolio->buy_value,2) }}
                                </h6>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Current Value')</p>
                                <h6 class="d-widget__amount mt-1">
                                    {{ $general->cur_sym }} {{ showAmount($globalStockPortFolio->current_value,2) }}
                                </h6>
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
                        <h4 class="d-widget__caption text-center">@lang('F&O Portfolio-Hedging')</h4>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Invested Value')</p>
                                <h6 class="d-widget__amount mt-1">
                                    {{ $general->cur_sym }} {{ showAmount($foglobalStockPortFolio->buy_value,2) }}
                                </h6>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Current Value')</p>
                                <h6 class="d-widget__amount mt-1">
                                    {{ $general->cur_sym }} {{ showAmount($foglobalStockPortFolio->current_value,2) }}
                                </h6>
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
                        <h4 class="d-widget__caption text-center">@lang('Metals Portfolio (Gold & Silver)')</h4>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Invested Value')</p>
                                <h6 class="d-widget__amount mt-1">
                                    {{ $general->cur_sym }} {{ showAmount($metalsPortFolio->buy_value,2) }}
                                </h6>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Current Value')</p>
                                <h6 class="d-widget__amount mt-1">
                                    {{ $general->cur_sym }} {{ showAmount($metalsPortFolio->current_value,2) }}
                                </h6>
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
                        <h4 class="d-widget__caption text-center">@lang('Invested in All Portfolios')</h4>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Invested Value')</p>
                                <h6 class="d-widget__amount mt-1">
                                    {{ $general->cur_sym }} {{ showAmount($user->balance, 2) }}
                                </h6>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Current Value')</p>
                                <h6 class="d-widget__amount mt-1">
                                    {{ $general->cur_sym }} {{ showAmount($user->balance, 2) }}
                                </h6>
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
                        <h4 class="d-widget__caption text-center">@lang('All Portfolios Current Value')</h4>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Invested Value')</p>
                                <h6 class="d-widget__amount mt-1">
                                    {{ $general->cur_sym }} {{ showAmount($user->balance, 2) }}
                                </h6>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <p class="d-widget__caption fs--12px">@lang('Current Value')</p>
                                <h6 class="d-widget__amount mt-1">
                                    {{ $general->cur_sym }} {{ showAmount($user->balance, 2) }}
                                </h6>
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
                        <h4 class="d-widget__caption text-center">@lang('Networth')</h4>
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
            </div>--}}
        </div> 

        <div class="row mt-4">
            <div class="col-xl-6">
                <div class="card dash-card">
                    <div class="card-header">
                        <h5 class="card-title">@lang('Networth Graph')</h5>
                    </div>
                    <div class="card-body">
                       
                        <div id="apex-spline-chart" style="width: 100%;"> </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card dash-card">
                     <div class="card-header">
                         <h5 class="card-title">@lang('Sectory Wise Protfolio')</h5>
                    </div>
                    <div class="card-body">
                      
                        <div id="apex-polar-area-basic-chart" style="width: 100%;"></div>
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
                                        <th class="text-start">@lang('Symbol')</th>
                                        <th class="text-start">@lang('LTP')</th>
                                        <th class="text-start">@lang('Change')</th>
                                        <th class="text-start">@lang('%Change')</th>
                                    </tr>
                                </thead>
                                <tbody id="topGainer">
                                    <tr>
                                        <td colspan="100%">
                                            <div class="spinner-border" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </td>
                                    </tr>
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
                                        <th class="text-start">@lang('Symbol')</th>
                                        <th class="text-start">@lang('LTP')</th>
                                        <th class="text-start">@lang('Change')</th>
                                        <th class="text-start">@lang('%Change')</th>
                                    </tr>
                                </thead>
                                <tbody id="topLoser">
                                    <tr>
                                        <td colspan="100%">
                                            <div class="spinner-border" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </td>
                                    </tr>
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
                            <li class="list-group-item d-flex justify-content-between align-items-center">@lang('Product') <span class="packageName"></span></li>
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
    let longIo;
    let shortIo; 
    let shortCover;
    let longWill;
    let pcrData;
    let Toploser;
    let topgainer;
   $(document).ready(function(){
        FetchLongOIData();
        FetchShortOIData();
        FetchShortCoveringOIData();
        FetchLongUnwilingOIData();
        FetchPCRData();
        FetchTopLoserData();
        FetchTopGainerData();

        var longIo = setInterval(() => {
            FetchLongOIData();
        }, 10 * 1000);

        var shortIo = setInterval(() => {
            FetchShortOIData();
        }, 10 * 1000);


        var shortCover = setInterval(() => {
            FetchShortCoveringOIData();
        }, 10 * 1000);

        var longWill = setInterval(() => {
            FetchLongUnwilingOIData();
        }, 10 * 1000);

        var pcrData = setInterval(() => {
            FetchPCRData();
        }, 10 * 1000);

        var Toploser = setInterval(() => {
            FetchTopLoserData();
        }, 10 * 1000);

        var topgainer = setInterval(() => {
            FetchTopGainerData();
        }, 10 * 1000);
        
    });

    function FetchTopLoserData(){
        $.get('{{route("get-top-loser-api-data")}}',function(data){
           if(data['status'] === true){
                data = data['data'];
                if(data.length > 0){
                    var str = "";
                    for (var i in data) {
                        if(i>4){
                            break;
                        } 
                        str += `<tr>
                        <td class="text-start">${data[i].tradingSymbol}</td>
                        <td class="text-start">${data[i].ltp}</td>
                        <td class="text-start text-danger">${data[i].netChange}</td>
                        <td class="text-start text-danger">${data[i].percentChange}</td></tr>`;
                    }
                    $("#topLoser").html(str);
                }else{
                    $("#topLoser").html('');
                }
            }else{
                $("#topLoser").html('<tr><td colspan="100%">No Response Please Try Again Later</tr></td>');
                clearInterval(Toploser);
            }
        });
    }
    
    function FetchTopGainerData(){
        $.get('{{route("get-top-gainer-api-data")}}',function(data){
           if(data['status'] === true){
            data = data['data'];
            if(data.length > 0){
                var str = "";
                for (var i in data) {
                    if(i>4){
                        break;
                    } 
                    str += `<tr>
                       <td class="text-start">${data[i].tradingSymbol}</td>
                       <td class="text-start">${data[i].ltp}</td>
                       <td class="text-start text-success">${data[i].netChange}</td>
                       <td class="text-start text-success">${data[i].percentChange}</td></tr>`;
                }
                $("#topGainer").html(str);
            }else{
                $("#topGainer").html('');
            }
           }else{
            $("#topGainer").html('<tr><td colspan="100%">No Response Please Try Again Later</tr></td>');
                clearInterval(topgainer);
            }
        });
    }

    function FetchPCRData(){
        $.get('{{route("get-pcr-api-data")}}',function(data){
           if(data['status'] === true){
            data = data['data'];
            if(data.length > 0){
                var str = "";
                for (var i in data) {
                    if (data[i].tradingSymbol.indexOf("NIFTY") != -1) {
                        str += `<tr>
                        <td class="text-start">${data[i].tradingSymbol}</td>
                        <td class="text-start">${data[i].pcr}</td>`;
                    }
                }
                $("#pcr").html(str);
            }else{
                $("#pcr").html('');
            }
           }else{
            $("#pcr").html('<tr><td colspan="100%">No Response Please Try Again Later</tr></td>');
                clearInterval(pcrData);
            }
        });
    }

    function FetchLongOIData(){
        $.get('{{route("get-long-build-api-data")}}',function(data){
           if(data['status'] === true){
            data = data['data'];
            if(data.length > 0){
                var str = "";
                for (var i in data) {
                    if(i>4){
                        break;
                    } 
                    str += `<tr>
                       <td class="text-start">${data[i].tradingSymbol}</td>
                       <td class="text-start">${data[i].ltp}</td>
                       <td class="text-start ${data[i].netChange > 0 ? 'text-success' : 'text-danger'}">${data[i].netChange}</td>
                       <td class="text-start ${data[i].percentChange > 0 ? 'text-success' : 'text-danger'}">${data[i].percentChange}</td>
                       <td class="text-start">${Math.trunc(data[i].opnInterest)}</td>
                       <td class="text-start ${data[i].netChangeOpnInterest > 0 ? 'text-success' : 'text-danger'}">${Math.trunc(data[i].netChangeOpnInterest)}</td>`;
                }
                $("#longBuild").html(str);
            }else{
                $("#longBuild").html('');
            }
           }else{
                $("#longBuild").html('<tr><td colspan="100%">No Response Please Try Again Later</tr></td>');
                clearInterval(longIo);
            }
        });
    }

    function FetchShortOIData(){
        $.get('{{route("get-short-build-api-data")}}',function(data){
            if(data['status'] === true){
                data = data['data'];
                if(data.length > 0){
                    var str = "";
                    for (var i in data) {
                        if(i>4){
                            break;
                        } 
                        str += `<tr>
                        <td class="text-start">${data[i].tradingSymbol}</td>
                        <td class="text-start">${data[i].ltp}</td>
                        <td class="text-start ${data[i].netChange > 0 ? 'text-success' : 'text-danger'}">${data[i].netChange}</td>
                        <td class="text-start ${data[i].percentChange > 0 ? 'text-success' : 'text-danger'}">${data[i].percentChange}</td>
                        <td class="text-start">${Math.trunc(data[i].opnInterest)}</td>
                        <td class="text-start ${data[i].netChangeOpnInterest > 0 ? 'text-success' : 'text-danger'}">${Math.trunc(data[i].netChangeOpnInterest)}</td>`;
                    }
                    $("#shortBuild").html(str);
                }else{
                    $("#shortBuild").html('');
                }
            }else{
                $("#shortBuild").html('<tr><td colspan="100%">No Response Please Try Again Later</tr></td>');
                clearInterval(shortIo);
            }
        });
    }

    function FetchShortCoveringOIData(){
        $.get('{{route("get-short-covering-api-data")}}',function(data){
           if(data['status'] === true){
                data = data['data'];
                if(data.length > 0){
                    var str = "";
                    for (var i in data) {
                        if(i>4){
                            break;
                        } 
                        str += `<tr>
                        <td class="text-start">${data[i].tradingSymbol}</td>
                        <td class="text-start">${data[i].ltp}</td>
                        <td class="text-start ${data[i].netChange > 0 ? 'text-success' : 'text-danger'}">${data[i].netChange}</td>
                        <td class="text-start ${data[i].percentChange > 0 ? 'text-success' : 'text-danger'}">${data[i].percentChange}</td>
                        <td class="text-start">${Math.trunc(data[i].opnInterest)}</td>
                        <td class="text-start ${data[i].netChangeOpnInterest > 0 ? 'text-success' : 'text-danger'}">${Math.trunc(data[i].netChangeOpnInterest)}</td>`;
                    }
                    $("#shortCovering").html(str);
                }else{
                    $("#shortCovering").html('');
                }
           }else{
                $("#shortCovering").html('<tr><td colspan="100%">No Response Please Try Again Later</tr></td>');
                clearInterval(shortCover);
            }
        });
    }

    function FetchLongUnwilingOIData(){
        $.get('{{route("get-long-unwilling-api-data")}}',function(data){
            if(data['status'] === true){
                data = data['data'];
                if(data.length > 0){
                    var str = "";
                    for (var i in data) {
                        if(i>4){
                            break;
                        } 
                        str += `<tr>
                        <td class="text-start">${data[i].tradingSymbol}</td>
                        <td class="text-start">${data[i].ltp}</td>
                        <td class="text-start ${data[i].netChange > 0 ? 'text-success' : 'text-danger'}">${data[i].netChange}</td>
                        <td class="text-start ${data[i].percentChange > 0 ? 'text-success' : 'text-danger'}">${data[i].percentChange}</td>
                        <td class="text-start">${Math.trunc(data[i].opnInterest)}</td>
                        <td class="text-start ${data[i].netChangeOpnInterest > 0 ? 'text-success' : 'text-danger'}">${Math.trunc(data[i].netChangeOpnInterest)}</td>`;
                    }
                    $("#longUnwilling").html(str);
                }else{
                    $("#longUnwilling").html('');
                }
            }else{
                $("#longUnwilling").html('<tr><td colspan="100%">No Response Please Try Again Later</tr></td>');
                clearInterval(longWill);
            }
        });
    }
  
    
</script>
@endpush
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
                data: [{{implode(",",$buyArr)}}]
            }, {
                name: '',
                data: [{{implode(",",$currArr)}}]
            }
        ],
        chart: {
             width: '100%',
            height: 360,
            type: 'area',
            toolbar: {
        show: false,
       
    }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth'
        },
        
        xaxis: {
            type: 'date',
            categories: [
                {!!"'".implode("','", $datesArr)."'"!!}
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
            enabled: true,
            theme: 'dark',
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
        series: [{{implode(',',$chrtArr)}}],
        chart: {
            width: '100%',
            height: 360,
            type: 'polarArea',
            foreColor: '#e4e4e4',
        },
        stroke: {
          colors: ['#fff']
        },
        fill: {
          opacity: 0.8
        },
        labels: ['Stock Portfolio', 'Metals Portfolio', 'Global stock', 'F&O Portfolio'], // Add your labels here
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

