<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use DB;
class ProfileController extends Controller
{
    public function profile()
    {
        $pageTitle = "Profile Setting";
        $user = auth()->user();
        return view($this->activeTemplate. 'user.profile_setting', compact('pageTitle','user'));
    }

    public function submitProfile(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
        ],[
            'firstname.required'=>'First name field is required',
            'lastname.required'=>'Last name field is required'
        ]);

        $user = auth()->user();

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->dob = $request->dob;
        $user->pan_number = $request->pan_number;
        $user->investment_amount = $request->investment_amount;
        $user->scheme_name = $request->scheme_name;
        $user->bank_name = $request->bank_name;
        $user->bank_account_no = $request->bank_account_no;
        $user->ifsc_code = $request->ifsc_code;
        $user->bank_address = $request->bank_address;
        // $user->telegram_username = $request->telegram_username;

        $user->address = [
            'address' => $request->address,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => @$user->address->country,
            'city' => $request->city
        ];

        $user->save();
        $notify[] = ['success', 'Profile updated successfully'];
        return back()->withNotify($notify);
    }

    public function changePassword()
    {
        $pageTitle = 'Change Password';
        return view($this->activeTemplate . 'user.password', compact('pageTitle'));
    }

    public function submitPassword(Request $request)
    {

        $passwordValidation = Password::min(6);
        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $this->validate($request, [
            'current_password' => 'required',
            'password' => ['required','confirmed',$passwordValidation]
        ]);

        $user = auth()->user();
        if (Hash::check($request->current_password, $user->password)) {
            $password = Hash::make($request->password);
            $user->password = $password;
            $user->save();
            $notify[] = ['success', 'Password changes successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'The password doesn\'t match!'];
            return back()->withNotify($notify);
        }
    }

    public function userInfo()
    {
        $pageTitle = "User Info";
        $user = auth()->user();
        return view($this->activeTemplate . 'user.info', compact('pageTitle', 'user'));
    }

    public function tradeDeskSignal(Request $request){
        $data['pageTitle'] = "User Info";
        $symbolArr = allTradeSymbolsNew();
        
        $timeFrame = 5;
        $atm = 1;
        if(!empty($request->time_frame)){
            $timeFrame = $request->time_frame;
        }

        $filtered = 0;
        $data['symbolArr'] = $symbolArr;
        $stockName = '';
        if(!empty($request->stock_name) && in_array($request->stock_name,$symbolArr)){
            $symbolArr = [$request->stock_name];
            $stockName = $request->stock_name;
            $filtered = 1;
        }

        $today = date("Y-m-d");
        $finalData = [];
        foreach($symbolArr as $value){
            $tableName = strtolower($value);
            $dataFetch = DB::table($tableName)->whereDate('created_at',$today)->where("atm",$atm);
            if($filtered==0){
                $dataFetch->limit(5)->orderBY('id','DESC');
                $dataFetch = $dataFetch->orderBy('created_at','ASC')->get();
            }else{
                $dataFetch = $dataFetch->paginate(100);
            }
            if($filtered==0){
                $finalD = [];
                foreach($dataFetch as $val){
                    $finalD[] = (object)[
                        'date'=>date("d-M-Y",strtotime($val->exchFeedTime_ce)),
                        'time'=>date("H:i",strtotime($val->exchFeedTime_ce)),
                        'ce_symbol_name'=>$val->symbol_ce,
                        'pe_symbol_name'=>$val->symbol_pe,
                        'ce_vmap'=>$val->vmap_ce,
                        'pe_vmap'=>$val->vmap_pe,
                        'ce_oi'=>$val->vmap_ce,
                        'pe_oi'=>$val->vmap_pe,
                        'ce_close_price'=>$val->close_ce,
                        'pe_close_price'=>$val->close_pe,
                        'buy_action'=>"BUY CE",
                        'sell_action'=>"SELL PE",
                        'strategy_name'=>"LONG CE, SHORT PE",
                        'created'=>date(strtotime($val->exchFeedTime_ce))
                    ];
                }
                if($filtered==0){
                    usort($finalD, function($a, $b)
                    {
                        return $a->created > $b->created;
                    });
                }
                $finalData[$value] = $finalD;
            }else{
                $finalData = $dataFetch;
            }
           
        }
        $data['finalData'] = $finalData;
        $data['timeFrame'] = $timeFrame;
        $data['stockName'] = $stockName;
        $data['filtered'] = $filtered;
        // dd($finalData);
        return view($this->activeTemplate . 'user.trade-desk-signal', $data);
    }
}
