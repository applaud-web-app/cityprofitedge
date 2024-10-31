 @forelse($paperFactor as $value)
            <div class="row mb-3">
                <div class="col-lg-12">
                    
                    <div class="custom--card">
                        <div class="card-header"></div>
                        <div class="card-body p-0">
                            <div class="table-responsive--md table-responsive">
                                <table class="table custom--table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>@lang('TIME')</th>
                                            <th>@lang('TXN Type')</th>
                                            <th>@lang('Symbol')</th>
                                            <th>@lang('LotSize')</th>
                                            <th>@lang('QTY')</th>
                                            <th>@lang('Investment')</th>
                                            <th>@lang('Close Price')</th>
                                            <th>@lang('Entry Price')</th>
                                            <th>@lang('Current Value')</th>
                                            <th>@lang('Profit')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $data = json_decode($value->json_data,true);
                                            $revArr = array_reverse($data);
                                            $fData = $isFiltered == 0 ? array_slice($revArr,0,5) : $revArr;
                                            $totalItems = 0;
                                            $itemsPerPage = 100;
                                            $currentPage =  isset($_GET['page']) ? $_GET['page'] : 1;

                                            $arrData = $fData;  
                                            $totalItems = count($arrData);
                                            $currentItems = array_slice($arrData, ($currentPage - 1) * $itemsPerPage, $itemsPerPage);
                                        
                                        @endphp
                                        @foreach($currentItems as $val)
                                        <tr>
                                            <td>{{isset($val['time']) ? $val['time']: '-'}}</td>
                                            <td>{{$val['txn']}}</td>
                                            <td>{{$val['symbol']}}</td>
                                            <td>{{$val['lotsize']}}</td>
                                            <td>{{$val['quantity']}}</td>
                                            <td>{{$val['investment']}}</td>
                                            <td>{{$val['close_price']}}</td>
                                            <td>{{$val['entry_price']}}</td>
                                            <td>{{$val['current_value']}}</td>
                                            <td>{{$val['profit']}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div>
                            @php
                             if($isFiltered==1):
                                $totalPages = ceil($totalItems / $itemsPerPage);
                                echo '<nav class="mt-3 justify-content-end d-flex">
                                        <ul class="pagination mb-0">';
                            @endphp
                            @for($i = 1; $i <= $totalPages; $i++)
                                @if($i == $currentPage)
                                    <li class="page-item active" aria-current="page"><span class="page-link">{{$i}}</span></li>
                                @else
                                <li class="page-item"><a class="page-link" href="{{url('user/paper-x-factor?factor_symbol='.request('factor_symbol').'&factor_date='.request('factor_date').'&page='.$i.'')}}">{{$i}}</a></li>
                                @endif
                            @endfor
                            @php     
                                echo '  </ul>
                                    </nav>';
                                endif;
                            @endphp
                        </div>
                </div>
            </div>
        @empty
            <div class="row mb-3">
                <div class="col-lg-12">
                    <div class="custom--card">
                       
                        <div class="card-body p-0">
                            <h3 class="text-center text-danger">NO DATA</h3>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
