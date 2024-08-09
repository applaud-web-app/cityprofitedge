@extends($activeTemplate.'layouts.master')
@section('content')
<section class="pt-50 pb-50">
    <div class="container content-container" id="pst_hre">
        <!-- Form to filter by date -->
        <form action="{{ url()->current() }}" method="GET" class="transparent-form mb-3">
            <div class="row">
                <div class="col-lg-3 form-group">
                    <label for="factor_date">Choose Date</label>
                    <input type="date" name="factor_date" class="form--control" id="factor_date" 
                           value="{{ request('factor_date') }}" max="{{ date('Y-m-d') }}" />
                </div>
                <div class="col-lg-2 form-group mt-auto">
                    <button class="btn btn--base w-100" type="submit"><i class="las la-filter"></i> Filter</button>
                </div>
                <div class="col-lg-2 col-md-3 col-6 form-group mt-auto">
                    <a href="{{ url('/user/paper-x-factor') }}" class="btn btn--base w-100"><i class="las la-redo-alt"></i> Refresh</a>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-lg-12">
                <div class="custom--card" id="pst_hre">
                    <div class="card-body p-0">
                        <div class="table-responsive--md table-responsive">
                            <table class="table custom--table text-nowrap">
                                <thead>
                                    <tr>
                                        {{-- <th>@lang('Date')</th>
                                        <th>@lang('Timestamp')</th>
                                        <th>@lang('Symbol')</th>
                                        <th>@lang('CE')</th>
                                        <th>@lang('PE')</th>
                                        <th>@lang('CE Signal')</th>
                                        <th>@lang('PE Signal')</th>
                                        <th>@lang('CE Profit')</th>
                                        <th>@lang('PE Profit')</th>
                                        <th>@lang('Strategy Profit')</th> --}}
                                        <th>@lang('TIME')</th>
                                        <th>@lang('CE SYMBOL')</th>
                                        <th>@lang('CE CLOSE')</th>
                                        <th>@lang('FINAL CE')</th>
                                        <th>@lang('CE QTY LOTS')</th>
                                        <th>@lang('CE ENTRY PRICE')</th>
                                        <th>@lang('CE EXIT PRICE')</th>
                                        <th>@lang('CE PROFIT')</th>
                                        <th>@lang('PE SYMBOL')</th>
                                        <th>@lang('PE CLOSE')</th>
                                        <th>@lang('FINAL PE')</th>
                                        <th>@lang('PE QTY LOTS')</th>
                                        <th>@lang('PE ENTRY PRICE')</th>
                                        <th>@lang('PE EXIT PRICE')</th>
                                        <th>@lang('PE PROFIT')</th>
                                        <th>@lang('STRATEGY PROFIT')</th>
                                        <th>@lang('TRADE STATUS')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @forelse($data['paperFactor'] as $factor)
                                        <tr>
                                            <td><strong>{{ showDate($factor->date) }}</strong></td>
                                            <td>{{ $factor->timestamp }}</td>
                                            <td>{{ $factor->symbol }}</td>
                                            <td>{{ $factor->ce }}</td>
                                            <td>{{ $factor->pe }}</td>
                                           
                                           
                                            <td>{{ $factor->ce_signal }}</td>
                                            <td>{{ $factor->pe_signal }}</td>
                                            <td>{{ $factor->ce_profit }}</td>
                                            <td>{{ $factor->pe_profit }}</td>
                                            <td>{{ $factor->strategy_profit }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-muted text-center" colspan="100%">NO DATA</td>
                                        </tr>
                                    @endforelse --}}

                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
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
@endsection
