<?php

namespace App\Http\Controllers\Buildings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buildings\BhmBuilding;
use App\Models\Buildings\BhmLicenseForm;
use App\Models\Buildings\BhmEconomical;
use Illuminate\Support\Facades\DB;

class BhmDashboardController extends Controller
{
    public function index()
    {
        $b = BhmBuilding::query();

        // ---- Buildings Stats ----
        $totalBuildings     = (clone $b)->count();
        $historicalBuildings = (clone $b)->where('historical', 1)->count();
        $abandonedBuildings = (clone $b)->where('abandond', 1)->count();
        $addingNewFloor     = (clone $b)->where('adding_new_floor', 1)->count();

        // construction_status: 0=غير مرخص, 1=مرخص ومستوفي, 2=مرخص وغير مستوفي, 3=مرخص ومتبقي طوابق
        $unlicensed          = (clone $b)->where('construction_status', 0)->count();
        $licensedCompliant   = (clone $b)->where('construction_status', 1)->count();
        $licensedNonCompliant= (clone $b)->where('construction_status', 2)->count();
        $licensedPendingFloor= (clone $b)->where('construction_status', 3)->count();
        $constructionUnknown = $totalBuildings - $unlicensed - $licensedCompliant - $licensedNonCompliant - $licensedPendingFloor;

        // ---- License Forms Stats ----
        $totalLicenses   = BhmLicenseForm::count();
        $approvedLicenses= BhmLicenseForm::where('status', 1)->count();
        $pendingLicenses = BhmLicenseForm::where('status', 0)->count();

        // ---- Economical Stats ----
        $totalEconomical   = BhmEconomical::count();
        $licensedEconomical  = BhmEconomical::where('isLicensed', 1)->count();
        $unlicensedEconomical= BhmEconomical::where('isLicensed', 2)->count();
        $dangerousEconomical = BhmEconomical::where('isDanger', 1)->count();

        $stats = [
            // Buildings
            'total_buildings'          => $totalBuildings,
            'unlicensed'               => $unlicensed,
            'licensed_compliant'       => $licensedCompliant,
            'licensed_non_compliant'   => $licensedNonCompliant,
            'licensed_pending_floor'   => $licensedPendingFloor,
            'construction_unknown'     => $constructionUnknown,
            'historical'               => $historicalBuildings,
            'abandoned'                => $abandonedBuildings,
            'adding_new_floor'         => $addingNewFloor,
            // Licenses
            'total_licenses'           => $totalLicenses,
            'approved_licenses'        => $approvedLicenses,
            'pending_licenses'         => $pendingLicenses,
            // Economical
            'total_economical'         => $totalEconomical,
            'licensed_economical'      => $licensedEconomical,
            'unlicensed_economical'    => $unlicensedEconomical,
            'dangerous_economical'     => $dangerousEconomical,
        ];

        return view('buildings.dashboard', compact('stats'));
    }
}
