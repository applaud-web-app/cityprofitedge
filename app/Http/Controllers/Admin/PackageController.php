<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;

class PackageController extends Controller{

    public function all(){
        $pageTitle = 'Manage Package';
        $packages = Package::paginate(getPaginate());
        return view('admin.package.all', compact('pageTitle' , 'packages'));
    }

    public function add(){
        $this->addOrUpdate();

        $notify[] = ['success', 'Package added successfully'];
        return back()->withNotify($notify);
    }

    public function update(){
        $this->addOrUpdate();

        $notify[] = ['success', 'Package updated successfully'];
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

}
