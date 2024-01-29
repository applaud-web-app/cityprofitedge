<section class="pt-100 pb-100">
    <div class="container">
        <div class="about-thumb -from-top-wow fadeInUp" data-wow-duration="0.5" data-wow-delay="0.5s" id="faqAccordion">
            <div class="row g-3">
                <div class="col-lg-6">
                    <h2>Top Gainers</h2>
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
                                        <tr>
                                            <td colspan="100%">
                                                <div class="spinner-border" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <h2>Top Losers</h2>
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
                                        <tr>
                                            <td colspan="100%">
                                                <div class="spinner-border" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <h2>PCR Volume</h2>
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
                                        <tr>
                                            <td colspan="100%">
                                                <div class="spinner-border" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <h2>OI BuildUp</h2>
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
                                            <th class="text-start">@lang('Interest')</th>
                                            <th class="text-start">@lang('Net Change Interest')</th>
                                        </tr>
                                    </thead>
                                    <tbody id="oi">
                                        <tr>
                                            <td colspan="100%">
                                                <div class="spinner-border" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('script')
<script>
    function FetchTopLoserData(){
        $.get('{{route("get-top-loser-api-data")}}',function(data){
           if(data['status'] === true){
            data = data['data'];
            if(data.length > 0){
                var str = "";
                for (var i in data) {
                    if(i>4){
                        break;
                    } 
                    str += `<tr>
                       <td class="text-start">${data[i].tradingSymbol}</td>
                       <td class="text-start">${data[i].ltp}</td>
                       <td class="text-start">${data[i].netChange}</td>
                       <td class="text-start">${data[i].percentChange}</td></tr>`;
                }
                $("#topLoser").html(str);
            }else{
                $("#topLoser").html('');
            }
           }
        });
    }

    function FetchTopGainerData(){
        $.get('{{route("get-top-gainer-api-data")}}',function(data){
           if(data['status'] === true){
            data = data['data'];
            if(data.length > 0){
                var str = "";
                for (var i in data) {
                    if(i>4){
                        break;
                    } 
                    str += `<tr>
                       <td class="text-start">${data[i].tradingSymbol}</td>
                       <td class="text-start">${data[i].ltp}</td>
                       <td class="text-start">${data[i].netChange}</td>
                       <td class="text-start">${data[i].percentChange}</td></tr>`;
                }
                $("#topGainer").html(str);
            }else{
                $("#topGainer").html('');
            }
           }
        });
    }

    function FetchPCRData(){
        $.get('{{route("get-pcr-api-data")}}',function(data){
           if(data['status'] === true){
            data = data['data'];
            if(data.length > 0){
                var str = "";
                for (var i in data) {
                    if(i>4){
                        break;
                    } 
                    str += `<tr>
                       <td class="text-start">${data[i].tradingSymbol}</td>
                       <td class="text-start">${data[i].pcr}</td>`;
                    if(i>4){
                        break;
                    } 
                }
                $("#pcr").html(str);
            }else{
                $("#pcr").html('');
            }
           }
        });
    }

    function FetchOIData(){
        $.get('{{route("get-oi-api-data")}}',function(data){
           if(data['status'] === true){
            data = data['data'];
            if(data.length > 0){
                var str = "";
                for (var i in data) {
                    if(i>4){
                        break;
                    } 
                    str += `<tr>
                       <td class="text-start">${data[i].tradingSymbol}</td>
                       <td class="text-start">${data[i].ltp}</td>
                       <td class="text-start">${data[i].netChange}</td>
                       <td class="text-start">${data[i].percentChange}</td>
                       <td class="text-start">${data[i].opnInterest}</td>
                       <td class="text-start">${data[i].netChangeOpnInterest}</td>`;
                    if(i>4){
                        break;
                    } 
                }
                $("#oi").html(str);
            }else{
                $("#oi").html('');
            }
           }
        });
    }
  
    $(document).ready(function(){
        FetchOIData();
        FetchPCRData();
        FetchTopLoserData();
        FetchTopGainerData();
        setInterval(() => {
            FetchOIData();
            FetchTopLoserData();
            FetchPCRData();
            FetchTopGainerData();
        }, 10 * 1000);
        
    });
</script>
@endpush