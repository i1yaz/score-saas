<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Landlord\Package;
use App\Models\Landlord\Subscription;
use App\Repositories\Landlord\SubscriptionsRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class SubscriptionController extends Controller
{
    private SubscriptionsRepository $subscriptionsRepo;

    public function __construct(SubscriptionsRepository $subscriptionsRepo)
    {
        $this->middleware('auth');
        $this->subscriptionsRepo = $subscriptionsRepo;
    }

    public function index()
    {
        $subscriptions = $this->subscriptionsRepo->search(
            [
                'subscriptions.id', 'subscriptions.first_name', 'subscriptions.last_name','subscriptions.created_at',
                'subscriptions.amount', 'subscriptions.date_renewed', 'subscriptions.gateway_billing_cycle','subscriptions.status',
                'subscriptions.gateway_name'
            ]);
        return view('landlord.subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        $packages = Package::all();
        return view('landlord.subscriptions.create', compact('packages'));
    }

    public function store(CreateSubscription $request)
    {
        $subscription = new Subscription();
        $this->saveSubscriptionDetails($request, $subscription);
        Flash::success('Subscription saved successfully.');
        return redirect(route('landlord.subscriptions.index'));
    }

    public function edit($id)
    {
        $subscription = Subscription::findOrFail($id);
        $packages = Package::all();
        return view('landlord.subscriptions.edit', compact('subscription', 'packages'));
    }

    public function update(CreateSubscription $request, $id)
    {
        $subscription = Subscription::findOrFail($id);
        $this->saveSubscriptionDetails($request, $subscription);
        Flash::success('Subscription updated successfully.');
        return redirect(route('landlord.subscriptions.index'));
    }

    public function destroy($id)
    {
        if (Subscription::where('id', $id)->delete()) {
            Flash::success('Subscription deleted successfully.');
        } else {
            Flash::error('Subscription not deleted.');
        }
        return redirect(route('landlord.subscriptions.index'));
    }

    private function saveSubscriptionDetails($request, $subscription)
    {
        $subscription->package_id = $request->package_id;
        $subscription->name = $request->name;
        $subscription->price = $request->price;
        $subscription->save();
    }

}
