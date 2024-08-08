@extends($activeTemplate.'layouts.master')
@section('content')
<section class="pt-50 pb-50">

    <div class="container-fluid" id="pst_hre">
        <div class="row" >
            <div class="col-lg-12">
                <div class="custom--card" id="pst_hre">
                    <div class="card-body p-0">
                        <div class="table-responsive--md table-responsive">
                            <table class="table custom--table text-nowrap">
                                <thead>
                                    <tr>
                                        <th>@lang('Date')</th>
                                        <th>@lang('Symbol')</th>
                                        <th>@lang('CE')</th>
                                        <th>@lang('PE')</th>
                                        <th>@lang('Timeframe')</th>
                                        <th>@lang('Timestamp')</th>
                                        <th>@lang('CE Signal')</th>
                                        <th>@lang('PE Signal')</th>
                                        <th>@lang('CE Profit')</th>
                                        <th>@lang('PE Profit')</th>
                                        <th>@lang('Strategy Profit')</th>
                                     
                                    </tr>
                                </thead>
                                <tbody>
                                  
                                    @forelse($data['paperFactor'] as $factor)
                                        
                                        <tr>
                                            <td>
                                                <strong>{{showDate($factor->date)}}</strong>
                                            </td>
                                            <td>{{$factor->symbol }}</td>
                                           
                                            <td>{{$factor->ce}}</td>
                                            <td>{{$factor->pe}}</td>
                                            <td>{{$factor->timeframe}}</td>
                                            <td>{{$factor->timestamp}}</td>
                                            <td>{{$factor->ce_signal}}</td>
                                            <td>{{$factor->pe_signal}}</td>
                                            <td>{{$factor->ce_profit}}</td>
                                            <td>{{$factor->pe_profit}}</td>
                                            <td>{{$factor->strategy_profit}}</td>
                                         
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-muted text-center" colspan="100%">NO DATA</td>
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
@endsection


