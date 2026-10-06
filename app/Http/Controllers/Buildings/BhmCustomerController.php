<?php

namespace App\Http\Controllers\Buildings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buildings\BhmSubscription;
use App\Models\Buildings\BhmCustomer;

class BhmCustomerController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'subscriptions');

        if ($tab == 'subscriptions') {
            $filters = $request->only(['id_number', 'subscriber_name', 'building_id']);
            $data = BhmSubscription::with(['building.zone', 'building.street', 'units'])
                ->when($filters['id_number'] ?? null, fn($q, $v) => $q->where('id_number', $v))
                ->when($filters['subscriber_name'] ?? null, fn($q, $v) => $q->where('subscriber_name', 'like', "%{$v}%"))
                ->when($filters['building_id'] ?? null, fn($q, $v) => $q->where('building_id', $v))
                ->orderBy('id', 'desc')
                ->paginate(15)
                ->withQueryString();
        } else {
            $filters = $request->only(['id_no', 'name', 'mobile']);
            $data = BhmCustomer::when($filters['id_no'] ?? null, fn($q, $v) => $q->where('id_no', $v))
                ->when($filters['name'] ?? null, fn($q, $v) => $q->where('name', 'like', "%{$v}%"))
                ->when($filters['mobile'] ?? null, fn($q, $v) => $q->where('mobile', $v))
                ->orderBy('id', 'desc')
                ->paginate(15)
                ->withQueryString();
        }

        return view('buildings.customers.index', compact('data', 'tab', 'filters'));
    }

    public function showSubscription($id)
    {
        $subscription = BhmSubscription::with([
            'building.zone', 'building.street', 
            'owner', 'units.floor'
        ])->findOrFail($id);

        return view('buildings.customers.show_subscription', compact('subscription'));
    }

    public function showCustomer($id)
    {
        $customer = BhmCustomer::findOrFail($id);
        
        return view('buildings.customers.show_customer', compact('customer'));
    }
}
