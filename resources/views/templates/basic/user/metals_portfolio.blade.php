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
                                        <th>@lang('Buy Price (USD)')</th>
                                        <th>@lang('CMP (USD)')</th>
                                        <th>@lang('Current Value (USD)')</th>
                                        <th>@lang('Profit/Loss (USD)')</th>
                                        <th>@lang('Sector')</th>
                                        <th>@lang('Pooling Broker Name')</th>
                                    </tr>
                                </thead>
                                @php
                                $date = \DB::connection('mysql_pr')->table('LTP')->WHEREIN('symbol',$symbolArray)->pluck('ltp','symbol')->toArray();  
                                @endphp
                                <tbody>
                                    @forelse($metalsPortfolios as $metalsPortfolio)
                                    @php $key = isset($date[$metalsPortfolio->stock_name]) ? $date[$metalsPortfolio->stock_name] : 0;
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ $metalsPortfolio->broker_name }}
                                        </td>
                                        <td>
                                            {{ $metalsPortfolio->stock_name }}
                                        </td>
                                        <td>
                                            {{ $metalsPortfolio->quantity }}
                                        </td>
                                        <td>
                                            {{ showDate($metalsPortfolio->buy_date) }}
                                        </td>
                                        <td>
                                            ${{ showAmount($metalsPortfolio->buy_price) }}
                                        </td>
                                        <td>${{showAmount($key)}}</td>
                                        <td>
                                            ${{ showAmount($metalsPortfolio->quantity*$key) }}
                                        </td>
                                        <td> {{showAmount($metalsPortfolio->quantity*($key - $metalsPortfolio->buy_price))}} </td>
                                        <td>{{ $metalsPortfolio->sector }}</td>
                                        <td>{{ $metalsPortfolio->poolingAccountPortfolio->broker_name }}</td>
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
            {{ paginateLinks($metalsPortfolios) }}
        </div>
    </div>
</section>
@endsection


