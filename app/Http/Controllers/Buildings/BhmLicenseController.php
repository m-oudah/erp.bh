<?php

namespace App\Http\Controllers\Buildings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buildings\BhmLicenseForm;

class BhmLicenseController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['id_card', 'name', 'status', 'building_id', 'building_number', 'phone', 'subject']);

        $licenses = BhmLicenseForm::with(['building.zone', 'building.street', 'report'])
            ->filter($filters)
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('buildings.licenses.index', compact('licenses', 'filters'));
    }

    public function show($id)
    {
        $license = BhmLicenseForm::with([
            'building.zone', 'building.street', 'building.buildingType', 
            'floors', 'report', 'attachments',
            'legalOpinionReply', 'areaOpinionReply', 'planOpinionReply',
            'waterOpinionReply', 'sewerOpinionReply', 'collectionOpinionReply', 'gisOpinionReply'
        ])->findOrFail($id);

        return view('buildings.licenses.show', compact('license'));
    }
}
