<div class="container">
    <div class="about-thumb -from-top-wow fadeInUp" data-wow-duration="0.5" data-wow-delay="0.5s" id="faqAccordion">
        <div class="row g-3">
            <div class="col-lg-4">
                <div class="d-flex align-items-center">
                    <h2>Top Gainers</h2>
                    {{-- <a class="text--base ms-3" href="#">View All</a> --}}
                </div>
                <div class="custom--card">
                    <div class="card-body p-0">
                        <div class="table-responsive--md">
                            <table class="text-start table custom--table">
                                <thead>
                                    <tr>
                                        <th class="text-start">@lang('Symbol')</th>
                                        <th class="text-start">@lang('LTP')</th>
                                        <th class="text-start">@lang('Change')</th>
                                        <th class="text-start">@lang('%Change')</th>
                                    </tr>
                                </thead>
                                <tbody id="topGainer">
                                    @if (isset($topGainer))
                                        @if (count($topGainer))
                                            @foreach ($topGainer as $item)
                                                @if ($item->type == "gainer")
                                                    <tr>
                                                        <td class="text-start">{{$item->symbol}}</td>
                                                        <td class="text-start">{{$item->ltp}}</td>
                                                        <td class="text-start text-success">{{$item->net_change}}</td>
                                                        <td class="text-start text-success">{{$item->per_change}}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="100%">
                                                    <span>No Data Found</span>
                                                </td>
                                            </tr>
                                        @endif                                            
                                    @else
                                        <tr>
                                            <td colspan="100%">
                                                <span>No Data Found</span>
                                            </td>
                                        </tr>
                                    @endif                                        
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="d-flex align-items-center">
                    <h2>Top Losers</h2>
                    {{-- <a class="text--base ms-3" href="#">View All</a> --}}
                </div>
                <div class="custom--card">
                    <div class="card-body p-0">
                        <div class="table-responsive--md">
                            <table class="text-start table custom--table">
                                <thead>
                                    <tr>
                                        <th class="text-start">@lang('Symbol')</th>
                                        <th class="text-start">@lang('LTP')</th>
                                        <th class="text-start">@lang('Change')</th>
                                        <th class="text-start">@lang('%Change')</th>
                                    </tr>
                                </thead>
                                <tbody id="topLoser">
                                    @if (isset($topLoser))
                                        @if (count($topLoser))
                                            @foreach ($topLoser as $item)
                                                @if ($item->type == "loser")
                                                    <tr>
                                                        <td class="text-start">{{$item->symbol}}</td>
                                                        <td class="text-start">{{$item->ltp}}</td>
                                                        <td class="text-start text-danger">{{$item->net_change}}</td>
                                                        <td class="text-start text-danger">{{$item->per_change}}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @else
                                            <tr>
                                                <td  colspan="100%">
                                                    <span>No Data Found</span>
                                                </td>
                                            </tr>
                                        @endif
                                    @else
                                        <tr>
                                            <td  colspan="100%">
                                                <span>No Data Found</span>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="d-flex align-items-center">
                    <h2>PCR Volume</h2>
                    {{-- <a class="text--base ms-3" href="#">View All</a> --}}
                </div>
                <div class="custom--card">
                    <div class="card-body p-0">
                        <div class="table-responsive--md">
                            <table class="text-start table custom--table">
                                <thead>
                                    <tr>
                                        <th class="text-start">@lang('Company')</th>
                                        <th class="text-start">@lang('PCR')</th>
                                    </tr>
                                </thead>
                                <tbody id="pcr">
                                    @if (isset($getPcrData))
                                        @if (count($getPcrData))
                                            @foreach ($getPcrData as $item)
                                                <tr>
                                                    <td class="text-start">{{$item->symbol}}</td>
                                                    <td class="text-start">{{$item->pcr}}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="100%">
                                                    <span>No Data Found</span>
                                                </td>
                                            </tr>
                                        @endif
                                    @else
                                        <tr>
                                            <td colspan="100%">
                                                <span>No Data Found</span>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="d-flex align-items-center">
                    <h2>OI BuildUp - Long</h2>
                    {{-- <a class="text--base ms-3" href="#">View All</a> --}}
                </div>
                <div class="custom--card">
                    <div class="card-body p-0">
                        <div class="table-responsive--md">
                            <table class="text-start table custom--table">
                                <thead>
                                    <tr>
                                        <th class="text-start">@lang('Company')</th>
                                        <th class="text-start">@lang('LTP')</th>
                                        <th class="text-start">@lang('Net Change')</th>
                                        <th class="text-start">@lang('%Change')</th>
                                        <th class="text-start">@lang('OI')</th>
                                        <th class="text-start">@lang('OI NET CHANGE')</th>
                                    </tr>
                                </thead>
                                <tbody id="longBuild">
                                    @if (isset($longBuildUp))
                                       @if (count($longBuildUp))
                                           @foreach ($longBuildUp as $item)
                                                @if ($item->type == "long")
                                                    <tr>
                                                        <td class="text-start">{{$item->symbol}}</td>
                                                        <td class="text-start">{{$item->ltp}}</td>
                                                        <td class="text-start {{$item->net_change > 0 ? 'text-success' : 'text-danger'}}">{{$item->net_change}}</td>
                                                        <td class="text-start {{$item->per_change > 0 ? 'text-success' : 'text-danger'}}">{{$item->per_change}}</td>
                                                        <td class="text-start">{{$item->oi}}</td>
                                                        <td class="text-start {{$item->oi_change > 0 ? 'text-success' : 'text-danger'}}">{{$item->oi_change}}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                       @else
                                           <tr>
                                                <td  colspan="100%">
                                                    <span>No Data Found</span>
                                                </td>
                                            </tr>
                                       @endif
                                    @else
                                        <tr>
                                            <td  colspan="100%">
                                                <span>No Data Found</span>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="d-flex align-items-center">
                    <h2>OI BuildUp - Short</h2>
                    {{-- <a class="text--base ms-3" href="#">View All</a> --}}
                </div>
                <div class="custom--card">
                    <div class="card-body p-0">
                        <div class="table-responsive--md">
                            <table class="text-start table custom--table">
                                <thead>
                                    <tr>
                                        <th class="text-start">@lang('Company')</th>
                                        <th class="text-start">@lang('LTP')</th>
                                        <th class="text-start">@lang('Net Change')</th>
                                        <th class="text-start">@lang('%Change')</th>
                                        <th class="text-start">@lang('OI')</th>
                                        <th class="text-start">@lang('OI NET CHANGE')</th>
                                    </tr>
                                </thead>
                                <tbody id="shortBuild">
                                    @if (isset($shortBuildUp))
                                        @if (count($shortBuildUp))
                                            @foreach ($shortBuildUp as $item)
                                                @if ($item->type == "short")
                                                    <tr>
                                                        <td class="text-start">{{$item->symbol}}</td>
                                                        <td class="text-start">{{$item->ltp}}</td>
                                                        <td class="text-start {{$item->net_change > 0 ? 'text-success' : 'text-danger'}}">{{$item->net_change}}</td>
                                                        <td class="text-start {{$item->per_change > 0 ? 'text-success' : 'text-danger'}}">{{$item->per_change}}</td>
                                                        <td class="text-start">{{$item->oi}}</td>
                                                        <td class="text-start {{$item->oi_change > 0 ? 'text-success' : 'text-danger'}}">{{$item->oi_change}}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @else
                                            <tr>
                                                <td  colspan="100%">
                                                    <span>No Data Found</span>
                                                </td>
                                            </tr>
                                        @endif
                                    @else
                                        <tr>
                                            <td  colspan="100%">
                                                <span>No Data Found</span>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="d-flex align-items-center">
                    <h2>OI BuildUp - Short Covering</h2>
                    {{-- <a class="text--base ms-3" href="#">View All</a> --}}
                </div>
                <div class="custom--card">
                    <div class="card-body p-0">
                        <div class="table-responsive--md">
                            <table class="text-start table custom--table">
                                <thead>
                                    <tr>
                                        <th class="text-start">@lang('Company')</th>
                                        <th class="text-start">@lang('LTP')</th>
                                        <th class="text-start">@lang('Net Change')</th>
                                        <th class="text-start">@lang('%Change')</th>
                                        <th class="text-start">@lang('OI')</th>
                                        <th class="text-start">@lang('OI NET CHANGE')</th>
                                    </tr>
                                </thead>
                                <tbody id="shortCovering">
                                    @if (isset($coveringBuildUp))
                                        @if (count($coveringBuildUp))
                                           @foreach ($coveringBuildUp as $item)
                                                @if ($item->type == "covering")
                                                    <tr>
                                                        <td class="text-start">{{$item->symbol}}</td>
                                                        <td class="text-start">{{$item->ltp}}</td>
                                                        <td class="text-start {{$item->net_change > 0 ? 'text-success' : 'text-danger'}}">{{$item->net_change}}</td>
                                                        <td class="text-start {{$item->per_change > 0 ? 'text-success' : 'text-danger'}}">{{$item->per_change}}</td>
                                                        <td class="text-start">{{$item->oi}}</td>
                                                        <td class="text-start {{$item->oi_change > 0 ? 'text-success' : 'text-danger'}}">{{$item->oi_change}}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                       @else
                                           <tr>
                                                <td  colspan="100%">
                                                    <span>No Data Found</span>
                                                </td>
                                            </tr>
                                       @endif                                            
                                    @else
                                        <tr>
                                            <td  colspan="100%">
                                                <span>No Data Found</span>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="d-flex align-items-center">
                    <h2>OI BuildUp - Long Unwinding</h2>
                    {{-- <a class="text--base ms-3" href="#">View All</a> --}}
                </div>
                <div class="custom--card">
                    <div class="card-body p-0">
                        <div class="table-responsive--md">
                            <table class="text-start table custom--table">
                                <thead>
                                    <tr>
                                        <th class="text-start">@lang('Company')</th>
                                        <th class="text-start">@lang('LTP')</th>
                                        <th class="text-start">@lang('Net Change')</th>
                                        <th class="text-start">@lang('%Change')</th>
                                        <th class="text-start">@lang('OI')</th>
                                        <th class="text-start">@lang('OI NET CHANGE')</th>
                                    </tr>
                                </thead>
                                <tbody id="longUnwilling">
                                    @if (isset($unWindingBuildUp))
                                        @if (count($unWindingBuildUp))
                                            @foreach ($unWindingBuildUp as $item)
                                                @if ($item->type == "unwinding")
                                                    <tr>
                                                        <td class="text-start">{{$item->symbol}}</td>
                                                        <td class="text-start">{{$item->ltp}}</td>
                                                        <td class="text-start {{$item->net_change > 0 ? 'text-success' : 'text-danger'}}">{{$item->net_change}}</td>
                                                        <td class="text-start {{$item->per_change > 0 ? 'text-success' : 'text-danger'}}">{{$item->per_change}}</td>
                                                        <td class="text-start">{{$item->oi}}</td>
                                                        <td class="text-start {{$item->oi_change > 0 ? 'text-success' : 'text-danger'}}">{{$item->oi_change}}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="100%">
                                                    <span>No Data Found</span>
                                                </td>
                                            </tr>
                                        @endif
                                    @else
                                        <tr>
                                            <td colspan="100%">
                                                <span>No Data Found</span>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
             {{-- START --}}
                <div class="col-lg-6">
                    <div class="d-flex align-items-center">
                        <h2>FII DII PRO-FUT Index Analysis</h2>
                        {{-- <a class="text--base ms-3" href="#">View All</a> --}}
                    </div>
                    <div class="custom--card">
                        <div class="card-body p-0">
                            <div class="table-responsive--md table-responsive">
                                <table class="table custom--table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="text-start text-uppercase">@lang('Date')</th>
                                            <th class="text-start text-uppercase">@lang('Client Type')</th>
                                            <th class="text-start text-uppercase">@lang('fut idx view')</th>
                                            <th class="text-start text-uppercase">@lang('net fut idx view')</th>
                                            <th class="text-start text-uppercase">@lang('market Outlook fut idx')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($fdProData))
                                            @if (count($fdProData))
                                                @foreach ($fdProData as $item)
                                                <tr>
                                                    <td class="text-start">{{$item->date}}</td>
                                                    <td class="text-start">{{$item->client_type}}</td>
                                                    <td class="text-start">{{$item->future_index_view}}</td>
                                                    <td class="text-start">{{$item->net_future_index_view}}</td>
                                                    <td class="text-start">{{$item->market_status_future_index}}</td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="100%">
                                                        <span>No Data Found</span>
                                                    </td>
                                                </tr>
                                            @endif
                                        @else
                                            <tr>
                                                <td colspan="100%">
                                                    <span>No Data Found</span>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="d-flex align-items-center">
                        <h2>FII DII PRO CE-Option Analysis</h2>
                        {{-- <a class="text--base ms-3" href="#">View All</a> --}}
                    </div>
                    <div class="custom--card">
                        <div class="card-body p-0">
                            <div class="table-responsive--md table-responsive">
                                <table class="table custom--table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="text-start text-uppercase">@lang('Date')</th>
                                            <th class="text-start text-uppercase">@lang('Client Type')</th>
                                            <th class="text-start text-uppercase">@lang('opt idx ce view')</th>
                                            <th class="text-start text-uppercase">@lang('net idx ce view')</th>
                                            <th class="text-start text-uppercase">@lang('market Outlook idx ce')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($fdProData))
                                            @if (count($fdProData))
                                                @foreach ($fdProData as $item)
                                                <tr>
                                                    <td class="text-start">{{$item->date}}</td>
                                                    <td class="text-start">{{$item->client_type}}</td>
                                                    <td class="text-start">{{$item->option_index_ce_view}}</td>
                                                    <td class="text-start">{{$item->net_index_ce_view}}</td>
                                                    <td class="text-start">{{$item->market_status_index_ce}}</td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="100%">
                                                        <span>No Data Found</span>
                                                    </td>
                                                </tr>
                                            @endif
                                        @else
                                            <tr>
                                                <td colspan="100%">
                                                    <span>No Data Found</span>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="d-flex align-items-center">
                        <h2>FII DII PRO PE-Option Analysis</h2>
                        {{-- <a class="text--base ms-3" href="#">View All</a> --}}
                    </div>
                    <div class="custom--card">
                        <div class="card-body p-0">
                            <div class="table-responsive--md table-responsive">
                                <table class="table custom--table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="text-start text-uppercase">@lang('Date')</th>
                                            <th class="text-start text-uppercase">@lang('Client Type')</th>
                                            <th class="text-start text-uppercase">@lang('opt idx pe view')</th>
                                            <th class="text-start text-uppercase">@lang('net idx pe view')</th>
                                            <th class="text-start text-uppercase">@lang('market Outlook idx pe')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($fdProData))
                                            @if (count($fdProData))
                                                @foreach ($fdProData as $item)
                                                <tr>
                                                    <td class="text-start">{{$item->date}}</td>
                                                    <td class="text-start">{{$item->client_type}}</td>
                                                    <td class="text-start">{{$item->option_index_pe_view}}</td>
                                                    <td class="text-start">{{$item->net_index_pe_view}}</td>
                                                    <td class="text-start">{{$item->market_status_index_pe}}</td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="100%">
                                                        <span>No Data Found</span>
                                                    </td>
                                                </tr>
                                            @endif
                                        @else
                                            <tr>
                                                <td colspan="100%">
                                                    <span>No Data Found</span>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            {{-- END --}}
        </div>
    </div>
</div>
