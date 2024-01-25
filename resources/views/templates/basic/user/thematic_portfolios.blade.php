@extends($activeTemplate.'layouts.master')
@section('content')
<section class="pt-100 pb-100">
    <div class="container content-container">
        <form action="#" class="transparent-form mb-3">
            <div class="row">
                <div class="col-lg-3 form-group">
                    <label>@lang('Stock Name')</label>
                    <input type="text" name="search" value="{{ request()->search }}" class="form--control" placeholder="@lang('Stock Name')">
                </div>
                {{-- <div class="col-lg-3 form-group">
                    <label>@lang('Type')</label>
                    <select name="type" class="form--control">
                        <option value="" disabled>@lang('Select an option')</option>
                        <option value="+" @selected(request()->type == '+')>@lang('Profit')</option>
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
                                        <th>@lang('Stock Name')</th>
                                        <th>@lang('Reco Date')</th>
                                        <th>@lang('Buy Price (USD)')</th>
                                        <th>@lang('CMP (USD)')</th>
                                        <th>@lang('PNL (USD)')</th>
                                        <th>@lang('Sector')</th>
                                    </tr>
                                </thead>
                                @php
                                $date = \DB::connection('mysql_pr')->table('LTP')->WHEREIN('symbol',$symbolArray)->pluck('ltp','symbol')->toArray();  
                                @endphp
                                <tbody>
                                    @forelse($thematicPortfolios as $thematicPortfolio)
                                    @php  $key = isset($date[$thematicPortfolio->stock_name]) ? $date[$thematicPortfolio->stock_name] : 0;
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ $thematicPortfolio->stock_name }}
                                        </td>
                                        <td>
                                            {{ showDate($thematicPortfolio->reco_date) }}
                                        </td>
                                        <td>
                                            ${{ showAmount($thematicPortfolio->buy_price) }}
                                        </td>
                                        <td>${{showAmount($key)}}</td>
                                        <td>
                                            ${{ showAmount($thematicPortfolio->pnl) }}
                                        </td>
                                        <td>{{ $thematicPortfolio->sector }}</td>
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
            {{ paginateLinks($thematicPortfolios) }}
        </div>
    </div>
</section>
@endsection


