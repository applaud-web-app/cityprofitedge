<div class="card-body p-0">
    <div class="table-responsive--md table-responsive">
        <table class="table custom--table text-nowrap">
            <thead>
                <tr>
                    <th>ENTRY DATE</th>
                    <th>SYMBOL NAME</th>
                    <th>BUY QTY</th>
                    <th>BUY PRICE</th>
                    <th>BUY VALUE</th>
                    <th>SELL QTY</th>
                    <th>SELL PRICE</th>
                    <th>SELL VALUE</th>
                    <th>NET CHANGE</th>
                    <th>LTP</th>
                    <th>MTM</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                @endphp
                @if (isset($respond))
                    @if ($respond['status'] == true)
                        @php $watchList = $respond['data']['fetched']; @endphp
                    @else
                        @php $watchList = NULL; @endphp
                    @endif
                @else
                    @php $watchList = NULL; @endphp
                @endif
                @isset($wishlistorder)
                    @if (count($wishlistorder) && $watchList != null)
                        @foreach ($wishlistorder as $item)
                            @php
                                $key = array_search($item->token, array_column($watchList, 'symbolToken'));
                                $angleData = App\Models\AngelApiInstrument::WHERE('token', $item->token)->first();

                                $buyValue = $item->buy_quantity * $item->buy_price;
                                $sellValue = $item->sell_quantity * $item->sell_price;
                                if ($angleData->name == 'CRUDEOIL') {
                                    $buyValue = $buyValue * 100;
                                    $sellValue = $sellValue * 100;
                                } elseif ($angleData->name == 'GOLD') {
                                    $buyValue = $buyValue * 10;
                                    $sellValue = $sellValue * 10;
                                } elseif ($angleData->name == 'NATURALGAS') {
                                    $buyValue = $buyValue * 1250;
                                    $sellValue = $sellValue * 1250;
                                } elseif ($angleData->name == 'SILVER') {
                                    $buyValue = $buyValue * 5;
                                    $sellValue = $sellValue * 5;
                                } else {
                                    $buyValue = $buyValue * $angleData->tick_size;
                                    $sellValue = $sellValue * $angleData->tick_size;
                                }
                            @endphp
                            <tr>
                                <td>{{ $item->created_at->format('d-M, Y H:i:s') }}</td>
                                <td>{{ $item->symbol }}</td>
                                <td>{{ $item->buy_quantity }}</td>
                                <td>{{ $item->buy_price }}</td>
                                <td>{{ $buyValue }}</td>
                                <td>{{ $item->sell_quantity }}</td>
                                <td>{{ $item->sell_price }}</td>
                                <td>{{ $sellValue }}</td>
                                <td>{{ $item->net_change }}</td>
                                <td>{{ $watchList[$key]['ltp'] }}</td>

                                @php
                                    $textColor = "text-success";
                                    $totalVal = ($watchList[$key]['ltp'] - $item->buy_price) * $item->buy_quantity;
                                    if(($totalVal * $angleData['lotsize']) < 0){
                                        $textColor = "text-danger";
                                    }
                                @endphp
                            <td class="{{$textColor}}" {{$angleData['lotsize']}}>{{$totalVal * $angleData['lotsize']}}</td>
                            </tr>
                            @php
                                $total += $totalVal * $angleData['lotsize'];
                            @endphp
                        @endforeach
                    @else
                        <tr>
                            <td colspan="100%" class="d-flex justify-content-center text-center">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </td>
                        </tr>
                    @endif
                @endisset
            </tbody>
        </table>
        <div class="d-flex justify-content-end">
            <button class="btn btn-danger">Total Profit : {{$total}}</button>
        </div>
    </div>
</div>
