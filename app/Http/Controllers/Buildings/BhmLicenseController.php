<?php

namespace App\Http\Controllers\Buildings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buildings\BhmLicenseForm;
use App\Models\Buildings\BhmBuildingOwner;
use App\Models\Buildings\BhmCustomer;
use Illuminate\Support\Facades\DB;

class BhmLicenseController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['id_card', 'name', 'status', 'building_id', 'building_number', 'phone', 'subject']);

        $licenses = BhmLicenseForm::with(['owner', 'building.zone', 'building.street', 'report'])
            ->filter($filters)
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('buildings.licenses.index', compact('licenses', 'filters'));
    }

    public function show($id)
    {
        $license = BhmLicenseForm::with([
            'owner', 'building.zone', 'building.street', 'building.buildingType', 
            'floors', 'report', 'attachments',
            'legalOpinionReply', 'areaOpinionReply', 'planOpinionReply',
            'waterOpinionReply', 'sewerOpinionReply', 'collectionOpinionReply', 'gisOpinionReply'
        ])->findOrFail($id);

        return view('buildings.licenses.show', compact('license'));
    }

    public function searchCitizens(Request $request)
    {
        $q = trim($request->input('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json([
                'status' => 'success',
                'data' => [],
            ]);
        }

        $query = BhmCustomer::query();
        $words = array_filter(preg_split('/\s+/', $q));
        $normalized = str_replace(['أ', 'إ', 'آ'], 'ا', $q);

        $query->where(function ($sub) use ($q, $normalized, $words) {
            $sub->where('id_no', $q)
                ->orWhere('id_no', 'like', "{$q}%")
                ->orWhere('id_no', 'like', "%{$q}%")
                ->orWhere('mobile', 'like', "%{$q}%")
                ->orWhere('telephone', 'like', "%{$q}%");

            if (count($words) > 1) {
                $sub->orWhere(function ($wordSub) use ($words) {
                    foreach ($words as $w) {
                        $normW = str_replace(['أ', 'إ', 'آ'], 'ا', $w);
                        $wordSub->where(function ($wGroup) use ($w, $normW) {
                            $wGroup->where('name', 'like', "%{$w}%")
                                   ->orWhereRaw("REPLACE(REPLACE(REPLACE(name, 'أ', 'ا'), 'إ', 'ا'), 'آ', 'ا') LIKE ?", ["%{$normW}%"]);
                        });
                    }
                });
            } else {
                $sub->orWhere('name', 'like', "%{$q}%")
                    ->orWhereRaw("REPLACE(REPLACE(REPLACE(name, 'أ', 'ا'), 'إ', 'ا'), 'آ', 'ا') LIKE ?", ["%{$normalized}%"]);
            }
        });

        $citizens = $query->take(25)->get();

        return response()->json([
            'status' => 'success',
            'data' => $citizens->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'id_number' => $c->id_no,
                'id_no' => $c->id_no,
                'mobile' => $c->mobile ?: $c->telephone ?: '',
            ]),
        ]);
    }

    public function linkCitizen(Request $request, $id)
    {
        $request->validate([
            'client_id' => 'nullable|integer',
            'id_number' => 'nullable|string|max:50',
        ]);

        $license = BhmLicenseForm::with('owner')->findOrFail($id);

        $clientId = $request->input('client_id');
        $idNumber = trim($request->input('id_number', ''));

        $clientQuery = BhmCustomer::query();
        if ($clientId) {
            $clientQuery->where('id', $clientId);
        } elseif ($idNumber) {
            $clientQuery->where('id_no', $idNumber);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'يرجى تحديد المواطن أو رقم الهوية للربط.',
            ], 422);
        }

        $client = $clientQuery->first();

        if (!$client) {
            return response()->json([
                'status' => 'error',
                'message' => 'لم يتم العثور على بيانات المواطن في سجل المواطنين.',
            ], 404);
        }

        // Split name into Arabic components
        $parts = preg_split('/\s+/', trim($client->name));
        $firstName = $parts[0] ?? '';
        $secondName = $parts[1] ?? '';
        $thirdName = $parts[2] ?? '';
        $surName = count($parts) > 3 ? implode(' ', array_slice($parts, 3)) : ($parts[3] ?? '');

        $buildingNumber = $license->building_number ?? $license->building?->building_number;

        $ownerData = [
            'license_form_id' => $license->id,
            'building_id' => $license->building_id,
            'building_number' => $buildingNumber,
            'first_name' => $firstName,
            'second_name' => $secondName,
            'third_name' => $thirdName,
            'sur_name' => $surName,
            'id_card' => $client->id_no,
            'phone_number' => $client->mobile,
        ];

        $owner = $license->owner;
        if ($owner) {
            $owner->update($ownerData);
        } else {
            $ownerData['is_approve'] = 0;
            $ownerData['mokalaf'] = 0;
            $owner = BhmBuildingOwner::create($ownerData);
        }

        $license->touch();

        return response()->json([
            'status' => 'success',
            'message' => 'تم ربط بيانات مقدم الطلب بالمواطن بنجاح.',
            'citizen' => [
                'name' => $client->name,
                'id_number' => $client->id_no,
                'id_no' => $client->id_no,
                'mobile' => $client->mobile,
            ],
        ]);
    }

    public function unlinkCitizen($id)
    {
        $license = BhmLicenseForm::with('owner')->findOrFail($id);

        $owner = $license->owner;
        if ($owner) {
            if ($owner->subscriptions()->count() === 0 && $owner->units()->count() === 0) {
                $owner->delete();
            } else {
                $owner->update([
                    'license_form_id' => null,
                ]);
            }
        }

        $license->touch();

        return response()->json([
            'status' => 'success',
            'message' => 'تمت إزالة ربط المواطن بنجاح، وأصبحت بيانات مقدم الطلب فارغة.',
        ]);
    }
}
