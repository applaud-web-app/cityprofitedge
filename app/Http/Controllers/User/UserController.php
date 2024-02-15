<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Lib\GoogleAuthenticator;
use App\Models\AdminNotification;
use App\Models\Deposit;
use App\Models\FOPortfolios;
use App\Models\Form;
use App\Models\GlobalStockPortfolio;
use App\Models\Ledger;
use App\Models\MetalsPortfolio;
use App\Models\Package;
use App\Models\PortfolioTopGainer;
use App\Models\PortfolioTopLoser;
use App\Models\Referral;
use App\Models\SignalHistory;
use App\Models\StockPortfolio;
use App\Models\ThematicPortfolio;
use App\Models\BrokerApi;
use App\Models\Transaction;
use App\Models\AngleOhlcData;
use App\Models\AngleHistoricalApi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\OmsConfig;
use App\Models\OrderBook;
use App\Models\ZerodhaInstrument;
use App\Helpers\KiteConnectCls;
use App\Helpers\AngelConnectCls;
use App\Jobs\PlaceOmsOrder;
use App\Traits\AngelApiAuth;
class UserController extends Controller
{
    use AngelApiAuth;

    public function home()
    {
        $user = auth()->user();
        $pageTitle = 'Dashboard';
        
        $totalTrx = Transaction::where('user_id', $user->id)->count();
        $totalSignal = SignalHistory::where('user_id', $user->id)->count();
        $latestTrx = Transaction::where('user_id', $user->id)->orderBy('id', 'DESC')->limit(10)->get();
        $totalDeposit = Deposit::where('user_id', $user->id)->where('status', Status::PAYMENT_SUCCESS)->sum('amount');
        $portfolioTopGainers = PortfolioTopGainer::all();
        $portfolioTopLosers = PortfolioTopLoser::all();

        $date1 = date("Y-m-01");
        $date2 = date("Y-m-t");
        $stockPortFolioBuyVal = 0;
        $stockPortFolioCurrVal = 0;

        $investGraphArr = [];

        $stockPortFolio =  StockPortfolio::select(\DB::raw('SUM(quantity*buy_price) as buy_value'),\DB::raw('SUM(quantity*cmp) as current_value'),\DB::raw('DATE_FORMAT(buy_date,"%M-%Y") as buy_date'))->where('user_id',$user->id)->groupBy('buy_date')->get();

        foreach($stockPortFolio as $v){
            $investGraphArr[$v->buy_date] = [
                'buy_value'=>$v->buy_value,
                'current_value'=>$v->current_value
            ];
            $stockPortFolioBuyVal += $v->buy_value;
            $stockPortFolioCurrVal += $v->current_value;
        }

        $stockPortFolio->buy_value = $stockPortFolioBuyVal;
        $stockPortFolio->current_value = $stockPortFolioCurrVal;

        $globalstockPortFolioBuyVal = 0;
        $globalstockPortFolioCurrVal = 0;


        $globalStockPortFolio =  GlobalStockPortFolio::select(\DB::raw('SUM(quantity*buy_price) as buy_value'),\DB::raw('SUM(quantity*cmp) as current_value'),\DB::raw('DATE_FORMAT(buy_date,"%M-%Y") as buy_date'))->where('user_id',$user->id)->groupBy('buy_date')->get();
        foreach($globalStockPortFolio as $v){
            if(isset($investGraphArr[$v->buy_date])){
                $investGraphArr[$v->buy_date] = [
                    'buy_value'=>$v->buy_value + $investGraphArr[$v->buy_date]['buy_value'],
                    'current_value'=>$v->current_value + $investGraphArr[$v->buy_date]['current_value']
                ];
            }else{
                $investGraphArr[$v->buy_date] = [
                    'buy_value'=>$v->buy_value,
                    'current_value'=>$v->current_value
                ];
            }            
            $globalstockPortFolioBuyVal += $v->buy_value;
            $globalstockPortFolioCurrVal += $v->current_value;
        }
        $globalStockPortFolio->buy_value = $globalstockPortFolioBuyVal;
        $globalStockPortFolio->current_value = $globalstockPortFolioCurrVal;


        $foglobalstockPortFolioBuyVal = 0;
        $foglobalstockPortFolioCurrVal = 0;


        $foglobalStockPortFolio =  FOPortfolios::select(\DB::raw('SUM(quantity*buy_price) as buy_value'),\DB::raw('SUM(quantity*cmp) as current_value'),\DB::raw('DATE_FORMAT(buy_date,"%M-%Y") as buy_date'))->where('user_id',$user->id)->groupBy('buy_date')->get();
        foreach($foglobalStockPortFolio as $v){
            if(isset($investGraphArr[$v->buy_date])){
                $investGraphArr[$v->buy_date] = [
                    'buy_value'=>$v->buy_value + $investGraphArr[$v->buy_date]['buy_value'],
                    'current_value'=>$v->current_value + $investGraphArr[$v->buy_date]['current_value']
                ];
            }else{
                $investGraphArr[$v->buy_date] = [
                    'buy_value'=>$v->buy_value,
                    'current_value'=>$v->current_value
                ];
            }            
            $foglobalstockPortFolioBuyVal += $v->buy_value;
            $foglobalstockPortFolioCurrVal += $v->current_value;
        }


        $foglobalStockPortFolio->buy_value = $foglobalstockPortFolioBuyVal;
        $foglobalStockPortFolio->current_value = $foglobalstockPortFolioCurrVal;


        $metalsPortFolioBuyVal = 0;
        $metalsPortFolioCurrVal = 0;


        $metalsPortFolio =  MetalsPortfolio::select(\DB::raw('SUM(quantity*buy_price) as buy_value'),\DB::raw('SUM(quantity*cmp) as current_value'),\DB::raw('DATE_FORMAT(buy_date,"%M-%Y") as buy_date'))->where('user_id',$user->id)->groupBy('buy_date')->get();

        foreach($metalsPortFolio as $v){
            if(isset($investGraphArr[$v->buy_date])){
                $investGraphArr[$v->buy_date] = [
                    'buy_value'=>$v->buy_value + $investGraphArr[$v->buy_date]['buy_value'],
                    'current_value'=>$v->current_value + $investGraphArr[$v->buy_date]['current_value']
                ];
            }else{
                $investGraphArr[$v->buy_date] = [
                    'buy_value'=>$v->buy_value,
                    'current_value'=>$v->current_value
                ];
            }            
            $metalsPortFolioBuyVal += $v->buy_value;
            $metalsPortFolioCurrVal += $v->current_value;
        }

        $metalsPortFolio->buy_value = $metalsPortFolioBuyVal;
        $metalsPortFolio->current_value = $metalsPortFolioCurrVal;


        $totalInvestedAmount = $stockPortFolio->buy_value + $globalStockPortFolio->buy_value + $foglobalStockPortFolio->buy_value + $metalsPortFolio->buy_value;
        $totalCurrentAmount = $stockPortFolio->current_value + $globalStockPortFolio->current_value + $foglobalStockPortFolio->current_value + $metalsPortFolio->current_value;

        $buyArr = [];
        $currArr = [];
        $datesArr = [];

        if(!empty($investGraphArr)){
           
            $datesArr = array_keys($investGraphArr);
            $datesArr = array_map(function($kk){
                return date("M-Y",strtotime($kk));
            },$datesArr);
            $buyArr = array_column($investGraphArr,'buy_value');
            $currArr = array_column($investGraphArr,'current_value');
        }

        $chrtArr = [
            $stockPortFolio->buy_value,$metalsPortFolio->buy_value,$globalStockPortFolio->buy_value,$foglobalStockPortFolio->buy_value
        ];

        return view($this->activeTemplate . 'user.dashboard', compact('pageTitle', 'user', 'totalDeposit', 'totalTrx', 'latestTrx', 'totalSignal', 'portfolioTopGainers', 'portfolioTopLosers','stockPortFolio','globalStockPortFolio','foglobalStockPortFolio','metalsPortFolio','totalInvestedAmount','totalCurrentAmount','datesArr','buyArr','currArr','chrtArr'));
    }

    public function depositHistory(Request $request)
    {
        $pageTitle = 'Deposit History';
        $deposits = auth()->user()->deposits()->searchable(['trx'])->with(['gateway'])->orderBy('id','desc')->paginate(getPaginate());
        return view($this->activeTemplate.'user.deposit_history', compact('pageTitle', 'deposits'));
    }

    public function show2faForm()
    {
        $ga = new GoogleAuthenticator();
        $user = auth()->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . gs('site_name'), $secret);
        $pageTitle = '2FA Setting';
        return view($this->activeTemplate.'user.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user,$request->code,$request->key);
        if ($response) {
            $user->tsc = $request->key;
            $user->ts = 1;
            $user->save();
            $notify[] = ['success', 'Google authenticator activated successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }

    public function disable2fa(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $user = auth()->user();
        $response = verifyG2fa($user,$request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts = 0;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }

    public function transactions()
    {
        $pageTitle = 'Stock Transactions';
        $transactions = Transaction::where('user_id',auth()->id());
        $remarks = Transaction::where('remark', '!=', null)->distinct('remark')->orderBy('remark')->get('remark');

        $transactions = Transaction::where('user_id',auth()->id())->searchable(['trx'])->filter(['trx_type','remark'])->orderBy('id','desc')->paginate(getPaginate());
        return view($this->activeTemplate.'user.transactions', compact('pageTitle','transactions','remarks'));
    }

    public function kycForm()
    {
        if (auth()->user()->kv == 2) {
            $notify[] = ['error','Your KYC is under review'];
            return to_route('user.home')->withNotify($notify);
        }
        if (auth()->user()->kv == 1) {
            $notify[] = ['error','You are already KYC verified'];
            return to_route('user.home')->withNotify($notify);
        }
        $pageTitle = 'KYC Form';
        $form = Form::where('act','kyc')->first();
        return view($this->activeTemplate.'user.kyc.form', compact('pageTitle','form'));
    }

    public function kycData()
    {
        $user = auth()->user();
        $pageTitle = 'KYC Data';
        return view($this->activeTemplate.'user.kyc.info', compact('pageTitle','user'));
    }

    public function kycSubmit(Request $request)
    {
        $form = Form::where('act','kyc')->first();
        $formData = $form->form_data;
        $formProcessor = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData = $formProcessor->processFormData($request, $formData);
        $user = auth()->user();
        $user->kyc_data = $userData;
        $user->kv = 2;
        $user->save();

        $notify[] = ['success','KYC data submitted successfully'];
        return to_route('user.home')->withNotify($notify);

    }

    public function attachmentDownload($fileHash)
    {
        $filePath = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $general = gs();
        $title = slug($general->site_name).'- attachments.'.$extension;
        $mimetype = mime_content_type($filePath);
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    public function userData()
    {
        $user = auth()->user();
        if ($user->profile_complete == Status::YES) {
            return to_route('user.home');
        }
        $pageTitle = 'User Data';
        return view($this->activeTemplate.'user.user_data', compact('pageTitle','user'));
    }

    public function userDataSubmit(Request $request)
    {
        $user = auth()->user();
        if ($user->profile_complete == Status::YES) {
            return to_route('user.home');
        }
        $request->validate([
            'firstname'=>'required',
            'lastname'=>'required',
        ]);
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->telegram_username = $request->telegram_username;
        $user->address = [
            'country'=>@$user->address->country,
            'address'=>$request->address,
            'state'=>$request->state,
            'zip'=>$request->zip,
            'city'=>$request->city,
        ];
        $user->profile_complete = Status::YES;
        $user->save();

        $notify[] = ['success','Registration process completed successfully'];
        return to_route('user.info')->withNotify($notify);
    }

    public function purchasePackage(Request $request){

        $request->validate([
            'id' => 'required|integer'
        ]);

        $package = Package::active()->findOrFail($request->id);
        $user = auth()->user();

        if($package->price > $user->balance){
            $notify[] = ['info', 'Sorry, Insufficient balance'];
            return back()->withNotify($notify);
        }

        $user->package_id = $package->id;
        $user->validity = Carbon::now()->addDay($package->validity);
        $user->balance -= $package->price;
        $user->save();

        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->amount = $package->price;
        $transaction->post_balance = $user->balance;
        $transaction->charge = 0;
        $transaction->trx_type = '-';
        $transaction->details = 'Purchased ' .$package->name;
        $transaction->trx =  getTrx();
        $transaction->remark = 'purchase';
        $transaction->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $user->id;
        $adminNotification->title = $user->username.' has purchased '.$package->name;
        $adminNotification->click_url = urlPath('admin.report.transaction', ['search'=>$transaction->trx]);
        $adminNotification->save();

        notify($user, 'PURCHASE_COMPLETE', [
            'trx' => $transaction->trx,
            'package' => $package->name,
            'amount' => showAmount($package->price, 2),
            'post_balance' => showAmount($user->balance, 2),
            'validity' => $package->validity.' Days',
            'expired_validity' => showDateTime($user->validity),
            'purchased_at' => showDateTime($transaction->created_at),
        ]);

        $notify[] = ['success', 'You have purchased '.$package->name.' successfully'];
        return to_route('user.transactions', ['search'=>$transaction->trx])->withNotify($notify);
    }

    public function renewPackage(Request $request){

        $request->validate([
            'id' => 'required|integer'
        ]);

        $package = Package::findOrFail($request->id);

        if(!$package->status){
            $notify[] = ['info', 'Sorry, '.$package->name .' is not available to renew right now'];
            return to_route('user.home')->withNotify($notify);
        }

        $user = auth()->user();

        if($user->package_id != $package->id){
            $notify[] = ['error', 'Sorry, There is no Product to renew'];
            return back()->withNotify($notify);
        }

        if($package->price > $user->balance){
            $notify[] = ['info', 'Sorry, Insufficient balance'];
            return back()->withNotify($notify);
        }

        $user->validity = Carbon::parse($user->validity)->addDay($package->validity);
        $user->balance = $package->price;
        $user->save();

        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->amount = $package->price;
        $transaction->post_balance = $user->balance;
        $transaction->charge = 0;
        $transaction->trx_type = '-';
        $transaction->details = 'Renewed ' .$package->name;
        $transaction->trx =  getTrx();
        $transaction->remark =  'renew';
        $transaction->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $user->id;
        $adminNotification->title = $user->username.' has renewed '.$package->name;
        $adminNotification->click_url = urlPath('admin.report.transaction', ['search'=>$transaction->trx]);
        $adminNotification->save();

        notify($user, 'RENEW_COMPLETE', [
            'trx' => $transaction->trx,
            'package' => $package->name,
            'amount' => showAmount($package->price, 2),
            'post_balance' => showAmount($user->balance, 2),
            'validity' => $package->validity.' Days',
            'expired_validity' => showDateTime($user->validity),
            'renew_at' => showDateTime($transaction->created_at),
        ]);

        $notify[] = ['success', 'You have renewed '.$package->name.' successfully'];
        return to_route('user.transactions', ['search'=>$transaction->trx])->withNotify($notify);
    }

    public function signals(Request $request){
        $pageTitle = 'Signals';
        $signals  = SignalHistory::where('user_id', auth()->user()->id);

        if ($request->search) {
            $signals = $signals->whereHas('signal', function($signal) use ($request){
                $signal->where('name', 'LIKE', '%'.$request->search.'%');
            });
        }

        $signals = $signals->orderBy('id','desc')->with('signal')->paginate(getPaginate());
        return view($this->activeTemplate.'user.signals', compact('pageTitle', 'signals'));
    }

    public function referrals(){
        $user = auth()->user();
        $pageTitle = 'Referrals';
        $maxLevel  = Referral::max('level');
        return view($this->activeTemplate.'user.referrals', compact('pageTitle', 'user', 'maxLevel'));
    }

    public function ledgers()
    {
        $pageTitle = 'Ledgers';

        // TODO:: modify commented code to implement searchable and filterable.
        $ledgers = Ledger::with('poolingAccountPortfolio')->where('user_id', auth()->id())->searchable(['stock_name'])/* ->filter(['trx_type', 'remark']) */->orderBy('id', 'desc')->paginate(getPaginate());

        return view($this->activeTemplate . 'user.ledgers', compact('pageTitle', 'ledgers'));
    }

    public function stockPortfolios()
    {
        $pageTitle = 'Stock Portfolio';

        // TODO:: modify commented code to implement searchable and filterable.
        $stockPortfolios = StockPortfolio::with('poolingAccountPortfolio')->where('user_id', auth()->id())->searchable(['broker_name', 'stock_price'])/* ->filter(['trx_type', 'remark']) */->orderBy('id', 'desc')->paginate(getPaginate());

        $symbolArray = [];
        foreach ($stockPortfolios as $val) {
           array_push($symbolArray , $val['stock_name'].".NS");
        }

        return view($this->activeTemplate . 'user.stock_portfolio', compact('pageTitle', 'stockPortfolios','symbolArray'));
    }

    public function thematicPortfolios()
    {
        $pageTitle = 'Thematic Portfolios';

        // TODO:: modify commented code to implement searchable and filterable.
        $thematicPortfolios = ThematicPortfolio::searchable(['stock_name'])/* ->filter(['trx_type', 'remark']) */->orderBy('id', 'desc')->paginate(getPaginate());

        $symbolArray = [];
        foreach ($thematicPortfolios as $val) {
           array_push($symbolArray , $val['stock_name'].".NS");
        }

        return view($this->activeTemplate . 'user.thematic_portfolios', compact('pageTitle', 'thematicPortfolios','symbolArray'));
    }

    public function globalStockPortfolio()
    {
        $pageTitle = 'Global Stock Portfolio';

        // TODO:: modify commented code to implement searchable and filterable.
        $globalStockPortfolios = GlobalStockPortfolio::with('poolingAccountPortfolio')->where('user_id', auth()->id())->searchable(['broker_name', 'stock_price'])->orderBy('id', 'desc')->paginate(getPaginate());

        
        $symbolArray = [];
        foreach ($globalStockPortfolios as $val) {
        //    array_push($symbolArray , $val['stock_name'].".NS");
        array_push($symbolArray , $val['stock_name']);
        }
        // $getToken = AngelApiInstrument::select('token')->WhereIn(['symbol',$symbolArray])->get();

        // dd($getToken);
        // $date = \DB::connection('mysql_pr')->table('LTP')->select('*')->get();
        
        return view($this->activeTemplate . 'user.global_stock_portfolio', compact('pageTitle', 'globalStockPortfolios','symbolArray'));
    }

    public function foPortFolioHedging()
    {
        $pageTitle = 'FO Portfolio Hedging';

        // TODO:: modify commented code to implement searchable and filterable.
        $foPortFolioHedgings = FOPortfolios::with('poolingAccountPortfolio')->where('user_id', auth()->id())->searchable(['broker_name', 'stock_price'])/* ->filter(['trx_type', 'remark']) */->orderBy('id', 'desc')->paginate(getPaginate());
        
        $symbolArray = [];
        foreach ($foPortFolioHedgings as $val) {
           array_push($symbolArray , $val['stock_name'].".NS");
        }

        return view($this->activeTemplate . 'user.fo_portfolio_hedging', compact('pageTitle', 'foPortFolioHedgings','symbolArray'));
    }

    public function metalsPortfolio()
    {
        $pageTitle = 'Metals Portfolio';

        // TODO:: modify commented code to implement searchable and filterable.
        $metalsPortfolios = MetalsPortfolio::with('poolingAccountPortfolio')->where('user_id', auth()->id())->searchable(['broker_name', 'stock_price'])/* ->filter(['trx_type', 'remark']) */->orderBy('id', 'desc')->paginate(getPaginate());

        $symbolArray = [];
        foreach ($metalsPortfolios as $val) {
           array_push($symbolArray , $val['stock_name'].".NS");
        }

        return view($this->activeTemplate . 'user.metals_portfolio', compact('pageTitle', 'metalsPortfolios','symbolArray'));
    }

    public function portfolioTopGainers(Request $request)
    {
        $pageTitle = 'Trade Desk Signal';

        // TODO:: modify commented code to implement searchable and filterable.
        // $portfolioTopGainers = PortfolioTopGainer::searchable(['stock_name'])/* ->filter(['trx_type', 'remark']) */->orderBy('id', 'desc')->paginate(getPaginate());
        $portfolioTopGainers = [];
        $symbolArr = allTradeSymbols();
        $todayDate = date("Y-m-d");
        $stockName = $request->stock_name;
        $timeFrame = $request->time_frame ? : 5;
        return view($this->activeTemplate . 'user.portfolio_top_gainers', compact('pageTitle', 'portfolioTopGainers','symbolArr','todayDate','timeFrame','stockName'));
    }

    public function brokerDetails(){
        $data['pageTitle'] = 'Broker Details';
        $data['broker_data'] = BrokerApi::where('user_id',auth()->user()->id)->get();
        return view($this->activeTemplate . 'user.broker_details',$data);
    }

    public function orderBooks(){
        $data['pageTitle'] = 'Order Boook';
        $data['order_data'] = OrderBook::select('*')->where('user_id',auth()->user()->id)->paginate(50);
        return view($this->activeTemplate . 'user.order_books',$data);
    }

    

    public function tradePositions(Request $request){
        

        $data['pageTitle'] = 'Trade Positions';
        $broker_data = BrokerApi::where('user_id',auth()->user()->id)->get();
        $data['broker_data'] = $broker_data;
        $data['trade_book_data'] = [];
        $brokerId = 0;
        if($broker_data){
            $brokerId = !empty($request->broker_name) ? $request->broker_name : $broker_data[0]->id;
            $userData = null;
            foreach($broker_data as $val){
                if($val->id == $brokerId){
                    $userData = $val;
                }
            }
            if(!is_null($userData)){
                if($userData->client_type=='Zerodha'){
                    $params = [
                        'accountUserName'=>$userData->account_user_name,
                        'accountPassword'=>$userData->account_password,
                        'totpSecret'=>$userData->totp,
                        'apiKey'=>$userData->api_key,
                        'apiSecret'=>$userData->api_secret_key
                    ];

                    $kiteObj = new KiteConnectCls($params);
                    $kite = \Cache::remember('KITE_AUTH_'.$userData->account_user_name, 18000, function () use($kiteObj,$userData) {
                        $pythonScript = '/home/forge/cityprofitedge.com/public/kite_login/app.py -u '.$userData->account_user_name;
                        $command = 'python3 ' . $pythonScript; 
                        exec($command, $output, $exitCode);
                        $tokenArr =  explode("=",implode("\n", $output));
                        $token =  $tokenArr[1];
                        $kite = $kiteObj->generateSessionManual($token);
                        return $kite;
                    });
                    $positionData = $kite->getPositions();
                    // dd($positionData->net[0]);
                    $fDada = [];
                    if(isset($positionData->net)){
                        foreach($positionData->net as $vl){
                            $fDada[] = (object)[
                                'product_type'=>$vl->product,
                                'entry_time'=>'-',
                                'txn_type'=>'-',
                                'symbol_name'=>$vl->tradingsymbol,
                                'qty'=>$vl->quantity,
                                'entry_price'=>'-',
                                'exit_price'=>'-',
                                'sl_price'=>$vl->sell_price,
                                'profile_loss'=>'',
                            ];
                        }
                    }
                    $data['trade_book_data'] = $fDada;
                }elseif($userData->client_type=='Angel'){
                    $param = [
                        'accountUserName'=>$userData->account_user_name,
                        'apiKey'=>$userData->api_key,
                        'pin'=>$userData->security_pin,
                        'totp_secret'=>$userData->totp,
                    ];
                    $angelObj = new AngelConnectCls($param);
                    $angelTokenArr = $angelObj->generate_access_token();
                    // dd($angelTokenArr);
                    $tokenA = $angelTokenArr['token'];
                    $clientLocalIp = $angelTokenArr['clientLocalIp'];
                    $clientPublicIp = $angelTokenArr['clientPublicIp'];
                    $macAddress = $angelTokenArr['macAddress'];
                    $httpHeaders = array(
                        'X-UserType: USER',
                        'X-SourceID: WEB',
                        'X-PrivateKey: '.$userData->api_key,
                        'X-ClientLocalIP: '.$clientLocalIp,
                        'X-ClientPublicIP: '.$clientPublicIp,
                        'X-MACAddress: '.$macAddress,
                        'Content-Type: application/json',
                        'Authorization: Bearer '.$tokenA
                    );

                    dd($httpHeaders);
                    
                    $fDada = [];

                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://apiconnect.angelbroking.com/rest/secure/angelbroking/order/v1/getPosition',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                        CURLOPT_HTTPHEADER => $httpHeaders,
                    ));
                    $response = curl_exec($curl);
                    curl_close($curl);
                    $dataArr = json_decode($response);
                    // dd($dataArr);
                    if($dataArr->status==true){
                        if(!is_null($dataArr->data)){
                            foreach($dataArr->data as $vl){
                                $fDada[] = (object)[
                                    'product_type'=>$vl->producttype,
                                    'entry_time'=>'-',
                                    'txn_type'=>'-',
                                    'symbol_name'=>$vl->tradingsymbol,
                                    'qty'=>$vl->quantity,
                                    'entry_price'=>'-',
                                    'exit_price'=>'-',
                                    'sl_price'=>$vl->sellamount,
                                    'profile_loss'=>'',
                                ];
                            }
                        }
                    }
                    $data['trade_book_data'] = $fDada;
                }
            }
        }
        $data['brokerId'] = $brokerId;
        return view($this->activeTemplate . 'user.trade_positions',$data);
    }



    public function getBrokerDetails(Request $request,$id){
        $data['broker_data'] = BrokerApi::where(['user_id'=>auth()->user()->id,'id'=>$id])->first();
        return view($this->activeTemplate.'user.get_broker_details',$data);
    }



    public function portfolioTopLosers()
    {
        $pageTitle = 'Portfolio Top Losers';

        // TODO:: modify commented code to implement searchable and filterable.
        $portfolioTopLosers = PortfolioTopLoser::searchable(['stock_name'])/* filter(['trx_type', 'remark']) ->*/->orderBy('id', 'desc')->paginate(getPaginate());

        $symbolArray = [];
        foreach ($portfolioTopLosers as $val) {
           array_push($symbolArray , $val['stock_name'].".NS");
        }

        return view($this->activeTemplate . 'user.portfolio_top_losers', compact('pageTitle', 'portfolioTopLosers','symbolArray'));
    }

    public function storeBrokerDetails(Request $request){
        $brokerApi = new BrokerApi();
        $brokerApi->client_name = $request->client_name;
        $brokerApi->broker_name = $request->broker_name;
        $brokerApi->account_user_name = $request->account_user_name;
        $brokerApi->account_password = $request->account_password;
        $brokerApi->api_key = $request->api_key;
        $brokerApi->api_secret_key = $request->api_secret_key;
        $brokerApi->security_pin = $request->security_pin;
        $brokerApi->totp = $request->totp;
        $brokerApi->client_type = $request->client_type;
        $brokerApi->user_id = auth()->user()->id;
        $brokerApi->save();
        $notify[] = ['success', 'Broker Details Added Successfully...'];
        return to_route('user.portfolio.broker-details')->withNotify($notify);
    }

    public function updateBrokerDetails(Request $request,$id){
        $brokerApi = BrokerApi::find($id);
        if($brokerApi->user_id!=auth()->user()->id){
            return to_route('user.portfolio.broker-details');
        }
        $brokerApi->client_name = $request->client_name;
        $brokerApi->broker_name = $request->broker_name;
        $brokerApi->account_user_name = $request->account_user_name;
        $brokerApi->account_password = $request->account_password;
        $brokerApi->api_key = $request->api_key;
        $brokerApi->api_secret_key = $request->api_secret_key;
        $brokerApi->security_pin = $request->security_pin;
        $brokerApi->totp = $request->totp;
        $brokerApi->request_token = $request->request_token;
        $brokerApi->client_type = $request->client_type;
        $brokerApi->user_id = auth()->user()->id;
        $brokerApi->save();
        $notify[] = ['success', 'Broker Details Updated Successfully...'];
        return to_route('user.portfolio.broker-details')->withNotify($notify);
    }

    public function removeBrokerDetails(Request $request,$id){
        BrokerApi::where(['id'=>$id,'user_id'=>auth()->user()->id])->delete();
        $notify[] = ['success', 'Broker Details Deleted Successfully...'];
        return to_route('user.portfolio.broker-details')->withNotify($notify);
    }

    public function tradeBook(Request $request){
        $pageTitle = 'Trade Book';
        $data['pageTitle'] = $pageTitle;
        
        if(!empty($request->buyDate) && $request->buyDate!='all'){
            $array = explode('/' ,$request->buyDate);
            $dataFrom = $array[0];
            $dateTo = $array[1];
        }

        $Ledger = Ledger::where('user_id',auth()->user()->id);
        if(!empty($request->symbol) && $request->symbol!='all'){
            $Ledger->where('stock_name',$request->symbol);
        }
        if(empty($request->symbol) && $request->symbol!='all' && empty($request->buyDate) && $request->buyDate!='all'){
            $Ledger->whereBetween('bought_date', [Carbon::now()->subMonth(6), Carbon::now()]);
        }
        if(!empty($request->buyDate) && $request->buyDate!='all'){
            $Ledger->whereBetween('bought_date',[$dataFrom,$dateTo]);
        }
        $Ledger = $Ledger->get();

        $stock = Ledger::select('stock_name')->where('user_id',auth()->user()->id)->get();


        $currentYear = date('Y');
        $datas = Ledger::select('bought_date as date', \DB::raw('COUNT(*) as count'))->where('user_id',auth()->user()->id)->whereYear('bought_date', $currentYear)->groupBy('bought_date')->orderBy('bought_date')->get();

        // dd($datas);
        return view($this->activeTemplate . 'user.trade-book',$data,compact('Ledger','stock','datas'));
    }

    public function getStockName(){
        $Ledger = Ledger::select('stock_name')->where('user_id',auth()->user()->id)->get();
        $MetalsPortfolio = MetalsPortfolio::select('stock_name')->where('user_id',auth()->user()->id)->get();
        $FOPortfolios = FOPortfolios::select('stock_name')->where('user_id',auth()->user()->id)->get();
        $GlobalStockPortfolio = GlobalStockPortfolio::select('stock_name')->where('user_id',auth()->user()->id)->get();
        $StockPortfolio = StockPortfolio::select('stock_name')->where('user_id',auth()->user()->id)->get();

        // Merge Data
        $combinedArray = array_merge($Ledger->toArray(), $MetalsPortfolio->toArray(), $FOPortfolios->toArray(), $GlobalStockPortfolio->toArray() , $StockPortfolio->toArray());

        // Sort Data
        $dates = array_column($combinedArray, 'stock_name');
        array_multisort($dates, SORT_ASC, $combinedArray);

        return $combinedArray;

    }

    public function plReports(Request $request){

        $pageTitle = 'PL Reports';
        $data['pageTitle'] = $pageTitle;

        $segments = "all";
        $type = "all";
        $symbol = 'all';
        $buyDate = 'all';
        $array = [];  

        if(!empty($request->buyDate) && $request->buyDate!='all'){
            $array = explode('/' ,$request->buyDate);
            $dataFrom = $array[0];
            $dateTo = $array[1];
        }

        $Ledger = Ledger::select(['*', 'bought_date as buy_date'])->where('user_id',auth()->user()->id);
        if(!empty($request->symbol) && $request->symbol!='all'){
            $Ledger->where('stock_name',$request->symbol);
        }
        if(empty($request->symbol) && $request->symbol!='all' && empty($request->type) && $request->type!='all' && empty($request->symbol) && $request->symbol!='all'  && empty($request->buyDate) && $request->buyDate!='all'){
            $Ledger->whereBetween('bought_date', [Carbon::now()->subMonth(6), Carbon::now()]);
        }
        if(!empty($request->buyDate) && $request->buyDate!='all'){
            $Ledger->whereBetween('bought_date',[$dataFrom,$dateTo]);
        }
        $Ledger = $Ledger->get();


        $MetalsPortfolio = MetalsPortfolio::where('user_id',auth()->user()->id);
        if(!empty($request->symbol) && $request->symbol!='all'){
            $MetalsPortfolio->where('stock_name',$request->symbol);
        }
        if(empty($request->symbol) && $request->symbol!='all' && empty($request->type) && $request->type!='all' && empty($request->symbol) && $request->symbol!='all'  && empty($request->buyDate) && $request->buyDate!='all'){
            $MetalsPortfolio->whereBetween('buy_date', [Carbon::now()->subMonth(6), Carbon::now()]);
        }
        if(!empty($request->buyDate) && $request->buyDate!='all'){
            $MetalsPortfolio->whereBetween('buy_date',[$dataFrom,$dateTo]);
        }
        $MetalsPortfolio = $MetalsPortfolio->get();


        $FOPortfolios = FOPortfolios::where('user_id',auth()->user()->id);
        if(!empty($request->symbol) && $request->symbol!='all'){
            $FOPortfolios->where('stock_name',$request->symbol);
        }
        if(empty($request->symbol) && $request->symbol!='all' && empty($request->type) && $request->type!='all' && empty($request->symbol) && $request->symbol!='all'  && empty($request->buyDate) && $request->buyDate!='all'){
            $FOPortfolios->whereBetween('buy_date', [Carbon::now()->subMonth(6), Carbon::now()]);
        }
        if(!empty($request->buyDate) && $request->buyDate!='all'){
            $FOPortfolios->whereBetween('buy_date',[$dataFrom,$dateTo]);
        }
        $FOPortfolios = $FOPortfolios->get();


        $GlobalStockPortfolio = GlobalStockPortfolio::where('user_id',auth()->user()->id);
        if(!empty($request->symbol) && $request->symbol!='all'){
            $GlobalStockPortfolio->where('stock_name',$request->symbol);
        }
        if(empty($request->symbol) && $request->symbol!='all' && empty($request->type) && $request->type!='all' && empty($request->symbol) && $request->symbol!='all'  && empty($request->buyDate) && $request->buyDate!='all'){
            $GlobalStockPortfolio->whereBetween('buy_date', [Carbon::now()->subMonth(6), Carbon::now()]);
        }
        if(!empty($request->buyDate) && $request->buyDate!='all'){
            $GlobalStockPortfolio->whereBetween('buy_date',[$dataFrom,$dateTo]);
        }
        $GlobalStockPortfolio = $GlobalStockPortfolio->get();


        $StockPortfolio = StockPortfolio::where('user_id',auth()->user()->id);
        if(!empty($request->symbol) && $request->symbol!='all'){
            $StockPortfolio->where('stock_name',$request->symbol);
        }
        if(empty($request->symbol) && $request->symbol!='all' && empty($request->type) && $request->type!='all' && empty($request->symbol) && $request->symbol!='all'  && empty($request->buyDate) && $request->buyDate!='all'){
            $StockPortfolio->whereBetween('buy_date', [Carbon::now()->subMonth(6), Carbon::now()]);
        }
        if(!empty($request->buyDate) && $request->buyDate!='all'){
            $StockPortfolio->whereBetween('buy_date',[$dataFrom,$dateTo]);
        }
        $StockPortfolio = $StockPortfolio->get();

        // Type = Realised (Lagyer) , Unrelized = (Except Lagyer)
        if(!empty($request->type) && $request->type!='all'){
            if($request->type == "unrealized"){
                if(!empty($request->segments) && $request->segments!='all'){
                    if($request->segments == "global"){
                        $combinedArray = array_merge($GlobalStockPortfolio->toArray());
                    }else if($request->segments == "fQ"){
                        $combinedArray = array_merge($FOPortfolios->toArray());
                    }else if($request->segments == "metals"){
                        $combinedArray = array_merge($MetalsPortfolio->toArray());
                    }else if($request->segments == "stock"){
                        $combinedArray = array_merge($StockPortfolio->toArray());
                    }
                }else{
                    $combinedArray = array_merge($MetalsPortfolio->toArray(), $FOPortfolios->toArray(), $GlobalStockPortfolio->toArray() , $StockPortfolio->toArray());
                }
            }else{
                // Merge All Data
                if($request->segments=='all'){
                  $combinedArray = array_merge($Ledger->toArray());
                }else{
                    $combinedArray = [];
                }

            }
        }else{
            // Merge All Data
            if(!empty($request->segments) && $request->segments!='all'){
                if($request->segments == "global"){
                    $combinedArray = array_merge($GlobalStockPortfolio->toArray());
                }else if($request->segments == "fQ"){
                    $combinedArray = array_merge($FOPortfolios->toArray());
                }else if($request->segments == "metals"){
                    $combinedArray = array_merge($MetalsPortfolio->toArray());
                }else if($request->segments == "stock"){
                    $combinedArray = array_merge($StockPortfolio->toArray());
                }
            }else{
                $combinedArray = array_merge($Ledger->toArray(), $MetalsPortfolio->toArray(), $FOPortfolios->toArray(), $GlobalStockPortfolio->toArray() , $StockPortfolio->toArray());
            }
        }

        // Sort Data
        $dates = array_column($combinedArray, 'buy_date');
        array_multisort($dates, SORT_ASC, $combinedArray);

        $allData = $this->getStockName();


        return view($this->activeTemplate . 'user.pl-reports',$data,compact('combinedArray','allData'));
    }

    public function omsConfig(){
        $pageTitle = 'OMS CONFIG';
        $data['pageTitle'] = $pageTitle;
        $brokers = BrokerApi::select('client_name','id')->where('user_id',auth()->user()->id)->get();
        $data['brokers'] = $brokers;
        $data['omsData'] = OmsConfig::where('user_id',auth()->user()->id)->with('broker:id,client_name')->paginate(50);
        return view($this->activeTemplate . 'user.oms-config',$data);
    }

    public function getPeCeSymbolNames(Request $request){
        $symbol = $request->symbol;
        $signal = $request->signal;
       

        $todayDate = date("Y-m-d");
        // $todayDate = date("2024-01-20");
        $data = \DB::connection('mysql_rm')->table($symbol)->select('*')->where(['date'=>$todayDate,'timeframe'=>$signal])->get(); 
        
        $atmData = [];
        foreach($data as $vvl){
            if(isset($vvl->atm) && ($vvl->atm=="ATM" || $vvl->atm=="ATM-1" || $vvl->atm=="ATM+1")){
                $atmData[] = $vvl;
            }
        }

        $fData = [];
        foreach($atmData as $val){
            $arrData = json_decode($val->data,true);   
            $CE = array_unique($arrData['CE']);
            $PE = $arrData['PE'];
            foreach ($CE as $k=>$item){
                $fData[] = [
                    'ce'=>$item,
                    'pe'=>$PE[$k]
                ];
            }
        }

        return response()->json(['s'=>1,'data'=>$fData]);
   
    }

    public function checkTradingSymbolExists($symbol){
        if(!empty($symbol)){
            $cnt = ZerodhaInstrument::where('trading_symbol',$symbol)->count();
            if($cnt){
                return true;
            }
            return false;
        }
        return true;
    }

    public function storeOmsConfig(Request $request){
        $txnType = '';

        // ce symbol
        if(!$this->checkTradingSymbolExists($request->ce_symbol_name)){
            $notify[] = ['error', 'Enter valid trading symbol'];
            return to_route('user.portfolio.oms-config')->withNotify($notify);
        }
        if(!$this->checkTradingSymbolExists($request->pe_symbol_name)){
            $notify[] = ['error', 'Enter valid trading symbol'];
            return to_route('user.portfolio.oms-config')->withNotify($notify);
        }

        $ce_symbol_name = $request->ce_symbol_name;
        $pe_symbol_name = $request->pe_symbol_name;

        $strategyName = $request->strategy_name;

        switch($request->strategy_name){
            case 'Short Straddle':
                $txnType = 'SELL';
            break;
            case 'Long Straddle':
                $txnType = 'BUY';
            break;
            case 'Buy CE':
                $txnType = 'BUY';
                $pe_symbol_name = null;
            break;
            case 'Buy PE':
                $txnType = 'BUY';
                $ce_symbol_name = null;
            break;
            case 'Sell CE':
                $txnType = 'SELL';
                $pe_symbol_name = null;
            break;
            case 'Sell PE':
                $txnType = 'SELL';
                $ce_symbol_name = null;
            break;
            case 'Bullish CE':
                $txnType = 'BUY';
                $pe_symbol_name = null;
                $strategyName = 'Bullish';
            break;
            case 'Bullish PE':
                $txnType = 'BUY';
                $ce_symbol_name = null;
                $strategyName = 'Bullish';
            break;
            case 'Bearish CE':
                $txnType = 'SELL';
                $pe_symbol_name = null;
                $strategyName = 'Bearish';
            break;
            case 'Bearish PE':
                $txnType = 'SELL';
                $ce_symbol_name = null;
                $strategyName = 'Bearish';
            break;
        }

        $ce_pyramid_1 = null;
        $ce_pyramid_2 = null;
        $ce_pyramid_3 = null;
        $pe_pyramid_1 = null;
        $pe_pyramid_2 = null;
        $pe_pyramid_3 = null;

        // if($request->order_type=="LIMIT"){
            $ce_quantity = $request->ce_quantity > 0 ? $request->ce_quantity : 0;
            $numbertodivise = $ce_quantity;
            $no=1;
            if($request->pyramid_percent==33){
                $no = 3;
            }elseif($request->pyramid_percent==50){
                $no = 2;
            }
            $pData = calculatePyramids($numbertodivise,$no);
            if($no==3){
                $ce_pyramid_1 = $pData[0];
                $ce_pyramid_2 = $pData[1];
                $ce_pyramid_3 = $pData[2];
            }
            if($no==2){
                $ce_pyramid_1 = $pData[0];
                $ce_pyramid_2 = $pData[1];
            }
            if($no==1){
                $ce_pyramid_1 = $pData[0];
            }
            //
            $pe_quantity = $request->pe_quantity >0 ? $request->pe_quantity : 0;
            $numbertodivise = $pe_quantity;
            $pData = calculatePyramids($numbertodivise,$no);
            if($no==3){
                $pe_pyramid_1 = $pData[0];
                $pe_pyramid_2 = $pData[1];
                $pe_pyramid_3 = $pData[2];
            }
            if($no==2){
                $pe_pyramid_1 = $pData[0];
                $pe_pyramid_2 = $pData[1];
            }
            if($no==1){
                $pe_pyramid_1 = $pData[0];
            }
        // }

        $omsObj = new OmsConfig();
        $omsObj->symbol_name = $request->symbol_name;
        $omsObj->signal_tf = $request->signal_tf;
        $omsObj->ce_symbol_name = $ce_symbol_name;
        $omsObj->pe_symbol_name = $pe_symbol_name;
        $omsObj->broker_api_id = $request->client_name;
        $omsObj->entry_point = $request->entry_point;
        $omsObj->strategy_name = $strategyName;
        $omsObj->product = $request->product;
        $omsObj->order_type = $request->order_type;
        $omsObj->pyramid_percent = $request->pyramid_percent;
        $omsObj->ce_pyramid_1 = $ce_pyramid_1 > 0 ? $ce_pyramid_1 : null;
        $omsObj->ce_pyramid_2 = $ce_pyramid_2 > 0 ? $ce_pyramid_2 : null;
        $omsObj->ce_pyramid_3 = $ce_pyramid_3 > 0 ? $ce_pyramid_3 : null;
        $omsObj->pe_pyramid_1 = $pe_pyramid_1 > 0 ? $pe_pyramid_1 : null;
        $omsObj->pe_pyramid_2 = $pe_pyramid_2 > 0 ? $pe_pyramid_2 : null;
        $omsObj->pe_pyramid_3 = $pe_pyramid_3 > 0 ? $pe_pyramid_3 : null;
        $omsObj->txn_type = $txnType;
        $omsObj->ce_quantity = $request->ce_quantity;
        $omsObj->pe_quantity = $request->pe_quantity;
        $omsObj->pyramid_freq = $request->pyramid_freq;
        $omsObj->exit_1_qty = $request->exit_1_qty;
        $omsObj->exit_1_target = $request->exit_1_target;
        $omsObj->exit_2_qty = $request->exit_2_qty;
        $omsObj->exit_2_target = $request->exit_2_target;
        $omsObj->user_id = auth()->user()->id;
        $omsObj->status = $request->status;
        $omsObj->cron_run_at = date("Y-m-d H:i:s",strtotime('-'.$request->pyramid_freq.' minutes'));
        $omsObj->save();
        
        $notify[] = ['success', 'Data added Successfully...'];
        return to_route('user.portfolio.oms-config')->withNotify($notify);
    }

    public function getOmgConfigData(Request $request){
        $id = $request->id;

        $brokers = BrokerApi::select('client_name','id')->where('user_id',auth()->user()->id)->get();
        $data['brokers'] = $brokers;
        $data['omgData'] = OmsConfig::where(['id'=>$id,'user_id'=>auth()->user()->id])->first();
        $symbol = $data['omgData']->symbol_name;
        $signal = $data['omgData']->signal_tf;
        $todayDate = date("Y-m-d");
        $Symdata = \DB::connection('mysql_rm')->table($symbol)->select('*')->where(['date'=>$todayDate,'timeframe'=>$signal])->get(); 
        $atmData = [];
        foreach($Symdata as $vvl){
            if(isset($vvl->atm) && ($vvl->atm=="ATM" || $vvl->atm=="ATM-1" || $vvl->atm=="ATM+1")){
                $atmData[] = $vvl;
            }
        }
        $fData = [];
        foreach($atmData as $val){
            $arrData = json_decode($val->data,true);   
            $CE = array_unique($arrData['CE']);
            $PE = $arrData['PE'];
            foreach ($CE as $k=>$item){
                $fData[] = [
                    'ce'=>$item,
                    'pe'=>$PE[$k]
                ];
            }
        }
        $data['fData'] = $fData;

        return view($this->activeTemplate . 'user.get-omg-config-data',$data);
    }

    public function removeOmsConfig(Request $request){
        $id = $request->id;
        OmsConfig::where(['id'=>$id,'user_id'=>auth()->user()->id])->delete();
        $notify[] = ['success', 'Data removed Successfully...'];
        return to_route('user.portfolio.oms-config')->withNotify($notify);
    }

    public function OptionAnalysis(Request $request){
        $pageTitle = 'Option Analysis';
        $symbolArr = allTradeSymbols();
        // For Chart 1
        $Atmtype1 = $request->atmRange1 ?? "ATM";
        $table1 =  $request->symbol1 ?? "CRUDEOIL";
        $timeFrame1 = $request->timeframe1 ? : 5;
        // For Chart 2
        $Atmtype2 = $request->atmRange2 ?? "ATM";
        $table2 =  $request->symbol2 ?? "CRUDEOIL";
        $timeFrame2 = $request->timeframe2 ? : 5;
        // For Chart 3
        $Atmtype3 = $request->atmRange3 ?? "ATM";
        $table3 =  $request->symbol3 ?? "CRUDEOIL";
        $timeFrame3 = $request->timeframe3 ? : 5;

        // For Chart 4
        $Atmtype4 = $request->atmRange4 ?? "ATM";
        $table4 =  $request->symbol4 ?? "CRUDEOIL";
        $timeFrame4 = $request->timeframe4 ? : 5;

        // For Chart 5
        $Atmtype5 = $request->atmRange5 ?? "ATM";
        $table5 =  $request->symbol5 ?? "CRUDEOIL";
        $timeFrame5 = $request->timeframe5 ? : 5;
        
        // For Chart 1
        $data1 = \DB::connection('mysql_rm')->table($table1)->select('*')->where('timeframe',$timeFrame1)->get();
        // For Chart 2
        $data2 = \DB::connection('mysql_rm')->table($table2)->select('*')->where('timeframe',$timeFrame2)->get();
        // For Chart 3
        $data3 = \DB::connection('mysql_rm')->table($table3)->select('*')->where('timeframe',$timeFrame3)->get();
        // For Chart 4
        $data4 = \DB::connection('mysql_rm')->table($table4)->select('*')->where('timeframe',$timeFrame4)->get();
        // For Chart 5
        $data5 = \DB::connection('mysql_rm')->table($table5)->select('*')->where('timeframe',$timeFrame5)->get();

        return view($this->activeTemplate . 'user.option-analysis', compact('pageTitle','symbolArr','data1','data2','data3','data4','data5','Atmtype1','timeFrame1','table1','Atmtype2','timeFrame2','table2','Atmtype3','timeFrame3','table3','Atmtype4','timeFrame4','table4','Atmtype5','timeFrame5','table5'));
    }

    public function fetchTradeRecord(){
        // try {
            return response()->json($this->getTradeDeskData());
        // } catch (\Throwable $th) {
        //    return response()->json(
        //     [ 'data' => ['status'=>false] ]
        //    );
        // }
    }

    // public function calculateSuperTrendWithStrength($high, $low, $close,$period, $index, $multiplier = 3){
    //     $basicUpperBand = ($high[$period - 1] + $low[$period - 1]) / 2;
    //     $basicLowerBand = ($high[$period - 1] + $low[$period - 1]) / 2;
    //     $finalUpperBand = 0;
    //     $finalLowerBand = 0;
    //     $trend = "";
    //     $strength = "";
        
    //     // Calculate ATR
    //     $atr = [];
    //     $atr[0] = 0;
    //     for ($i = 1; $i < count($close); $i++) {
    //         $tr1 = max($high[$i] - $low[$i], abs($high[$i] - $close[$i - 1]), abs($low[$i] - $close[$i - 1]));
    //         $atr[$i] = ($atr[$i - 1] * ($period - 1) + $tr1) / $period;
    //     }
    
    //     // Calculate Super Trend
    //     // for ($i = $period; $i < count($close); $i++) {
    //         if(count($close) > $index){
    //             $basicUpperBand = ($high[$index] + $low[$index]) / 2 + $multiplier * $atr[$index];
    //             $basicLowerBand = ($high[$index] + $low[$index]) / 2 - $multiplier * $atr[$index];
                
    //             if ($basicUpperBand < $finalUpperBand || $close[$index - 1] > $finalUpperBand) {
    //                 $finalUpperBand = $basicUpperBand;
    //             } else {
    //                 $finalUpperBand = $finalUpperBand;
    //             }
                
    //             if ($basicLowerBand > $finalLowerBand || $close[$index - 1] < $finalLowerBand) {
    //                 $finalLowerBand = $basicLowerBand;
    //             } else {
    //                 $finalLowerBand = $finalLowerBand;
    //             }
        
    //             if ($close[$index] <= $finalUpperBand) {
    //                 $trend = 'Bullish';
    //                 $strength = ($finalUpperBand - $close[$index]) / $atr[$index];
    //             } elseif ($close[$index] >= $finalLowerBand) {
    //                 $trend = 'Bearish';
    //                 $strength = ($close[$index] - $finalLowerBand) / $atr[$index];
    //             }
    //         }
            
    //     // } 
    
    //     return ['trend' => $trend, 'strength' => $strength];
    // }

    // public function calculateHeikinAshi(){
    //     set_time_limit(0);
    //     $previousClose = null;
    //     $period = 21;
    //     $multiplier = 3;
    //     $highArr = array();
    //     $lowArr = array();
    //     $closeArr = array();
    //     $historicalData = AngleHistoricalApi::get()->toArray();

    //     foreach ($historicalData as $key => $historical) {
    //         // dd($historical['timestamp'])->format('');
         
    //         $open = ($historical['open'] + $historical['close']) / 2;
    //         $close = ($historical['open'] + $historical['high'] + $historical['low'] + $historical['close']) / 4;

    //         if ($previousClose !== null) {
    //             $high = max($historical['high'], $open, $close);
    //             $low = min($historical['low'], $open, $close);
    //         } else {
    //             $high = $historical['high'];
    //             $low = $historical['low'];
    //         }           

    //         array_push($highArr,$high);
    //         array_push($lowArr,$low);
    //         array_push($closeArr,$close);
    //         $newData = new AngleOhlcData;
    //         $newData->historical_id = $historical['id'];
    //         $newData->date = $historical['timestamp'];
    //         $newData->new_open = $open;
    //         $newData->new_high = $high;
    //         $newData->new_low = $low;
    //         $newData->new_close = $close;
           
    //         $totalRecord = AngleOhlcData::count();
    //         if($totalRecord >= $period){
    //             $val = $this->calculateSuperTrendWithStrength($highArr,$lowArr,$closeArr,$period,$key-1);
    //             $newData->trend = $val['trend'];
    //             $newData->strength = $val['strength'];
    //         }

    //         $newData->save();
    //         $previousClose = $close;

    //        if($key % 100 == 0){
    //             sleep(4);
    //        }
    //     }

    //     return "Completed";die;

    // }

    // function calculateSuperTrendWithStrength($high, $low, $close, $multiplier = 3, $period = 21) {
    //     $basicUpperBand = ($high[$period - 1] + $low[$period - 1]) / 2;
    //     $basicLowerBand = ($high[$period - 1] + $low[$period - 1]) / 2;
    //     $finalUpperBand = 0;
    //     $finalLowerBand = 0;
    //     $trend = [];
    //     $strength = [];
        
    //     // Calculate ATR
    //     $atr = [];
    //     $atr[0] = 0;
    //     for ($i = 1; $i < count($close); $i++) {
    //         $tr1 = max($high[$i] - $low[$i], abs($high[$i] - $close[$i - 1]), abs($low[$i] - $close[$i - 1]));
    //         $atr[$i] = ($atr[$i - 1] * ($period - 1) + $tr1) / $period;
    //     }
    
    //     // Calculate Super Trend
    //     for ($i = $period; $i < count($close); $i++) {
    //         $basicUpperBand = ($high[$i] + $low[$i]) / 2 + $multiplier * $atr[$i];
    //         $basicLowerBand = ($high[$i] + $low[$i]) / 2 - $multiplier * $atr[$i];
            
    //         if ($basicUpperBand < $finalUpperBand or $close[$i - 1] > $finalUpperBand) {
    //             $finalUpperBand = $basicUpperBand;
    //         } else {
    //             $finalUpperBand = $finalUpperBand;
    //         }
            
    //         if ($basicLowerBand > $finalLowerBand or $close[$i - 1] < $finalLowerBand) {
    //             $finalLowerBand = $basicLowerBand;
    //         } else {
    //             $finalLowerBand = $finalLowerBand;
    //         }
    
    //         if ($close[$i] <= $finalUpperBand) {
    //             $trend[] = 'Bullish';
    //             $strength[] = ($finalUpperBand - $close[$i]) / $atr[$i];
    //         } elseif ($close[$i] >= $finalLowerBand) {
    //             $trend[] = 'Bearish';
    //             $strength[] = ($close[$i] - $finalLowerBand) / $atr[$i];
    //         }
    //     }
    
    //     return ['trend' => $trend, 'strength' => $strength];
    // }

    // Get New high,low,close,etc
    public function getNewData($ohlcData){
        $heikinAshiData = [];
        $previousClose = null;
    
        foreach ($ohlcData as $ohlc) {
            $open = ($ohlc['open'] + $ohlc['close']) / 2;
            $close = ($ohlc['open'] + $ohlc['high'] + $ohlc['low'] + $ohlc['close']) / 4;
    
            if ($previousClose !== null) {
                $high = max($ohlc['high'], $open, $close);
                $low = min($ohlc['low'], $open, $close);
            } else {
                $high = $ohlc['high'];
                $low = $ohlc['low'];
            }
    
            $heikinAshiData[] = [
                'date' => $ohlc['timestamp'],
                'open' => $open,
                'high' => $high,
                'low' => $low,
                'close' => $close
            ];
    
            $previousClose = $close;
        }
    
        return $heikinAshiData;
    }

    public function calculateSuperTrendWithStrength2($high, $low, $close, $multiplier = 3, $period = 21){
        $basicUpperBand = ($high[$period - 1] + $low[$period - 1]) / 2;
        $basicLowerBand = ($high[$period - 1] + $low[$period - 1]) / 2;
        $finalUpperBand = 0;
        $finalLowerBand = 0;
        $trend = [];
        $strength = [];
        
        // Calculate ATR
        $atr = [];
        $atr[0] = 0;
        for ($i = 1; $i < count($close); $i++) {
            $tr1 = max($high[$i] - $low[$i], abs($high[$i] - $close[$i - 1]), abs($low[$i] - $close[$i - 1]));
            $atr[$i] = ($atr[$i - 1] * ($period - 1) + $tr1) / $period;
        }
    
        // Calculate Super Trend
        for ($i = $period; $i < count($close); $i++) {
            $basicUpperBand = ($high[$i] + $low[$i]) / 2 + $multiplier * $atr[$i];
            $basicLowerBand = ($high[$i] + $low[$i]) / 2 - $multiplier * $atr[$i];
            
            if ($basicUpperBand < $finalUpperBand or $close[$i - 1] > $finalUpperBand) {
                $finalUpperBand = $basicUpperBand;
            } else {
                $finalUpperBand = $finalUpperBand;
            }
            
            if ($basicLowerBand > $finalLowerBand or $close[$i - 1] < $finalLowerBand) {
                $finalLowerBand = $basicLowerBand;
            } else {
                $finalLowerBand = $finalLowerBand;
            }
    
            if ($close[$i] <= $finalUpperBand) {
                $trend[$i] = 'Bullish';
                $strength[$i] = ($finalUpperBand - $close[$i]) / $atr[$i];
            } elseif ($close[$i] >= $finalLowerBand) {
                $trend[$i] = 'Bearish';
                $strength[$i] = ($close[$i] - $finalLowerBand) / $atr[$i];
            }
        }
    
        return ['trend' => $trend, 'strength' => $strength];
    }


    public function storenewData(){
        set_time_limit(0);
        $historicalData = AngleHistoricalApi::get()->toArray();
        $newData = $this->getNewData($historicalData);

        $high = array_map(function($val){
            return $val['high'];
        },$newData);

        $low = array_map(function($val){
            return $val['low'];
        },$newData);

        $close = array_map(function($val){
            return $val['close'];
        },$newData);

        $open = array_map(function($val){
            return $val['open'];
        },$newData);
        $return =  $this->calculateSuperTrendWithStrength2($high,$low,$close);

        foreach ($historicalData as $key => $value) {
            $ohlcNew = new AngleOhlcData;
            $ohlcNew->historical_id = $value['id'];
            $ohlcNew->symbol = $value['symbol'];
            $ohlcNew->date = $value['timestamp'];
            $ohlcNew->new_open = $open[$key];
            $ohlcNew->new_high = $high[$key];
            $ohlcNew->new_low = $low[$key];
            $ohlcNew->new_close = $close[$key];
            $ohlcNew->trend = $return['trend'][$key] ?? null;  
            $ohlcNew->strength = number_format($return['strength'][$key],2,",",".") ?? null; 
            $ohlcNew->save();

            if($key % 100 == 0){
                sleep(4);
            }
        }
        dd('Completed');die;
    }

    public function updateOmsConfig(Request $request){
       
        $txnType = '';

        // ce symbol
        if(!$this->checkTradingSymbolExists($request->ce_symbol_name_up)){
            $notify[] = ['error', 'Enter valid trading symbol'];
            return to_route('user.portfolio.oms-config')->withNotify($notify);
        }
        if(!$this->checkTradingSymbolExists($request->pe_symbol_name_up)){
            $notify[] = ['error', 'Enter valid trading symbol'];
            return to_route('user.portfolio.oms-config')->withNotify($notify);
        }

        $ce_symbol_name = $request->ce_symbol_name_up;
        $pe_symbol_name = $request->pe_symbol_name_up;

        $strategyName = $request->strategy_name_up;

        switch($request->strategy_name_up){
            case 'Short Straddle':
                $txnType = 'SELL';
            break;
            case 'Long Straddle':
                $txnType = 'BUY';
            break;
            case 'Buy CE':
                $txnType = 'BUY';
                $pe_symbol_name = null;
            break;
            case 'Buy PE':
                $txnType = 'BUY';
                $ce_symbol_name = null;
            break;
            case 'Sell CE':
                $txnType = 'SELL';
                $pe_symbol_name = null;
            break;
            case 'Sell PE':
                $txnType = 'SELL';
                $ce_symbol_name = null;
            break;
            case 'Bullish CE':
                $txnType = 'BUY';
                $pe_symbol_name = null;
                $strategyName = 'Bullish';
            break;
            case 'Bullish PE':
                $txnType = 'BUY';
                $ce_symbol_name = null;
                $strategyName = 'Bullish';
            break;
            case 'Bearish CE':
                $txnType = 'SELL';
                $pe_symbol_name = null;
                $strategyName = 'Bearish';
            break;
            case 'Bearish PE':
                $txnType = 'SELL';
                $ce_symbol_name = null;
                $strategyName = 'Bearish';
            break;
        }

        $ce_pyramid_1 = null;
        $ce_pyramid_2 = null;
        $ce_pyramid_3 = null;
        $pe_pyramid_1 = null;
        $pe_pyramid_2 = null;
        $pe_pyramid_3 = null;

        // if($request->order_type=="LIMIT"){
            $ce_quantity = $request->ce_quantity_up > 0 ? $request->ce_quantity_up : 0;
            $numbertodivise = $ce_quantity;
            $no=1;
            if($request->pyramid_percent_up==33){
                $no = 3;
            }elseif($request->pyramid_percent_up==50){
                $no = 2;
            }
            $pData = calculatePyramids($numbertodivise,$no);
            if($no==3){
                $ce_pyramid_1 = $pData[0];
                $ce_pyramid_2 = $pData[1];
                $ce_pyramid_3 = $pData[2];
            }
            if($no==2){
                $ce_pyramid_1 = $pData[0];
                $ce_pyramid_2 = $pData[1];
            }
            if($no==1){
                $ce_pyramid_1 = $pData[0];
            }
            //
            $pe_quantity = $request->pe_quantity_up >0 ? $request->pe_quantity_up : 0;
            $numbertodivise = $pe_quantity;
            $pData = calculatePyramids($numbertodivise,$no);
            if($no==3){
                $pe_pyramid_1 = $pData[0];
                $pe_pyramid_2 = $pData[1];
                $pe_pyramid_3 = $pData[2];
            }
            if($no==2){
                $pe_pyramid_1 = $pData[0];
                $pe_pyramid_2 = $pData[1];
            }
            if($no==1){
                $pe_pyramid_1 = $pData[0];
            }
        // }

        $omsObj = OmsConfig::find($request->id);
        $omsObj->symbol_name = $request->symbol_name_up;
        $omsObj->signal_tf = $request->signal_tf_up;
        $omsObj->ce_symbol_name = $ce_symbol_name;
        $omsObj->pe_symbol_name = $pe_symbol_name;
        $omsObj->broker_api_id = $request->client_name_up;
        $omsObj->entry_point = $request->entry_point_up;
        $omsObj->strategy_name = $strategyName;
        $omsObj->product = $request->product_up;
        $omsObj->order_type = $request->order_type_up;
        $omsObj->pyramid_percent = $request->pyramid_percent_up;
        $omsObj->ce_pyramid_1 = $ce_pyramid_1 > 0 ? $ce_pyramid_1 : null;
        $omsObj->ce_pyramid_2 = $ce_pyramid_2 > 0 ? $ce_pyramid_2 : null;
        $omsObj->ce_pyramid_3 = $ce_pyramid_3 > 0 ? $ce_pyramid_3 : null;
        $omsObj->pe_pyramid_1 = $pe_pyramid_1 > 0 ? $pe_pyramid_1 : null;
        $omsObj->pe_pyramid_2 = $pe_pyramid_2 > 0 ? $pe_pyramid_2 : null;
        $omsObj->pe_pyramid_3 = $pe_pyramid_3 > 0 ? $pe_pyramid_3 : null;
        $omsObj->txn_type = $txnType;
        $omsObj->ce_quantity = $request->ce_quantity_up;
        $omsObj->pe_quantity = $request->pe_quantity_up;
        $omsObj->pyramid_freq = $request->pyramid_freq_up;
        $omsObj->user_id = auth()->user()->id;
        $omsObj->status = $request->status;
        $omsObj->is_api_pushed = 0;
        $omsObj->last_time = null;
        $omsObj->cron_run_at = date("Y-m-d H:i:s",strtotime('-'.$request->pyramid_freq_up.' minutes'));
        $omsObj->save();
        
        $notify[] = ['success', 'Data updated Successfully...'];
        return to_route('user.portfolio.oms-config')->withNotify($notify);
    }
    
}
