<?php

namespace App\Http\Controllers\Buildings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Buildings\BhmSubscription;
use App\Models\Buildings\BhmCustomer;

class BhmCustomerController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_citizens' => BhmCustomer::count(),
            'citizens_with_id' => BhmCustomer::whereNotNull('id_no')->where('id_no', '!=', '')->count(),
            'citizens_with_phone' => BhmCustomer::where(function($q) {
                $q->whereNotNull('mobile')->where('mobile', '!=', '')
                  ->orWhere(fn($sq) => $sq->whereNotNull('telephone')->where('telephone', '!=', ''));
            })->count(),
            'owners_count' => \App\Models\Buildings\BhmBuildingOwner::whereNotNull('id_card')->where('id_card', '!=', '')->distinct('id_card')->count('id_card'),
            'crafts_count' => \App\Models\Buildings\BhmEconomical::whereNotNull('id_card')->where('id_card', '!=', '')->distinct('id_card')->count('id_card'),
            
            'total_subscriptions' => BhmSubscription::count(),
            'buildings_with_subs' => BhmSubscription::distinct('building_id')->count('building_id'),
            'units_with_subs' => \Illuminate\Support\Facades\DB::table('bhm_subscription_unit')->distinct('unit_id')->count('unit_id'),

            'total_buildings' => \App\Models\Buildings\BhmBuilding::count(),
            'total_transactions' => \App\Models\Transactions\BhmTransaction::count(),
        ];

        $latestCitizens = BhmCustomer::latest('id')->take(6)->get();
        $latestSubscriptions = BhmSubscription::with(['building.zone', 'building.street'])->latest('id')->take(6)->get();

        return view('customers.dashboard', compact('stats', 'latestCitizens', 'latestSubscriptions'));
    }

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
        $filters = $request->only(['id_no', 'name', 'mobile', 'search']);
        $search = trim($request->input('search', ''));
        $idNo = trim($request->input('id_no', ''));

        $data = BhmCustomer::query()
            ->when($idNo, fn($q) => $q->where('id_no', 'like', "%{$idNo}%"))
            ->when($search, function($q) use ($search) {
                $q->where(function($sq) use ($search) {
                    $sq->where('id_no', 'like', "%{$search}%")
                       ->orWhere('name', 'like', "%{$search}%")
                       ->orWhere('mobile', 'like', "%{$search}%");
                });
            })
            ->when($filters['mobile'] ?? null, fn($q, $v) => $q->where('mobile', 'like', "%{$v}%"))
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
        if (!auth()->user()->can('Update-Citizen') && !auth()->user()->can('Update-Client') && !auth()->user()->can('bhm.subscriptions.edit') && !auth()->user()->can('Read-Subscriptions')) {
            abort(403, 'غير مصرح لك بتعديل بيانات المواطن.');
        }

        $customer = BhmCustomer::findOrFail($id);

        return view('buildings.customers.edit_citizen', compact('customer'));
    }

    public function updateCitizen(Request $request, $id)
    {
        if (!auth()->user()->can('Update-Citizen') && !auth()->user()->can('Update-Client') && !auth()->user()->can('bhm.subscriptions.edit') && !auth()->user()->can('Read-Subscriptions')) {
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

        return redirect()->route('customers.citizens.show', $customer->id)
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
        $data = $this->getCitizenFullData($id);
        return view('buildings.customers.show_customer', $data);
    }

    public function printCitizen($id)
    {
        $data = $this->getCitizenFullData($id);
        $customer = $data['customer'];
        $verificationUrl = route('customers.citizens.show', $customer->id);
        $data['verificationUrl'] = $verificationUrl;
        $data['qrUrl'] = 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&format=svg&data=' . urlencode($verificationUrl);

        return view('buildings.customers.print_citizen', $data);
    }

    protected function getCitizenFullData($id)
    {
        $customer = BhmCustomer::findOrFail($id);
        $cleanIdNo = trim($customer->id_no);

        // 1. Building Owners & Associated Buildings
        $buildingOwners = \App\Models\Buildings\BhmBuildingOwner::with([
            'building.zone',
            'building.street',
            'building.buildingType',
            'units.floor'
        ])->where(function ($q) use ($cleanIdNo, $customer) {
            if (!empty($cleanIdNo)) {
                $q->where('id_card', $cleanIdNo)
                  ->orWhere('id_card', $customer->id_no);
            }
        })->get();

        $buildings = $buildingOwners->map(fn($o) => $o->building)->filter()->unique('id')->values();

        // 2. Associated Units (via building owner units or direct building_owner_id or building units)
        $ownerIds = $buildingOwners->pluck('id')->filter()->toArray();
        $pivotUnitIds = !empty($ownerIds) ? \Illuminate\Support\Facades\DB::table('bhm_building_owner_unit')
            ->whereIn('building_owner_id', $ownerIds)
            ->pluck('unit_id')
            ->toArray() : [];

        if (!empty($ownerIds) || !empty($pivotUnitIds)) {
            $units = \App\Models\Buildings\BhmUnit::with(['floor', 'building.zone', 'building.street'])
                ->where(function ($q) use ($ownerIds, $pivotUnitIds) {
                    if (!empty($ownerIds)) {
                        $q->whereIn('building_owner_id', $ownerIds);
                    }
                    if (!empty($pivotUnitIds)) {
                        $q->orWhereIn('id', $pivotUnitIds);
                    }
                })
                ->get()
                ->unique('id')
                ->values();
        } else {
            $units = collect();
        }

        // 3. Crafts & Businesses (الحرف والمهن والصناعات)
        $crafts = \App\Models\Crafts\BhmCraft::with(['statusRecord', 'craftType'])
            ->where(function ($q) use ($customer, $cleanIdNo) {
                $q->where('customer_id', $customer->id);
                if (!empty($cleanIdNo)) {
                    $q->orWhere('id_number', $cleanIdNo)
                      ->orWhere('id_number', $customer->id_no);
                }
            })
            ->get()
            ->unique('id')
            ->values();

        // Check Economical Activities
        $economicals = \App\Models\Buildings\BhmEconomical::with(['building.zone', 'building.street', 'unit', 'category'])
            ->where(function ($q) use ($cleanIdNo, $customer) {
                if (!empty($cleanIdNo)) {
                    $q->where('id_card', $cleanIdNo)
                      ->orWhere('id_card', $customer->id_no)
                      ->orWhereHas('owners', function($sq) use ($cleanIdNo, $customer) {
                          $sq->where('id_card', $cleanIdNo)
                             ->orWhere('id_card', $customer->id_no);
                      });
                }
            })
            ->get()
            ->unique('id')
            ->values();

        // 4. Transactions in Public Relations (قلم الجمهور)
        $transactions = \App\Models\Transactions\BhmTransaction::with(['transactionType', 'building.zone', 'departmentResponses'])
            ->where('customer_id', $customer->id)
            ->latest()
            ->get();

        if (!empty($cleanIdNo)) {
            $draftTxnIds = \App\Models\Transactions\BhmDraftTransaction::where(function($q) use ($cleanIdNo, $customer) {
                $q->where('national_id', $cleanIdNo)
                  ->orWhere('national_id', $customer->id_no);
            })->whereNotNull('transaction_id')->pluck('transaction_id')->toArray();

            if (!empty($draftTxnIds)) {
                $moreTxns = \App\Models\Transactions\BhmTransaction::with(['transactionType', 'building.zone', 'departmentResponses'])
                    ->whereIn('id', $draftTxnIds)
                    ->get();
                $transactions = $transactions->merge($moreTxns)->unique('id')->values();
            }
        }

        // 5. Licenses (رخص البناء)
        $licenses = \App\Models\Buildings\BhmLicenseForm::with(['building.zone', 'building.street', 'report'])
            ->whereHas('owner', function($q) use ($cleanIdNo, $customer) {
                if (!empty($cleanIdNo)) {
                    $q->where('id_card', $cleanIdNo)
                      ->orWhere('id_card', $customer->id_no);
                }
            })
            ->latest()
            ->get()
            ->unique('id')
            ->values();

        // 6. Subscriptions (اشتراكات المياه والخدمات)
        $subscriptions = \App\Models\Buildings\BhmSubscription::with(['building.zone', 'building.street', 'units'])
            ->where(function($q) use ($customer, $cleanIdNo) {
                if (!empty($cleanIdNo)) {
                    $q->where('id_number', $cleanIdNo)
                      ->orWhere('id_number', $customer->id_no);
                }
            })
            ->latest('id')
            ->get();

        // 7. Damage Records (منظومة حصر الأضرار)
        $damageRecords = collect();
        if (class_exists(\App\Models\Damage\DamageRecord::class)) {
            $damageRecords = \App\Models\Damage\DamageRecord::with(['zone', 'pricingCriteria'])
                ->where(function($q) use ($cleanIdNo, $customer) {
                    if (!empty($cleanIdNo)) {
                        $q->where('citizen_national_id', $cleanIdNo)
                          ->orWhere('citizen_national_id', $customer->id_no);
                    }
                })
                ->latest()
                ->get();
        }

        return compact(
            'customer',
            'buildingOwners',
            'buildings',
            'units',
            'crafts',
            'economicals',
            'transactions',
            'licenses',
            'subscriptions',
            'damageRecords'
        );
    }

    public function searchCitizenApi(Request $request)
    {
        $term = trim($request->input('citizen_id', $request->input('q', '')));
        
        if (empty($term)) {
            return response()->json([
                'found' => false,
                'message' => 'يرجى إدخال رقم الهوية أو الاسم للبحث.'
            ]);
        }

        $customer = BhmCustomer::where('id_no', $term)
            ->orWhere('id_no', 'like', "%{$term}%")
            ->orWhere('name', 'like', "%{$term}%")
            ->orWhere('mobile', 'like', "%{$term}%")
            ->first();

        if (!$customer) {
            return response()->json([
                'found' => false,
                'message' => 'المواطن غير مسجل مسبقاً في النظام. يمكنك إضافته الآن.'
            ]);
        }

        $cleanIdNo = trim($customer->id_no);

        // Fetch properties / buildings linked via BhmBuildingOwner
        $owners = \App\Models\Buildings\BhmBuildingOwner::with('building')
            ->where(function ($q) use ($cleanIdNo, $customer) {
                if (!empty($cleanIdNo)) {
                    $q->where('id_card', $cleanIdNo)
                      ->orWhere('id_card', $customer->id_no);
                }
            })
            ->get();

        $properties = [];
        foreach ($owners as $owner) {
            if ($owner->building) {
                $b = $owner->building;
                $properties[] = [
                    'id' => $b->id,
                    'piece_number' => $b->parcel_number ?? '',
                    'voucher_number' => $b->block_number ?? '',
                    'detailed_address' => $b->building_name ?? $customer->address ?? '',
                    'regulation_file_number' => $b->file_number ?? '',
                    'subscription_number' => '',
                    'relation_status' => $owner->mokalaf ? 'owner' : 'beneficiary',
                ];
            }
        }

        if (empty($properties)) {
            $buildings = \App\Models\Buildings\BhmBuilding::where('file_number', 'like', "%{$cleanIdNo}%")
                ->orWhere('building_name', 'like', "%{$customer->name}%")
                ->take(5)
                ->get();

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
        }

        return response()->json([
            'found' => true,
            'citizen' => [
                'id' => $customer->id,
                'citizen_id' => $customer->id_no,
                'id_no' => $customer->id_no,
                'name' => $customer->name,
                'phone' => $customer->mobile ?? $customer->telephone ?? '',
                'mobile' => $customer->mobile ?? '',
                'telephone' => $customer->telephone ?? '',
                'email' => $customer->email ?? '',
                'address' => $customer->address ?? '',
                'notes' => $customer->notes ?? '',
                'properties' => $properties,
            ],
            'customer' => [
                'id' => $customer->id,
                'id_no' => $customer->id_no,
                'name' => $customer->name,
                'mobile' => $customer->mobile ?? '',
                'telephone' => $customer->telephone ?? '',
                'email' => $customer->email ?? '',
                'address' => $customer->address ?? '',
                'notes' => $customer->notes ?? '',
            ]
        ]);
    }

    public function storeCitizenApi(Request $request)
    {
        $idNo = trim($request->input('id_no', $request->input('citizen_id', '')));
        $name = trim($request->input('name', ''));
        $mobile = trim($request->input('mobile', $request->input('phone', '')));
        $telephone = trim($request->input('telephone', ''));
        $email = trim($request->input('email', ''));
        $address = trim($request->input('address', ''));
        $notes = trim($request->input('notes', ''));

        if (empty($idNo) || strlen($idNo) < 7) {
            return response()->json([
                'status' => 'error',
                'message' => 'رقم الهوية الوطنية إلزامي ويجب ألا يقل عن 7 أرقام.'
            ], 422);
        }

        if (empty($name)) {
            return response()->json([
                'status' => 'error',
                'message' => 'اسم المواطن إلزامي.'
            ], 422);
        }

        $customer = BhmCustomer::updateOrCreate(
            ['id_no' => $idNo],
            [
                'name' => $name,
                'mobile' => $mobile,
                'telephone' => $telephone,
                'email' => $email,
                'address' => $address,
                'notes' => $notes,
            ]
        );

        if ($request->filled('building_id')) {
            $building = \App\Models\Buildings\BhmBuilding::find($request->input('building_id'));
            if ($building) {
                $nameParts = preg_split('/\s+/', $name);
                \App\Models\Buildings\BhmBuildingOwner::firstOrCreate(
                    [
                        'building_id' => $building->id,
                        'id_card' => $customer->id_no,
                    ],
                    [
                        'building_number' => $building->building_number,
                        'first_name' => $nameParts[0] ?? '',
                        'second_name' => $nameParts[1] ?? '',
                        'third_name' => $nameParts[2] ?? '',
                        'sur_name' => count($nameParts) > 3 ? implode(' ', array_slice($nameParts, 3)) : ($nameParts[3] ?? ''),
                        'phone_number' => $mobile ?: $telephone,
                        'mokalaf' => $request->boolean('mokalaf') ? 1 : 0,
                        'notes' => $notes,
                    ]
                );
            }
        }

        $savedProperties = [];
        if ($request->has('properties') && is_array($request->input('properties'))) {
            foreach ($request->input('properties') as $prop) {
                $building = \App\Models\Buildings\BhmBuilding::create([
                    'file_number' => !empty($prop['regulation_file_number']) ? $prop['regulation_file_number'] : ('REG-' . date('Y') . '-' . rand(100, 999)),
                    'parcel_number' => $prop['piece_number'] ?? null,
                    'block_number' => $prop['voucher_number'] ?? null,
                    'building_name' => $prop['detailed_address'] ?? 'عقار تابع للمواطن ' . $customer->name,
                ]);

                $nameParts = preg_split('/\s+/', $name);
                \App\Models\Buildings\BhmBuildingOwner::create([
                    'building_id' => $building->id,
                    'building_number' => $building->building_number,
                    'id_card' => $customer->id_no,
                    'first_name' => $nameParts[0] ?? '',
                    'second_name' => $nameParts[1] ?? '',
                    'third_name' => $nameParts[2] ?? '',
                    'sur_name' => count($nameParts) > 3 ? implode(' ', array_slice($nameParts, 3)) : ($nameParts[3] ?? ''),
                    'phone_number' => $mobile ?: $telephone,
                    'mokalaf' => 1,
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
            'message' => 'تم حفظ بيانات المواطن بنجاح في سجل المواطنين الموحد.',
            'customer' => [
                'id' => $customer->id,
                'id_no' => $customer->id_no,
                'name' => $customer->name,
                'mobile' => $customer->mobile,
                'telephone' => $customer->telephone,
                'email' => $customer->email,
                'address' => $customer->address,
                'notes' => $customer->notes,
            ],
            'citizen' => [
                'id' => $customer->id,
                'citizen_id' => $customer->id_no,
                'id_no' => $customer->id_no,
                'name' => $customer->name,
                'phone' => $customer->mobile,
                'mobile' => $customer->mobile,
                'properties' => $savedProperties,
            ]
        ]);
    }
}
