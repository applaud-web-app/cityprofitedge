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
                            @forelse($globalStockPortfolios as $globalStockPortfolio)
                                @php  $key = isset($date[$globalStockPortfolio->stock_name.'.NS']) ? $date[$globalStockPortfolio->stock_name.'.NS'] : 0;
                                @endphp
                                <tr>
                                    <td>
                                        {{ $globalStockPortfolio->broker_name }}
                                    </td>
                                    <td>
                                        {{ $globalStockPortfolio->stock_name }}
                                    </td>
                                    <td>
                                        {{ $globalStockPortfolio->quantity }}
                                    </td>
                                    <td>
                                        {{ showDate($globalStockPortfolio->buy_date) }}
                                    </td>
                                    <td>
                                        ${{ showAmount($globalStockPortfolio->buy_price) }}
                                    </td>
                                    <td>${{showAmount($key)}}</td>
                                    <td>
                                        ${{ showAmount($globalStockPortfolio->quantity*$key) }}
                                    </td>
                                    <td>   
                                        @php $vals = $globalStockPortfolio->quantity*($key - $globalStockPortfolio->buy_price);
                                        @endphp
                                        <span class='{{$vals > 0 ? "text-success" :"text-danger"}}'>{{showAmount($globalStockPortfolio->quantity*($key - $globalStockPortfolio->buy_price))}}</span>
                                    </td>
                                    <td>{{ $globalStockPortfolio->sector }}</td>
                                    <td>{{ $globalStockPortfolio->poolingAccountPortfolio->broker_name }}</td>
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
    {{ paginateLinks($globalStockPortfolios) }}
</div>