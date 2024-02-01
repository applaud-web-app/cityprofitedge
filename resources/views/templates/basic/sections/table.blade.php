<section class="pt-100 pb-100">
    <div class="container">
        <div class="about-thumb -from-top-wow fadeInUp" data-wow-duration="0.5" data-wow-delay="0.5s" id="faqAccordion">
            <div class="row g-3">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center">
                        <h2>Top Gainers</h2><a class="text--base ms-3" href="#">View All</a>
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
                <div class="col-lg-4">
                    <div class="d-flex align-items-center">
                        <h2>Top Losers</h2><a class="text--base ms-3" href="#">View All</a>
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
                <div class="col-lg-4">
                    <div class="d-flex align-items-center">
                        <h2>PCR Volume</h2><a class="text--base ms-3" href="#">View All</a>
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
                    <div class="d-flex align-items-center">
                        <h2>OI BuildUp - Long</h2><a class="text--base ms-3" href="#">View All</a>
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
                    <div class="d-flex align-items-center">
                        <h2>OI BuildUp - Short</h2><a class="text--base ms-3" href="#">View All</a>
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
                    <div class="d-flex align-items-center">
                        <h2>OI BuildUp - Short Covering</h2><a class="text--base ms-3" href="#">View All</a>
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
                    <div class="d-flex align-items-center">
                        <h2>OI BuildUp - Long Unwinding</h2><a class="text--base ms-3" href="#">View All</a>
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
    let longIo;
    let shortIo; 
    let shortCover;
    let longWill;
    let pcrData;
    let Toploser;
    let topgainer;
   $(document).ready(function(){
        FetchLongOIData();
        FetchShortOIData();
        FetchShortCoveringOIData();
        FetchLongUnwilingOIData();
        FetchPCRData();
        FetchTopLoserData();
        FetchTopGainerData();

        var longIo = setInterval(() => {
            FetchLongOIData();
        }, 10 * 1000);

        var shortIo = setInterval(() => {
            FetchShortOIData();
        }, 10 * 1000);


        var shortCover = setInterval(() => {
            FetchShortCoveringOIData();
        }, 10 * 1000);

        var longWill = setInterval(() => {
            FetchLongUnwilingOIData();
        }, 10 * 1000);

        var pcrData = setInterval(() => {
            FetchPCRData();
        }, 10 * 1000);

        var Toploser = setInterval(() => {
            FetchTopLoserData();
        }, 10 * 1000);

        var topgainer = setInterval(() => {
            FetchTopGainerData();
        }, 10 * 1000);
        
    });

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
                        <td class="text-start text-danger">${data[i].netChange}</td>
                        <td class="text-start text-danger">${data[i].percentChange}</td></tr>`;
                    }
                    $("#topLoser").html(str);
                }else{
                    $("#topLoser").html('');
                }
            }else{
                $("#topLoser").html('<tr><td colspan="100%">No Response Please Try Again Later</tr></td>');
                clearInterval(Toploser);
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
                       <td class="text-start text-success">${data[i].netChange}</td>
                       <td class="text-start text-success">${data[i].percentChange}</td></tr>`;
                }
                $("#topGainer").html(str);
            }else{
                $("#topGainer").html('');
            }
           }else{
            $("#topGainer").html('<tr><td colspan="100%">No Response Please Try Again Later</tr></td>');
                clearInterval(topgainer);
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
                    if (data[i].tradingSymbol.indexOf("NIFTY") != -1) {
                        str += `<tr>
                        <td class="text-start">${data[i].tradingSymbol}</td>
                        <td class="text-start">${data[i].pcr}</td>`;
                    }
                }
                $("#pcr").html(str);
            }else{
                $("#pcr").html('');
            }
           }else{
            $("#pcr").html('<tr><td colspan="100%">No Response Please Try Again Later</tr></td>');
                clearInterval(pcrData);
            }
        });
    }

    function FetchLongOIData(){
        $.get('{{route("get-long-build-api-data")}}',function(data){
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
                       <td class="text-start ${data[i].netChange > 0 ? 'text-success' : 'text-danger'}">${data[i].netChange}</td>
                       <td class="text-start ${data[i].percentChange > 0 ? 'text-success' : 'text-danger'}">${data[i].percentChange}</td>
                       <td class="text-start">${Math.trunc(data[i].opnInterest)}</td>
                       <td class="text-start ${data[i].netChangeOpnInterest > 0 ? 'text-success' : 'text-danger'}">${Math.trunc(data[i].netChangeOpnInterest)}</td>`;
                }
                $("#longBuild").html(str);
            }else{
                $("#longBuild").html('');
            }
           }else{
                $("#longBuild").html('<tr><td colspan="100%">No Response Please Try Again Later</tr></td>');
                clearInterval(longIo);
            }
        });
    }

    function FetchShortOIData(){
        $.get('{{route("get-short-build-api-data")}}',function(data){
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
                        <td class="text-start ${data[i].netChange > 0 ? 'text-success' : 'text-danger'}">${data[i].netChange}</td>
                        <td class="text-start ${data[i].percentChange > 0 ? 'text-success' : 'text-danger'}">${data[i].percentChange}</td>
                        <td class="text-start">${Math.trunc(data[i].opnInterest)}</td>
                        <td class="text-start ${data[i].netChangeOpnInterest > 0 ? 'text-success' : 'text-danger'}">${Math.trunc(data[i].netChangeOpnInterest)}</td>`;
                    }
                    $("#shortBuild").html(str);
                }else{
                    $("#shortBuild").html('');
                }
            }else{
                $("#shortBuild").html('<tr><td colspan="100%">No Response Please Try Again Later</tr></td>');
                clearInterval(shortIo);
            }
        });
    }

    function FetchShortCoveringOIData(){
        $.get('{{route("get-short-covering-api-data")}}',function(data){
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
                        <td class="text-start ${data[i].netChange > 0 ? 'text-success' : 'text-danger'}">${data[i].netChange}</td>
                        <td class="text-start ${data[i].percentChange > 0 ? 'text-success' : 'text-danger'}">${data[i].percentChange}</td>
                        <td class="text-start">${Math.trunc(data[i].opnInterest)}</td>
                        <td class="text-start ${data[i].netChangeOpnInterest > 0 ? 'text-success' : 'text-danger'}">${Math.trunc(data[i].netChangeOpnInterest)}</td>`;
                    }
                    $("#shortCovering").html(str);
                }else{
                    $("#shortCovering").html('');
                }
           }else{
                $("#shortCovering").html('<tr><td colspan="100%">No Response Please Try Again Later</tr></td>');
                clearInterval(shortCover);
            }
        });
    }

    function FetchLongUnwilingOIData(){
        $.get('{{route("get-long-unwilling-api-data")}}',function(data){
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
                        <td class="text-start ${data[i].netChange > 0 ? 'text-success' : 'text-danger'}">${data[i].netChange}</td>
                        <td class="text-start ${data[i].percentChange > 0 ? 'text-success' : 'text-danger'}">${data[i].percentChange}</td>
                        <td class="text-start">${Math.trunc(data[i].opnInterest)}</td>
                        <td class="text-start ${data[i].netChangeOpnInterest > 0 ? 'text-success' : 'text-danger'}">${Math.trunc(data[i].netChangeOpnInterest)}</td>`;
                    }
                    $("#longUnwilling").html(str);
                }else{
                    $("#longUnwilling").html('');
                }
            }else{
                $("#longUnwilling").html('<tr><td colspan="100%">No Response Please Try Again Later</tr></td>');
                clearInterval(longWill);
            }
        });
    }
  
    
</script>
@endpush