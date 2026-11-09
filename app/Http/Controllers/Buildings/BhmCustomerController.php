<?php

namespace App\Http\Controllers\Buildings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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

    public function createCitizen()
    {
        if (!auth()->user()->can('Create-Client') && !auth()->user()->can('bhm.subscriptions.edit') && !auth()->user()->can('Read-Subscriptions')) {
            abort(403, 'غير مصرح لك بإضافة مواطن جديد.');
        }

        return view('buildings.customers.create_citizen');
    }

    public function storeCitizen(Request $request)
    {
        if (!auth()->user()->can('Create-Client') && !auth()->user()->can('bhm.subscriptions.edit') && !auth()->user()->can('Read-Subscriptions')) {
            abort(403, 'غير مصرح لك بإضافة مواطن جديد.');
        }

        $request->validate([
            'id_no' => [
                'required',
                'string',
                'min:7',
                'max:20',
                Rule::unique('bhm_customers', 'id_no')->whereNull('deleted_at'),
            ],
            'name' => 'required|string|max:300',
            'mobile' => 'nullable|string|max:30',
            'telephone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:255',
        ], [
            'id_no.required' => 'يرجى إدخال رقم الهوية الوطنية.',
            'id_no.min' => 'رقم الهوية يجب ألا يقل عن 7 أرقام.',
            'id_no.max' => 'رقم الهوية لا يجب أن يتجاوز 20 حرفاً/رقماً.',
            'id_no.unique' => 'رقم الهوية هذا مسجل مسبقاً لمواطن آخر في النظام.',
            'name.required' => 'يرجى إدخال الاسم الرباعي أو اسم المواطن/الجهة.',
            'name.max' => 'الاسم طويل جداً (الحد الأقصى 300 حرف).',
            'email.email' => 'يرجى إدخال بريد إلكتروني بصيغة صحيحة.',
        ]);

        $customer = BhmCustomer::create([
            'id_no' => trim($request->input('id_no')),
            'name' => trim($request->input('name')),
            'mobile' => trim($request->input('mobile', '')),
            'telephone' => trim($request->input('telephone', '')),
            'email' => trim($request->input('email', '')),
            'address' => trim($request->input('address', '')),
            'notes' => trim($request->input('notes', '')),
        ]);

        return redirect()->route('customers.show', $customer->id)
            ->with('success', 'تمت إضافة المواطن بنجاح إلى سجلات البلدية.');
    }

    public function editCitizen($id)
    {
        if (!auth()->user()->can('Update-Client') && !auth()->user()->can('bhm.subscriptions.edit') && !auth()->user()->can('Read-Subscriptions')) {
            abort(403, 'غير مصرح لك بتعديل بيانات المواطن.');
        }

        $customer = BhmCustomer::findOrFail($id);

        return view('buildings.customers.edit_citizen', compact('customer'));
    }

    public function updateCitizen(Request $request, $id)
    {
        if (!auth()->user()->can('Update-Client') && !auth()->user()->can('bhm.subscriptions.edit') && !auth()->user()->can('Read-Subscriptions')) {
            abort(403, 'غير مصرح لك بتعديل بيانات المواطن.');
        }

        $customer = BhmCustomer::findOrFail($id);

        $request->validate([
            'id_no' => [
                'required',
                'string',
                'min:7',
                'max:20',
                Rule::unique('bhm_customers', 'id_no')->ignore($customer->id)->whereNull('deleted_at'),
            ],
            'name' => 'required|string|max:300',
            'mobile' => 'nullable|string|max:30',
            'telephone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:255',
        ], [
            'id_no.required' => 'يرجى إدخال رقم الهوية الوطنية.',
            'id_no.min' => 'رقم الهوية يجب ألا يقل عن 7 أرقام.',
            'id_no.max' => 'رقم الهوية لا يجب أن يتجاوز 20 حرفاً/رقماً.',
            'id_no.unique' => 'رقم الهوية هذا مسجل مسبقاً لمواطن آخر في النظام.',
            'name.required' => 'يرجى إدخال الاسم الرباعي أو اسم المواطن/الجهة.',
            'name.max' => 'الاسم طويل جداً (الحد الأقصى 300 حرف).',
            'email.email' => 'يرجى إدخال بريد إلكتروني بصيغة صحيحة.',
        ]);

        $customer->update([
            'id_no' => trim($request->input('id_no')),
            'name' => trim($request->input('name')),
            'mobile' => trim($request->input('mobile', '')),
            'telephone' => trim($request->input('telephone', '')),
            'email' => trim($request->input('email', '')),
            'address' => trim($request->input('address', '')),
            'notes' => trim($request->input('notes', '')),
        ]);

        return redirect()->route('customers.show', $customer->id)
            ->with('success', 'تم تحديث وحفظ بيانات المواطن بنجاح.');
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

    public function searchCitizenApi(Request $request)
    {
        $idNumber = trim($request->input('citizen_id', $request->input('q', '')));
        
        if (empty($idNumber)) {
            return response()->json([
                'found' => false,
                'message' => 'يرجى إدخال رقم الهوية للبحث.'
            ]);
        }

        $customer = BhmCustomer::where('id_no', $idNumber)->first();

        if (!$customer) {
            return response()->json([
                'found' => false,
                'message' => 'المواطن غير مسجل مسبقاً في النظام. يمكنك إضافته الآن.'
            ]);
        }

        // Fetch properties / buildings linked to this customer
        $buildings = \App\Models\Buildings\BhmBuilding::where('file_number', 'like', "%{$idNumber}%")
            ->orWhere('building_name', 'like', "%{$customer->name}%")
            ->take(10)
            ->get();

        $properties = [];
        foreach ($buildings as $b) {
            $properties[] = [
                'id' => $b->id,
                'piece_number' => $b->parcel_number ?? '',
                'voucher_number' => $b->block_number ?? '',
                'detailed_address' => $b->building_name ?? $customer->address ?? '',
                'regulation_file_number' => $b->file_number ?? '',
                'subscription_number' => '',
                'relation_status' => 'owner',
            ];
        }

        return response()->json([
            'found' => true,
            'citizen' => [
                'id' => $customer->id,
                'citizen_id' => $customer->id_no,
                'name' => $customer->name,
                'phone' => $customer->mobile ?? $customer->telephone ?? '',
                'properties' => $properties,
            ]
        ]);
    }

    public function storeCitizenApi(Request $request)
    {
        $validated = $request->validate([
            'citizen_id' => 'required|string|min:7|max:20',
            'name' => 'required|string|max:300',
            'phone' => 'nullable|string|max:30',
            'properties' => 'nullable|array',
        ]);

        $customer = BhmCustomer::updateOrCreate(
            ['id_no' => trim($validated['citizen_id'])],
            [
                'name' => trim($validated['name']),
                'mobile' => trim($validated['phone'] ?? ''),
            ]
        );

        $savedProperties = [];
        if (!empty($validated['properties'])) {
            foreach ($validated['properties'] as $prop) {
                $building = \App\Models\Buildings\BhmBuilding::create([
                    'file_number' => !empty($prop['regulation_file_number']) ? $prop['regulation_file_number'] : ('REG-' . date('Y') . '-' . rand(100, 999)),
                    'parcel_number' => $prop['piece_number'] ?? null,
                    'block_number' => $prop['voucher_number'] ?? null,
                    'building_name' => $prop['detailed_address'] ?? 'عقار تابع للمواطن ' . $customer->name,
                ]);

                $savedProperties[] = [
                    'id' => $building->id,
                    'piece_number' => $building->parcel_number,
                    'voucher_number' => $building->block_number,
                    'detailed_address' => $building->building_name,
                    'regulation_file_number' => $building->file_number,
                    'subscription_number' => $prop['subscription_number'] ?? '',
                    'relation_status' => $prop['relation_status'] ?? 'owner',
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'تم حفظ بيانات المواطن والعقار بنجاح.',
            'citizen' => [
                'id' => $customer->id,
                'citizen_id' => $customer->id_no,
                'name' => $customer->name,
                'phone' => $customer->mobile,
                'properties' => $savedProperties,
            ]
        ]);
    }
}
