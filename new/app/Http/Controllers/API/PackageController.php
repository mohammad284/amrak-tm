<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\User;
use App\Models\EProvider;
use App\Models\EProviderPayout;
use App\Models\PaymentMethod;
use App\Models\PaymentStatus;
use App\Models\UserSubscrip;
class PackageController extends Controller
{
    public function allPackages(){
        $packages = Package::all();
        return response()->json([
            'status' => '200',
            'details'=> $packages
        ]);
    }
    public function subscription(Request $requset){
        // return response()->json($requset);
        $package  = Package::find($requset->package_id);
        $end_date = $package->created_at;
        if($package->period == 'year' ){
            $expired_date = $end_date->addYear();
        }elseif($package->period == 'week'){
            $expired_date = $end_date->addDays(6);
        }elseif($package->period == 'month'){
            $expired_date = $end_date->addMonth(1);
        }else{
            $expired_date = $end_date->addMonth(6);
        }
        $user = User::find($requset->user_id);
        $data = [
            'user'           => $requset->user_id,
            'email'          => $user->email,
            'package'        => $package->name,
            'expired_date'   => $expired_date,
        ];
        $user_package = UserSubscrip::where('email',$user->email)->where('package',$package->name)->where('expired_date','>',now())->count();
        if($user_package > 0){
            return response()->json([
                'status' => 404,
                'details'=> 'لديك باقة مفعلة مسبقا'
            ]);
        }
        UserSubscrip::create($data);
        return response()->json([
            'status'=>200,
            'details'=>'success'
        ]);
    }
    public function cash(Request $request)
    {
        
        $input = $request->all();
        
        try {
            $package = Package::find($input['id']);
            $payment_method = PaymentMethod::find($request->payment_method_id);
            $user = EProvider::find($request->user_id);
            $input['subscript']['amount'] = $package->price;
            $input['subscript']['paid_date'] = now();
            $input['subscript']['e_provider_id'] = $request->user_id;
            $input['subscript']['method'] = $payment_method->name;
            $input['subscript']['note'] = $request->note;
            EProviderPayout::create($input['subscript']);
        } catch (ValidatorException $e) {
            return $this->sendError(__('lang.not_found', ['operator' => __('lang.payment')]));
        }
        return response()->json( __('lang.saved_successfully', ['operator' => __('lang.payment')]));


    }
}