<?php

namespace App\Http\Controllers\Mobile\Factory;

use App\Http\Controllers\Controller;
use App\Models\Ponds;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FarmerController extends Controller
{
    public function getAllFarmerInformation(Request $request){

        $page = $request->skip;
        $limit = 20;
        $offset = $page == 1 ? 0 :  $limit * ($page - 1);

        $validator = Validator::make($request->all(), [
            'skip' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }
        else {
            //GET FARMER DATA
            try {
                $allFarmerInformation = Ponds::select(
                    'Ponds.PondId',
                    'Ponds.Location',
                    'Ponds.PondSizeInBigha',
                    'Ponds.LandOwnershipBreakdown',
                    'Ponds.Variety',
                    'Ponds.NumberOfPond',
                    'Ponds.Depth',
                    'Ponds.PondPreparationMethod',
                    'Ponds.PondImagePath',
                    DB::raw("FORMAT(Ponds.CreatedAt,'dd-MM-yyyy') as CreatedAt"),
                )->withCount('PondOperationInfo')->with('PondOperationInfo')
                    ->skip($offset)->take($limit)->get();

                $totalAllFarmerInformation = $allFarmerInformation->count();

                return response()->json([
                    'AllFarmerInformationCount' => $totalAllFarmerInformation,
                    'data' => $allFarmerInformation

                ]);
            } catch (\Exception $exception) {
                return response()->json([
                    'status' => 'error',
                    'message' => $exception->getMessage() . '-' . $exception->getLine()
                ], 200);
            }
        }


    }
}
