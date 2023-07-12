<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\BuyerProducts;
use App\Models\Harvest;
use App\Models\Ponds;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //
    public function index(){
        $totalFarmers = User::where('RoleId','Farmer')->count();
        $totalPonds = Ponds::all()->count();
        $totalHarvest = Harvest::all()->count();
        $totalProduct = BuyerProducts::all()->count();

        return response()->json([
            'status' => 'success',
            'totalFarmers' =>$totalFarmers,
            'totalPonds' =>$totalPonds,
            'totalHarvest' =>$totalHarvest,
            'totalProduct' =>$totalProduct,
        ]);
    }
}
