<div class="card-body p-0">
    <div class="table-responsive--md table-responsive">
        <table class="table custom--table text-nowrap">
            <thead>
                <tr>
                    <th>@lang('TXN Date')</th>
                    <th>@lang('Symbol')</th>
                    <th>@lang('NSE Symbol')</th>
                    <th>@lang('Expiry')</th>
                    <th>@lang('Exchange')</th>
                    <th>@lang('Transaction Type')</th>
                    <th>@lang('Index Token')</th>
                    <th>@lang('Lot Size')</th>
                    <th>@lang('Atm Status')</th>
                    <th>@lang('Ce')</th>
                    <th>@lang('Pe')</th>
                    <th>@lang('Ce Entry Price')</th>
                    <th>@lang('pe Entry Price')</th>
                    <th>@lang('Ce Ltp')</th>
                    <th>@lang('Pe Ltp')</th>
                    <th>@lang('Combined Premium Ce Pe')</th>
                    <th>@lang('MTM')</th>
                    <th>@lang('Target Status')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paperTrade as $trade)
                    <tr>
                        <td>
                            <strong>{{showDate($trade->date)}}</strong>
                        </td>
                        <td>{{ $trade->symbol }}</td>
                        <td>{{ $trade->nse_symbol }}</td>
                        <td>{{ showDate($trade->expiry) }}</td>
                        <td>{{ $trade->exchange }}</td>
                        <td>{{ $trade->transaction_type }}</td>
                        <td>{{ $trade->index_token }}</td>
                        <td>{{ $trade->lot_size }}</td>
                        <td>{{ $trade->atm_status }}</td>
                        <td>{{ $trade->ce }}</td>
                        <td>{{ $trade->pe }}</td>
                        @php
                            $ce_textColor = "text-success";
                            if ($trade->ce_entry_price > $trade->ce_ltp ) {
                                $ce_textColor = "text-danger";
                            }

                            $pe_textColor = "text-success";
                            if ($trade->pe_entry_price > $trade->pe_ltp ) {
                                $pe_textColor = "text-danger";
                            }
                        @endphp
                        <td>{{ $trade->ce_entry_price }}</td>
                        <td>{{ $trade->pe_entry_price }}</td>
                        <td class="{{$ce_textColor}}">{{ $trade->ce_ltp }}</td>
                        <td class="{{$pe_textColor}}">{{ $trade->pe_ltp }}</td>
                        <td>{{ $trade->combined_premium_ce_pe*$trade->lot_size }}</td>
                        @php
                            $mtm = (($trade->ce_ltp+$trade->pe_ltp)*$trade->lot_size)-($trade->combined_premium_ce_pe*$trade->lot_size);
                            $target = "";
                        @endphp
                        <td>{{ $mtm }}</td>
                        @if ($trade->combined_premium_ce_pe == $trade->ce_ltp)
                            @php
                                $target = "CE Target Achieved";
                            @endphp
                        @elseif($trade->combined_premium_ce_pe == $trade->pe_ltp)
                            @php
                                $target = "PE Target Achieved";
                            @endphp
                        @endif
                        <td>{{ $target }}</td>
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