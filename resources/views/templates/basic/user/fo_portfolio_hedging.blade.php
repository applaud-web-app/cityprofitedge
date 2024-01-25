@extends($activeTemplate.'layouts.master')
@section('content')
<section class="pt-100 pb-100">
    <div class="container content-container">
        <form action="#" class="transparent-form mb-3">
            <div class="row">
                <div class="col-lg-3 form-group">
                    <label>@lang('Broker Name')</label>
                    <input type="text" name="search" value="{{ request()->search }}" class="form--control" placeholder="@lang('Broker Name')">
                </div>
                <div class="col-lg-3 form-group">
                    <label>@lang('Stock Name')</label>
                    <input type="text" name="search" value="{{ request()->search }}" class="form--control" placeholder="@lang('Stock Name')">
                </div>
                {{-- <div class="col-lg-3 form-group">
                    <label>@lang('Type')</label>
                    <select name="type" class="form--control">
                        <option value="">@lang('All')</option>
                        <option value="+" @selected(request()->type == '+')>@lang('Plus')</option>
                        <option value="-" @selected(request()->type == '-')>@lang('Minus')</option>
                    </select>
                </div> --}}
                <div class="col-lg-3 form-group mt-auto">
                    <button class="btn btn--base w-100" type="submit"><i class="las la-filter"></i> @lang('Filter')</button>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-lg-12">
                <div class="custom--card">
                    <div class="card-body p-0">
                        <div class="table-responsive--md">
                            <table class="table custom--table">
                                <thead>
                                    <tr>
                                        <th>@lang('Broker Name')</th>
                                        <th>@lang('Stock Name')</th>
                                        <th>@lang('Qty')</th>
                                        <th>@lang('Buy Date')</th>
                                        <th>@lang('Buy Price')</th>
                                        <th>@lang('CMP')</th>
                                        <th>@lang('Current Value')</th>
                                        <th>@lang('Profit/Loss')</th>
                                        <th>@lang('Sector')</th>
                                        <th>@lang('Pooling Broker Name')</th>
                                    </tr>
                                </thead>
                                @php
                                $date = \DB::connection('mysql_pr')->table('LTP')->WHEREIN('symbol',$symbolArray)->pluck('ltp','symbol')->toArray();  
                                @endphp
                                <tbody>
                                    @forelse($foPortFolioHedgings as $foPortFolioHedging)
                                        @php  $key = isset($date[$foPortFolioHedging->stock_name.'.NS']) ? $date[$foPortFolioHedging->stock_name.'.NS'] : 0;
                                        @endphp
                                        <tr>
                                            <td>
                                                {{ $foPortFolioHedging->broker_name }}
                                            </td>
                                            <td>
                                                {{ $foPortFolioHedging->stock_name }}
                                            </td>
                                            <td>
                                                {{ $foPortFolioHedging->quantity }}
                                            </td>
                                            <td>
                                                {{ showDate($foPortFolioHedging->buy_date) }}
                                            </td>
                                            <td>
                                                {{ showAmount($foPortFolioHedging->buy_price) }}
                                            </td>
                                            <td>{{showAmount($key)}}</td>
                                            <td>
                                                {{ showAmount($foPortFolioHedging->quantity*$key) }}
                                            </td>
                                            <td> {{showAmount($foPortFolioHedging->quantity*($key - $foPortFolioHedging->buy_price))}} </td>
                                            <td>{{ $foPortFolioHedging->sector }}</td>
                                            <td>{{ $foPortFolioHedging->poolingAccountPortfolio->broker_name }}</td>
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
        <div class="mt-4 justify-content-center d-flex">
            {{ paginateLinks($foPortFolioHedgings) }}
        </div>
    </div>
</section>
@endsection


