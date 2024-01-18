@extends($activeTemplate.'layouts.master')
@section('content')
@push('style')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush
<section class="pt-100 pb-100">
    <div class="container content-container">
        <form action="#" class="transparent-form mb-3">
            <div class="row">
                <div class="col-lg-2 col-md-2 col-6 form-group">
                    <label>@lang('Segments')</label>
                    <select name="segments" class="form--control">
                        <option value="" disabled>@lang('Select an option')</option>
                        <option value="all" @selected(request()->type == '+')>@lang('All')</option>
                        <option value="global" @selected(request()->type == '-')>@lang('Global Stock Portfolios')</option>
                        <option value="fQ" @selected(request()->type == '-')>@lang('F&O Portfolio Hedging')</option>
                        <option value="metals" @selected(request()->type == '-')>@lang('Metals Portfolios')</option>
                        <option value="stock" @selected(request()->type == '-')>@lang('Stock Portfolio')</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-2 col-6 form-group">
                    <label>@lang('P and L')</label>
                    <select name="type" class="form--control">
                        <option value="" disabled>@lang('Select an option')</option>
                        <option value="all" @selected(request()->type == '+')>@lang('Combine')</option>
                        <option value="realised" @selected(request()->type == '-')>@lang('Realised')</option>
                        <option value="unrealized" @selected(request()->type == '-')>@lang('Unrealized')</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-2 col-6 form-group">
                    <label>@lang('Symbol')</label>
                    <select name="symbol" class="form--control">
                        <option value="" selected disabled>@lang('Select an option')</option>
                        @isset($allData)
                        @php $symbol = []; @endphp
                            @foreach ($allData as $item)
                                @if (isset($item['stock_name']))
                                     @if (in_array($item['stock_name'],$symbol))

                                     @else
                                        @php array_push($symbol,$item['stock_name']); @endphp
                                     @endif
                                @endif
                            @endforeach
                            @foreach ($symbol as $item)
                                <option value="{{$item}}" @selected(request()->type == '+')>@lang($item)</option>
                            @endforeach
                        @endisset 
                    </select>
                </div>
                <div class="col-lg-2 col-md-2 col-6 form-group">
                    <label>@lang('Dates')</label>
                    <input type="text" name="buyDate" id="dates_range" value="" class="form--control" placeholder="Choose Date">
                </div>
                <div class="col-lg-2 col-md-2 col-6 form-group mt-auto">
                    <button class="btn btn--base w-100" type="submit"><i class="las la-filter"></i> @lang('Filter')</button>
                </div>
            </div>
        </form>
        <div class="row g-3">
            <div class="col-lg-3 col-md-6 col-12">
                <div class="custom--card">
                    <div class="card-body">
                        <h5 class="card-title">@lang('Realised PNL')</h5>
                        <h2 class="text--base">
                            @isset($allData)
                            @php
                                $Realised = 0;
                            @endphp
                                @foreach ($allData as $item)
                                    @if (isset($item['cmp']))
                                        @php
                                            $Realised += $item['profit_loss'];
                                        @endphp
                                    @endif
                                @endforeach
                            @endisset    
                            @lang($Realised)</h2>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="custom--card">
                    <div class="card-body">
                        <h5 class="card-title">@lang('Not Realised PNL')</h5>
                        <h2 class="text--base">
                            @isset($allData)
                            @php
                                $notRealised = 0;
                            @endphp
                                @foreach ($allData as $item)
                                    @if (isset($item['bought_date']))
                                        @php
                                            $notRealised += $item['profit_loss'];
                                        @endphp
                                    @endif
                                @endforeach
                            @endisset
                            @lang($notRealised)</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-lg-12">
                <div class="custom--card">
                    <div class="card-body p-0">
                        <div class="table-responsive--md">
                            <table class="table custom--table">
                                <thead>
                                    <tr>
                                        <th>Stock Name</th>
                                        <th>Buy Date</th>
                                        <th>Buy Price</th>
                                        <th>Quantity</th>
                                        <th>Sold Date</th>
                                        <th>Sell Price</th>
                                        <th>PNL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($combinedArray)
                                        @foreach ($combinedArray as $item)
                                            <tr>
                                                <td>{{$item['stock_name']}}</td>
                                                <td>{{showDate($item['buy_date'])}}</td>
                                                <td>{{$item['buy_price']}}</td>
                                                <td>{{$item['quantity']}}</td>
                                                <td> @if (isset($item['sold_date']))
                                                    {{showDate($item['sold_date'])}}
                                                @else
                                                    {{"---"}}
                                                @endif</td>
                                                <td>  @if (isset($item['sell_price']))
                                                    {{$item['sell_price']}}
                                                @else
                                                    {{"---"}}
                                                @endif</td>
                                                <td>{{$item['profit_loss']}}</td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4 justify-content-center d-flex">
           {{-- pagination links --}}
        </div>
    </div>
</section>

@push('script')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
   $("#dates_range").daterangepicker({
        autoUpdateInput: false,
        minYear: 1901,
        showDropdowns: true,
    }).on("apply.daterangepicker", function (e, picker) {
        picker.element.val(picker.startDate.format(picker.locale.format) + " - " + picker.endDate.format(picker.locale.format));
    });

</script>
@endpush
@endsection


