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
        $filters = $request->only(['id_number', 'subscriber_name', 'building_id']);
        $data = BhmSubscription::with(['building.zone', 'building.street', 'units'])
            ->when($filters['id_number'] ?? null, fn($q, $v) => $q->where('id_number', $v))
            ->when($filters['subscriber_name'] ?? null, function($q, $v) {
                $cleaned = preg_replace('/[+\-><\(\)~*\"@]+/', ' ', trim($v));
                $terms = array_filter(explode(' ', $cleaned));
                if (!empty($terms)) {
                    $boolQuery = implode(' ', array_map(fn($t) => '+' . $t . '*', $terms));
                    $q->where(function($sub) use ($boolQuery, $v) {
                        $sub->whereRaw("MATCH(subscriber_name) AGAINST(? IN BOOLEAN MODE)", [$boolQuery])
                            ->orWhere('subscriber_name', 'like', "%{$v}%");
                    });
                } else {
                    $q->where('subscriber_name', 'like', "%{$v}%");
                }
            })
            ->when($filters['building_id'] ?? null, fn($q, $v) => $q->where('building_id', $v))
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('buildings.customers.index', compact('data', 'filters'));
    }

    public function citizensIndex(Request $request)
    {
        $filters = $request->only(['id_no', 'name', 'mobile']);
        $data = BhmCustomer::when($filters['id_no'] ?? null, fn($q, $v) => $q->where(fn($sub) => $sub->where('id_no', $v)->orWhere('id_number', $v)))
            ->when($filters['mobile'] ?? null, fn($q, $v) => $q->where('mobile', $v))
            ->when($filters['name'] ?? null, function($q, $v) {
                $cleaned = preg_replace('/[+\-><\(\)~*\"@]+/', ' ', trim($v));
                $terms = array_filter(explode(' ', $cleaned));
                if (!empty($terms)) {
                    $boolQuery = implode(' ', array_map(fn($t) => '+' . $t . '*', $terms));
                    $q->where(function($sub) use ($boolQuery, $v) {
                        $sub->whereRaw("MATCH(name) AGAINST(? IN BOOLEAN MODE)", [$boolQuery])
                            ->orWhere('name', 'like', "%{$v}%");
                    });
                } else {
                    $q->where('name', 'like', "%{$v}%");
                }
            })
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('buildings.customers.citizens', compact('data', 'filters'));
    }

    public function showSubscription($id)
    {
        $subscription = BhmSubscription::with([
            'building.zone', 'building.street', 
            'buildingOwner', 'units.floor'
        ])->findOrFail($id);

        return view('buildings.customers.show_subscription', compact('subscription'));
    }

    public function showCustomer($id)
    {
        $customer = BhmCustomer::with(['crafts.statusRecord', 'crafts.craftType'])->findOrFail($id);
        
        return view('buildings.customers.show_customer', compact('customer'));
    }
}
