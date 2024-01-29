<section class="pt-100 pb-100">
    <div class="container">
        <div class="about-thumb -from-top-wow fadeInUp" data-wow-duration="0.5" data-wow-delay="0.5s" id="faqAccordion">
            <div class="row g-3">
                <div class="col-lg-6">
                    <h2>Top Losers</h2>
                    <div class="custom--card">
                        <div class="card-body p-0">
                            <div class="table-responsive--md">
                                <table class="table custom--table">
                                    <thead>
                                        <tr>
                                            <th>@lang('SNo.')</th>
                                            <th>@lang('Symbol')</th>
                                            <th>@lang('LTP')</th>
                                            <th>@lang('Change')</th>
                                            <th>@lang('%Change')</th>
                                        </tr>
                                    </thead>
                                    <tbody id="topLoser">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <h2>Top Gainers</h2>
                    <div class="custom--card">
                        <div class="card-body p-0">
                            <div class="table-responsive--md">
                                <table class="table custom--table">
                                    <thead>
                                        <tr>
                                            <th>@lang('SNo.')</th>
                                            <th>@lang('Symbol')</th>
                                            <th>@lang('LTP')</th>
                                            <th>@lang('Change')</th>
                                            <th>@lang('%Change')</th>
                                        </tr>
                                    </thead>
                                    <tbody id="topGainer">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-lg-6">
                    <div class="custom--card">
                        <div class="card-body p-0">
                            <div class="table-responsive--md">
                                <table class="table custom--table">
                                    <thead>
                                        <tr>
                                            <th>@lang('SNo.')</th>
                                            <th>@lang('Company')</th>
                                            <th>@lang('LTP')</th>
                                            <th>@lang('Change')</th>
                                            <th>@lang('%Change')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="custom--card">
                        <div class="card-body p-0">
                            <div class="table-responsive--md">
                                <table class="table custom--table">
                                    <thead>
                                        <tr>
                                            <th>@lang('SNo.')</th>
                                            <th>@lang('Company')</th>
                                            <th>@lang('LTP')</th>
                                            <th>@lang('Change')</th>
                                            <th>@lang('%Change')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</section>

@push('script')
<script>
    function FetchTopLoserData(){
        $.get('{{route("get-top-loser-api-data")}}',function(data){
           console.log(data);
           if(data['status'] === true){
            data = data['data'];
            if(data.length){
                var str = "";
                var j =0;
                for (var i in data) {
                    str += `<tr><td>${++j}</td>
                       <td>${data[i].tradingSymbol}</td>
                       <td>${data[i].ltp}</td>
                       <td>${data[i].netChange}</td>
                       <td>${data[i].percentChange}</td></tr>`;
                }
                $("#topLoser").html(str);
            }else{
                $("#topLoser").html('');
            }
           }else{
            console.log('Not FOund');
           }
        });
    }

    function FetchTopGainerData(){
        $.get('{{route("get-top-gainer-api-data")}}',function(data){
            console.log(data);
           if(data['status'] === true){
            data = data['data'];
            if(data.length){
                var str = "";
                var j =0;
                for (var i in data) {
                    str += `<tr><td>${++j}</td>
                       <td>${data[i].tradingSymbol}</td>
                       <td>${data[i].ltp}</td>
                       <td>${data[i].netChange}</td>
                       <td>${data[i].percentChange}</td></tr>`;
                }
                $("#topGainer").html(str);
            }else{
                $("#topGainer").html('');
            }
           }else{
            console.log('Not Found');
           }
        });
          
    }
  
    $(document).ready(function(){
        FetchTopLoserData();
        FetchTopGainerData();
        setInterval(() => {
            FetchTopLoserData();
        }, 10 * 1000);

        setInterval(() => {
            FetchTopGainerData();
        }, 5 * 1000);
        
    });
</script>
@endpush