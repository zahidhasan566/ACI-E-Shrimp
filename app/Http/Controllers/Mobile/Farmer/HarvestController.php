<?php

namespace App\Http\Controllers\Mobile\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Harvest;
use App\Models\PondDetails;
use App\Models\Ponds;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HarvestController extends Controller
{
    //STORE HARVEST DATA
    public function storeHarvestData(Request $request){

        $validator = Validator::make($request->all(), [
            'DateOfProduction' => 'required|date',
            'DateOfSalesAtFactoryGate' => 'required|date',
            'AmountOfShrimp' => 'required',
            'SalesPrice' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }

        //Data Insert
        try {
            DB::beginTransaction();
            $harvest = new Harvest();
            $harvest->DateOfProduction = $request->DateOfProduction;
            $harvest->DateOfSalesAtFactoryGate = $request->DateOfSalesAtFactoryGate;
            $harvest->AmountOfShrimp = $request->AmountOfShrimp;
            $harvest->SalesPrice = $request->SalesPrice;

            $harvest->CreatedBy = Auth::user()->Id;
            $harvest->UpdatedBy = Auth::user()->Id;
            $harvest->CreatedAt = Carbon::now()->format('Y-m-d H:i:s');
            $harvest->UpdatedAt = Carbon::now()->format('Y-m-d H:i:s');
            $harvest->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Harvest Created Successfully'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage() . '-' . $exception->getLine()
            ], 200);
        }
    }

    public function getAllHarvestData(Request $request){
        $skip = $request->skip;
        $limit = 10;

        $validator = Validator::make($request->all(), [
            'skip' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }

        //GET USER BASED DATA
        try {
            $allHarvestData = Harvest::select(
                'Harvest.HarvestId',
                DB::raw("FORMAT(Harvest.DateOfProduction,'dd-MM-yyyy') as DateOfProduction"),
                DB::raw("FORMAT(Harvest.DateOfSalesAtFactoryGate,'dd-MM-yyyy') as DateOfSalesAtFactoryGate"),
                'Harvest.AmountOfShrimp',
                'Harvest.SalesPrice',
                DB::raw("FORMAT(Harvest.CreatedAt,'dd-MM-yyyy') as CreatedAt"),

            )
                ->where('Harvest.CreatedBy',Auth::user()->Id)
                ->skip($skip)->take($limit)->get();

            return response()->json([
                'data' =>$allHarvestData
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage() . '-' . $exception->getLine()
            ], 200);
        }
    }
}
