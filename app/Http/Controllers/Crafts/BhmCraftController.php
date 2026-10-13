<?php

namespace App\Http\Controllers\Crafts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Crafts\BhmCraft;
use App\Models\Buildings\BhmCraftStatus;
use App\Models\Buildings\BhmCraftType;

class BhmCraftController extends Controller
{
    public function index(Request $request)
    {
        $query = BhmCraft::with(['customer', 'statusRecord', 'craftType']);

        if ($request->filled('business_name')) {
            $query->where('business_name', 'like', '%' . $request->business_name . '%');
        }

        if ($request->filled('id_number')) {
            $query->where('id_number', 'like', '%' . $request->id_number . '%');
        }
        
        if ($request->filled('mobile')) {
            $query->where('mobile', 'like', '%' . $request->mobile . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $crafts = $query->paginate(20)->withQueryString();
        $statuses = \Illuminate\Support\Facades\DB::table('bhm_craft_status')->get();

        return view('crafts.crafts.index', compact('crafts', 'statuses'));
    }
}
