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
use Carbon\Carbon;
use Illuminate\Http\Request;
class UserController extends Controller
{
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
            $notify[] = ['error', 'Sorry, There is no package to renew'];
            return back()->withNotify($notify);
        }

        if($package->price > $user->balance){
            $notify[] = ['info', 'Sorry, Insufficient balance'];
            return back()->withNotify($notify);
        }

        $user->validity = Carbon::parse($user->validity)->addDay($package->validity);
        $user->balance -= $package->price;
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
        return view($this->activeTemplate . 'user.stock_portfolio', compact('pageTitle', 'stockPortfolios'));
    }

    public function thematicPortfolios()
    {
        $pageTitle = 'Thematic Portfolios';

        // TODO:: modify commented code to implement searchable and filterable.
        $thematicPortfolios = ThematicPortfolio::searchable(['stock_name'])/* ->filter(['trx_type', 'remark']) */->orderBy('id', 'desc')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.thematic_portfolios', compact('pageTitle', 'thematicPortfolios'));
    }

    public function globalStockPortfolio()
    {
        $pageTitle = 'Global Stock Portfolio';

        // TODO:: modify commented code to implement searchable and filterable.
        $globalStockPortfolios = GlobalStockPortfolio::with('poolingAccountPortfolio')->where('user_id', auth()->id())->searchable(['broker_name', 'stock_price'])/* ->filter(['trx_type', 'remark']) */->orderBy('id', 'desc')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.global_stock_portfolio', compact('pageTitle', 'globalStockPortfolios'));
    }

    public function foPortFolioHedging()
    {
        $pageTitle = 'FO Portfolio Hedging';

        // TODO:: modify commented code to implement searchable and filterable.
        $foPortFolioHedgings = FOPortfolios::with('poolingAccountPortfolio')->where('user_id', auth()->id())->searchable(['broker_name', 'stock_price'])/* ->filter(['trx_type', 'remark']) */->orderBy('id', 'desc')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.fo_portfolio_hedging', compact('pageTitle', 'foPortFolioHedgings'));
    }

    public function metalsPortfolio()
    {
        $pageTitle = 'Metals Portfolio';

        // TODO:: modify commented code to implement searchable and filterable.
        $metalsPortfolios = MetalsPortfolio::with('poolingAccountPortfolio')->where('user_id', auth()->id())->searchable(['broker_name', 'stock_price'])/* ->filter(['trx_type', 'remark']) */->orderBy('id', 'desc')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.metals_portfolio', compact('pageTitle', 'metalsPortfolios'));
    }

    public function portfolioTopGainers(Request $request)
    {
        $pageTitle = 'Trade Desk Signal';

        // TODO:: modify commented code to implement searchable and filterable.
        // $portfolioTopGainers = PortfolioTopGainer::searchable(['stock_name'])/* ->filter(['trx_type', 'remark']) */->orderBy('id', 'desc')->paginate(getPaginate());
        $portfolioTopGainers = [];
        $symbolArr = allTradeSymbols();
        $todayDate = date("Y-m-d");
        $timeFrame = $request->time_frame ?: 5;
        return view($this->activeTemplate . 'user.portfolio_top_gainers', compact('pageTitle', 'portfolioTopGainers','symbolArr','todayDate','timeFrame'));
    }

    public function brokerDetails(){
        $data['pageTitle'] = 'Broker Details';
        $data['broker_data'] = BrokerApi::where('user_id',auth()->user()->id)->get();
        return view($this->activeTemplate . 'user.broker_details',$data);
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
        return view($this->activeTemplate . 'user.portfolio_top_losers', compact('pageTitle', 'portfolioTopLosers'));
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

    public function tradeBook(){
        $pageTitle = 'Trade Book';
        $data['pageTitle'] = $pageTitle;
        return view($this->activeTemplate . 'user.trade-book',$data);
    }

    public function plReports(){
        $pageTitle = 'PL Reports';
        $data['pageTitle'] = $pageTitle;
        return view($this->activeTemplate . 'user.pl-reports',$data);
    }
}
