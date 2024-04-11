<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\SiteVariable;

class PackageController extends Controller{

    public function all(){
        $pageTitle = 'Manage Product';
        $packages = Package::paginate(getPaginate());
        return view('admin.package.all', compact('pageTitle' , 'packages'));
    }

    public function add(){
        $this->addOrUpdate();

        $notify[] = ['success', 'Product added successfully'];
        return back()->withNotify($notify);
    }

    public function update(){
        $this->addOrUpdate();

        $notify[] = ['success', 'Product updated successfully'];
        return back()->withNotify($notify);
    }

    private function addOrUpdate(){

        $request = request();
        $package = new Package();

        $validation = [
            'name'=> 'required|max:250|unique:packages,name,'.$request->id,
            // 'price'=> 'required|numeric|gt:0',
            // 'validity'=> 'required|integer|gt:0',
            // 'features' => 'required|array|max:60000',
            'description'=> 'required',
            'asset_type'=> 'required',
            'min_investment'=> 'required|gt:0',
            'time_horizon'=> 'required',
            'risk_appetite'=> 'required',
            'expected_returns'=> 'required',
            'frequency'=> 'required',
            'hedging_strategy'=> 'required',
        ];

        if($request->id){
            $validation['id'] = 'required|integer';
        }

        $request->validate($validation);

        if($request->id){
            $package = Package::findOrFail($request->id);
        }

        $package->name = $request->name;
        $package->price = NULL;
        $package->validity = NULL;
        $package->features = NULL;
        $package->description = $request->description;
        $package->asset_type = $request->asset_type;
        $package->min_investment = $request->min_investment;
        $package->time_horizon = $request->time_horizon;
        $package->risk_appetite = $request->risk_appetite;
        $package->expected_returns = $request->expected_returns;
        $package->frequency = $request->frequency;
        $package->hedging_strategy = $request->hedging_strategy;
        $package->save();
    }

    public function status($id){
        return Package::changeStatus($id);
    }

    public function setFibonaciVariables(){
        $pageTitle = 'Set Fibonaci Variables';
        $data = SiteVariable::select('content')->where('value','fibonaci')->first();
        $percentData = (object)[
            'percentage_one'=>'',
            'percentage_two'=>'',
            'percentage_three'=>'',
        ];
        if($data){
            $percentData = json_decode($data->content);
        }
        return view('admin.package.set-fibonaci-variables', compact('pageTitle','percentData' ));
    }

    public function storeFibonaciVariables(Request $request){
        $request->validate([
            'percentage_one'=>'required',
            'percentage_two'=>'required',
            'percentage_three'=>'required',
        ]);
        $checkExist = SiteVariable::select('id')->where('value','fibonaci')->first();
        if($checkExist){
            SiteVariable::where('id',$checkExist->id)->update([
                'content'=> json_encode([
                    'percentage_one'=>$request->percentage_one,
                    'percentage_two'=>$request->percentage_two,
                    'percentage_three'=>$request->percentage_three,
                ])
            ]);
        }else{
            $obj = new SiteVariable();
            $obj->value = 'fibonaci';
            $obj->content = json_encode([
                'percentage_one'=>$request->percentage_one,
                'percentage_two'=>$request->percentage_two,
                'percentage_three'=>$request->percentage_three,
            ]);
            $obj->save();
        }
        return redirect('admin/package/set-fibonaci-variables');
    }

}
