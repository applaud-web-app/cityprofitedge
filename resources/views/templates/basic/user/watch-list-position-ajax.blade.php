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
            @isset($wishlistorder)
                @if (count($wishlistorder))
                    @foreach ($wishlistorder as $item)
                        @php
                            $angleData = App\Models\AngelApiInstrument::WHERE('token',$item->token)->first();
                            $tickSize = 1;
                            $buyValue = $item->buy_quantity * $item->buy_price;
                            $sellValue = $item->sell_quantity * $item->sell_price;
                            if (isset($angleData)) {
                                if($angleData->name == "CRUDEOIL"){
                                    $buyValue = $buyValue*100;
                                    $sellValue = $sellValue*100;
                                    $tickSize = $angleData['lotsize'];
                                }else if($angleData->name == "GOLD"){
                                    $buyValue = $buyValue*10;
                                    $sellValue = $sellValue*10;
                                    $tickSize = $angleData['lotsize'];
                                }else if($angleData->name == "NATURALGAS"){
                                    $buyValue = $buyValue*1250;
                                    $sellValue = $sellValue*1250;
                                    $tickSize = $angleData['lotsize'];
                                }else if($angleData->name == "SILVER"){
                                    $buyValue = $buyValue*5;
                                    $sellValue = $sellValue*5;
                                    $tickSize = $angleData['lotsize'];
                                }else{
                                    $buyValue = $buyValue*$angleData->tick_size;
                                    $sellValue = $sellValue*$angleData->tick_size;
                                    $tickSize = $angleData['lotsize'];
                                }
                            }
                        @endphp
                        <tr>
                            <td>{{($item->created_at)->format('d-M, Y H:i:s')}}</td>
                            <td>{{$item->symbol}}</td>
                            <td>{{$item->buy_quantity}}</td>
                            <td>{{$item->buy_price}}</td>
                            <td>{{$buyValue}}</td>
                            <td>{{$item->sell_quantity}}</td>
                            <td>{{$item->sell_price}}</td> 
                            <td>{{$sellValue}}</td>
                            <td>{{$item->net_change}}</td>
                            <td>{{$item->ltp}}</td>
                            @php
                                $textColor = "text-success";
                                $totalVal = ($item->ltp - $item->buy_price) * $item->buy_quantity;
                                if(($totalVal * $tickSize) < 0){
                                    $textColor = "text-danger";
                                }
                            @endphp
                        <td class="{{$textColor}}" {{$tickSize}}>{{round($totalVal * $tickSize,2)}}</td>
                        </tr>
                        @php
                            $total += $totalVal * $tickSize;
                        @endphp
                    @endforeach
                @else
                    <tr>
                        <td colspan="100%" class="d-flex justify-content-center text-center">
                            No Data Found...
                        </td>
                    </tr>
                @endif
            @endisset
            </tbody>
        </table>
        <div class="d-flex justify-content-end">
            <button class="btn btn-danger">Total Profit : {{round($total,2)}}</button>
        </div>
    </div>
</div>
