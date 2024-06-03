<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Landlord\Package;
use App\Models\Landlord\Setting;
use App\Models\MonthlyInvoicePackage;
use App\Models\Student;
use App\Models\StudentTutoringPackage;
use App\Models\Tutor;
use App\Repositories\Landlord\SubscriptionsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laracasts\Flash\Flash;

class BillingController extends Controller
{

    private SubscriptionsRepository $subscriptionsRepo;

    public function __construct(SubscriptionsRepository $subscriptionsRepo)
    {
        $this->subscriptionsRepo = $subscriptionsRepo;
    }
    public function showPackages()
    {
        $packages = Package::on('landlord')->where('status',true)->where('visibility','visible')->get();
        return view('settings.billing.packages',compact('packages'));

    }
    public function changePackage(Request $request,$id)
    {
        $continue = true;
        $over_limits = [];
        $landlord_settings = Setting::on('landlord')->where('id', 'default')->first();

        if (!$package = Package::On('landlord')->Where('id', $id)->first()) {
            Log::error("changing plan failed - the package could not be found", ['process' => '[change-subscription-plan]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'plain_id' => $id]);
            abort(409, __('lang.error_message_with_code') . config('app.debug_ref'));
        }

        //[check limits] -  count current usage
        $count['max_students'] = Student::count();
        $count['max_tutors'] =Tutor::count();
        $count['max_student_packages'] = StudentTutoringPackage::count();
        $count['max_monthly_packages'] = MonthlyInvoicePackage::count();

        //[check limits] - [students] (exclude unlimited)
        if ($package->max_students > 0 && ($count['max_students'] > $package->max_students)) {
            $continue = false;
            $over_limits[] = [
                'limits' => $package->max_students,
                'usage' => $count['max_students'],
                'error_message' => "you can not update your plan because current students ({$count['max_students']}) are more than the limit of the new plan ({$package->max_students})"
            ];
        }

        //[check limits] - [tutors] (exclude unlimited)
        if ($package->max_tutors > 0 && ($count['max_tutors'] > $package->max_tutors)) {
            $continue = false;
            $over_limits[] = [
                'feature' => __('lang.tutors'),
                'limits' => $package->max_tutors,
                'error_message' => "you can not update your plan because current tutors ({$count['max_tutors']}) are more than the limit of the new plan ({$package->max_tutors})"
            ];
        }


        //[check limits] - [student_packages] (exclude unlimited)
        if ($package->max_student_packages > 0 && ($count['max_student_packages'] > $package->max_student_packages)) {
            $continue = false;
            $over_limits[] = [
                'feature' => __('lang.student_packages'),
                'limits' => $package->max_student_packages,
                'error_message' => "you can not update your plan because current student packages ({$count['max_student_packages']}) are more than the limit of the new plan ({$package->max_student_packages})"
            ];
        }

        //[check limits] - [monthly_packages] (exclude unlimited)
        if ($package->max_monthly_packages > 0 && ($count['max_monthly_packages'] > $package->max_monthly_packages)) {
            $continue = false;
            $over_limits[] = [
                'feature' => __('lang.monthly_packages'),
                'limits' => $package->max_monthly_packages,
                'error_message' =>  "you can not update your plan because current monthly packages ({$count['max_monthly_packages']}) are more than the limit of the new plan ({$package->max_monthly_packages})"
            ];
        }

        //there in a limits error - show the error
        if (!$continue) {
            $payload = [
                'over_limits' => $over_limits,
                'show' => 'error',
            ];
            foreach ($over_limits as $error) {
                Flash::error($error['error_message']);
            }
            return redirect()->back()->with('error', __('lang.error_request_could_not_be_completed'));
        }

        //change the package and get the new subscription
        $data = [
            'customer_id' => config('system.saas_tenant_id'),
            'package_id' => $package->id,
            'billing_cycle' => $request->billing_cycle,
            'billing_type' => 'automatic',
            'free_trial' => 'no',
            'free_trial_days' => 0,
        ];
        if (!$subscription = $this->subscriptionsRepo->changeCustomersPlan($data)) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //update customer settings
        \App\Models\Setting::where('id', 1)
            ->update([
                'saas_status' => $subscription->status,
                'saas_package_id' => $package->id,
                'saas_package_limits_tutors' => $package->max_tutors,
                'saas_package_limits_students' => $package->max_students,
                'saas_package_limits_monthly_packages' => $package->max_monthly_packages,
                'saas_package_limits_student_packages' => $package->max_student_packages,
            ]);

        //package was free, just show success
        if ($subscription->type == 'free') {
            Flash::success('Package updated successfully');
            return redirect('/settings/billing/packages');
        }

        //package was paid, redirect to notices
        $payload = [
            'show' => 'payment-required',
        ];
        return view('settings.billing.notices', compact('payload','package','subscription','landlord_settings'));

    }

    public function pay(Request $request,$unique_id)
    {
        if ($request->payment_method == 'stripe' && $request->Ajax()){
            $payload = $this->subscriptionsRepo->paySubscription($request,$unique_id);
            $html = view('settings.billing.notices.stripe-button', compact( 'payload'))->render();
            return response()->json(['html' => $html]);
        }

    }

    public function packagePaymentSuccess(Request $request)
    {
        dd($request);
    }

    public function packagePaymentFailed(Request $request)
    {
        dd($request);
    }

}
