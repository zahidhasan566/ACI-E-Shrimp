<?php

namespace App\Http\Controllers\Mobile\Factory;

use App\Http\Controllers\Controller;
use App\Models\Ponds;
use App\Models\User;
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
                $farmerList =  User::
                select(
                        'Id',
                        'Name',
                        'Email',
                        'Mobile',
                        'NID',
                        'Cluster',
                        'PondSizeInBigha',
                        'Address',
                        'RoleID',
                )   ->withCount('getPondPreparation')
                    ->with('getPondPreparation','getPondPreparation.PondOperationInfo','getPondPreparation.harvestInfo')
                    ->with('getPondPreparation',function($q) {
                        $q->withCount('PondOperationInfo','harvestInfo');
                    })
                    ->where('RoleID','Farmer')
                    ->skip($offset)->take($limit)->get();

                $totalAllFarmerInformation =  User::where('RoleID','Farmer')->count();

                return response()->json([
                    'AllFarmerInformationCount' => $totalAllFarmerInformation,
                    'data' => $farmerList

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
