<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\CurlRequest;
use App\Models\AdminNotification;
use App\Models\Deposit;
use App\Models\Package;
use App\Models\Signal;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserLogin;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Constants\Status;
use App\Models\GlobalStockPortfolio;
use App\Models\StockPortfolio;
use App\Models\ThematicPortfolio;
use App\Models\MetalsPortfolio;
use App\Models\FOPortfolios;

class AdminController extends Controller
{

    public function dashboard()
    {

        $pageTitle = 'Dashboard';

        // User Info
        $widget['total_users']             = User::count();
        $widget['verified_users']          = User::active()->count();
        $widget['email_unverified_users']  = User::emailUnverified()->count();
        $widget['mobile_unverified_users'] = User::mobileUnverified()->count();


        // user Browsing, Country, Operating Log
        $userLoginData = UserLogin::where('created_at', '>=', Carbon::now()->subDay(30))->get(['browser', 'os', 'country']);

        $chart['user_browser_counter'] = $userLoginData->groupBy('browser')->map(function ($item, $key) {
            return collect($item)->count();
        });
        $chart['user_os_counter'] = $userLoginData->groupBy('os')->map(function ($item, $key) {
            return collect($item)->count();
        });
        $chart['user_country_counter'] = $userLoginData->groupBy('country')->map(function ($item, $key) {
            return collect($item)->count();
        })->sort()->reverse()->take(5);


        $deposit['total_deposit_amount']        = Deposit::successful()->sum('amount');
        $deposit['total_deposit_pending']       = Deposit::pending()->count();
        $deposit['total_deposit_rejected']      = Deposit::rejected()->count();
        $deposit['total_deposit_charge']        = Deposit::successful()->sum('charge');

        $trxReport['date'] = collect([]);
        $plusTrx = Transaction::where('trx_type','+')->where('created_at', '>=', Carbon::now()->subDays(30))
                                       ->selectRaw("SUM(amount) as amount, DATE_FORMAT(created_at,'%Y-%m-%d') as date")
                                       ->orderBy('created_at')
                                       ->groupBy('date')
                                       ->get();

        $plusTrx->map(function ($trxData) use ($trxReport) {
            $trxReport['date']->push($trxData->date);
        });

        $minusTrx = Transaction::where('trx_type','-')->where('created_at', '>=', Carbon::now()->subDays(30))
                                       ->selectRaw("SUM(amount) as amount, DATE_FORMAT(created_at,'%Y-%m-%d') as date")
                                       ->orderBy('created_at')
                                       ->groupBy('date')
                                       ->get();

        $minusTrx->map(function ($trxData) use ($trxReport) {
            $trxReport['date']->push($trxData->date);
        });

        $trxReport['date'] = dateSorting($trxReport['date']->unique()->toArray());


        // Monthly Deposit
        $report['months'] = collect([]);
        $report['deposit_month_amount'] = collect([]);

        $depositsMonth = Deposit::where('created_at', '>=', Carbon::now()->subYear())
            ->where('status', Status::PAYMENT_SUCCESS)
            ->selectRaw("SUM( CASE WHEN status = ".Status::PAYMENT_SUCCESS." THEN amount END) as depositAmount")
            ->selectRaw("DATE_FORMAT(created_at,'%M-%Y') as months")
            ->orderBy('created_at')
            ->groupBy('months')->get();

        $depositsMonth->map(function ($depositData) use ($report) {
            $report['months']->push($depositData->months);
            $report['deposit_month_amount']->push(getAmount($depositData->depositAmount));
        });

        $months = $report['months'];

        for($i = 0; $i < $months->count(); ++$i) {
            $monthVal      = Carbon::parse($months[$i]);
            if(isset($months[$i+1])){
                $monthValNext = Carbon::parse($months[$i+1]);
                if($monthValNext < $monthVal){
                    $temp = $months[$i];
                    $months[$i]   = Carbon::parse($months[$i+1])->format('F-Y');
                    $months[$i+1] = Carbon::parse($temp)->format('F-Y');
                }else{
                    $months[$i]   = Carbon::parse($months[$i])->format('F-Y');
                }
            }
        }

        $totalPackage = Package::count();

        $signalStatistics['total'] = Signal::count();
        $signalStatistics['sent'] = Signal::sent()->count();
        $signalStatistics['notSent'] = Signal::notSent()->count();

        $general = gs();
        $showCronModal = Carbon::parse($general->last_cron)->diffInMinutes() > 15 || !$general->last_cron;



        // New Records

        $date1 = date("Y-m-01");
        $date2 = date("Y-m-t");
        $stockPortFolioBuyVal = 0;
        $stockPortFolioCurrVal = 0;

        $investGraphArr = [];

        $stockPortFolio =  StockPortfolio::select(\DB::raw('SUM(quantity*buy_price) as buy_value'),\DB::raw('SUM(quantity*cmp) as current_value'),\DB::raw('DATE_FORMAT(buy_date,"%M-%Y") as buy_date'))->groupBy('buy_date')->get();

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


        $globalStockPortFolio =  GlobalStockPortFolio::select(\DB::raw('SUM(quantity*buy_price) as buy_value'),\DB::raw('SUM(quantity*cmp) as current_value'),\DB::raw('DATE_FORMAT(buy_date,"%M-%Y") as buy_date'))->groupBy('buy_date')->get();
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


        $foglobalStockPortFolio =  FOPortfolios::select(\DB::raw('SUM(quantity*buy_price) as buy_value'),\DB::raw('SUM(quantity*cmp) as current_value'),\DB::raw('DATE_FORMAT(buy_date,"%M-%Y") as buy_date'))->groupBy('buy_date')->get();
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


        $metalsPortFolio =  MetalsPortfolio::select(\DB::raw('SUM(quantity*buy_price) as buy_value'),\DB::raw('SUM(quantity*cmp) as current_value'),\DB::raw('DATE_FORMAT(buy_date,"%M-%Y") as buy_date'))->groupBy('buy_date')->get();

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

        $user = User::sum('balance');

        return view('admin.dashboard', compact('pageTitle', 'widget', 'chart','deposit','report','depositsMonth','months','trxReport','plusTrx','minusTrx', 'totalPackage', 'signalStatistics', 'showCronModal','stockPortFolio','globalStockPortFolio','foglobalStockPortFolio','metalsPortFolio','totalInvestedAmount','totalCurrentAmount','user'));
    }


    public function profile()
    {
        $pageTitle = 'Profile';
        $admin = auth('admin')->user();
        return view('admin.profile', compact('pageTitle', 'admin'));
    }

    public function profileUpdate(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'image' => ['nullable','image',new FileTypeValidate(['jpg','jpeg','png'])]
        ]);
        $user = auth('admin')->user();

        if ($request->hasFile('image')) {
            try {
                $old = $user->image;
                $user->image = fileUploader($request->image, getFilePath('adminProfile'), getFileSize('adminProfile'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        $notify[] = ['success', 'Profile updated successfully'];
        return to_route('admin.profile')->withNotify($notify);
    }


    public function password()
    {
        $pageTitle = 'Password Setting';
        $admin = auth('admin')->user();
        return view('admin.password', compact('pageTitle', 'admin'));
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|min:5|confirmed',
        ]);

        $user = auth('admin')->user();
        if (!Hash::check($request->old_password, $user->password)) {
            $notify[] = ['error', 'Password doesn\'t match!!'];
            return back()->withNotify($notify);
        }
        $user->password = bcrypt($request->password);
        $user->save();
        $notify[] = ['success', 'Password changed successfully.'];
        return to_route('admin.password')->withNotify($notify);
    }

    public function notifications(){
        $notifications = AdminNotification::orderBy('id','desc')->with('user')->paginate(getPaginate());
        $pageTitle = 'Notifications';
        return view('admin.notifications',compact('pageTitle','notifications'));
    }


    public function notificationRead($id){
        $notification = AdminNotification::findOrFail($id);
        $notification->is_read = Status::YES;
        $notification->save();
        $url = $notification->click_url;
        if ($url == '#') {
            $url = url()->previous();
        }
        return redirect($url);
    }

    public function requestReport()
    {
        $pageTitle = 'Your Listed Report & Request';
        $arr['app_name'] = systemDetails()['name'];
        $arr['app_url'] = env('APP_URL');
        $arr['purchase_code'] = env('PURCHASECODE');
        $url = "https://license.viserlab.com/issue/get?".http_build_query($arr);
        $response = CurlRequest::curlContent($url);
        $response = json_decode($response);
        if ($response->status == 'error') {
            return to_route('admin.dashboard')->withErrors($response->message);
        }
        $reports = $response->message[0];
        return view('admin.reports',compact('reports','pageTitle'));
    }

    public function reportSubmit(Request $request)
    {
        $request->validate([
            'type'=>'required|in:bug,feature',
            'message'=>'required',
        ]);
        $url = 'https://license.viserlab.com/issue/add';

        $arr['app_name'] = systemDetails()['name'];
        $arr['app_url'] = env('APP_URL');
        $arr['purchase_code'] = env('PURCHASECODE');
        $arr['req_type'] = $request->type;
        $arr['message'] = $request->message;
        $response = CurlRequest::curlPostContent($url,$arr);
        $response = json_decode($response);
        if ($response->status == 'error') {
            return back()->withErrors($response->message);
        }
        $notify[] = ['success',$response->message];
        return back()->withNotify($notify);
    }

    public function readAll(){
        AdminNotification::where('is_read',Status::NO)->update([
            'is_read'=>Status::YES
        ]);
        $notify[] = ['success','Notifications read successfully'];
        return back()->withNotify($notify);
    }

    public function downloadAttachment($fileHash)
    {
        $filePath = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $title = slug(gs('site_name')).'- attachments.'.$extension;
        $mimetype = mime_content_type($filePath);
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

}
