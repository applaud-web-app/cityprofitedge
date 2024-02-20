<div class="card-body p-0">
    <div class="table-responsive--md table-responsive">
        <table class="table custom--table text-nowrap">
            <thead>
                <tr>
                    <th>ENTRY DATE</th>
                    <th>SYMBOL NAME</th>
                    <th>BUY QTY</th>
                    <th>BUY PRICE</th>
                    <th>SELL QTY</th>
                    <th>SELL PRICE</th>
                    <th>NET CHANGE</th>
                    <th>LTP</th>
                    <th>UNREALIZED P/L</th>
                </tr>
            </thead>
            <tbody id="watchList">

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
                    @if (count($wishlistorder) && $watchList != NULL)
                        @foreach ($wishlistorder as $item)
                            @php
                                $key = array_search($item->token, array_column($watchList, 'symbolToken'));
                            @endphp
                            <tr>
                                <td>{{($item->created_at)->format('d-M, Y H:i:s')}}</td>
                                <td>{{$item->symbol}}</td>
                                <td>{{$item->buy_quantity}}</td>
                                <td>{{$item->buy_price}}</td>
                                <td>{{$item->sell_quantity}}</td>
                                <td>{{$item->sell_price}}</td> 
                                <td>{{$item->net_change}}</td>
                                <td>{{$watchList[$key]['ltp']}}</td>
                                <td class="{{ (($watchList[$key]['ltp'] - $item->buy_price) * $item->quantity) > 0 ? 'text-success' : 'text-danger' }}">{{($watchList[$key]['ltp'] - $item->buy_price) * $item->buy_quantity}}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="100%" class="text-center">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </td>
                        </tr>
                    @endif
                @endisset
            </tbody>
        </table>
    </div>
</div>