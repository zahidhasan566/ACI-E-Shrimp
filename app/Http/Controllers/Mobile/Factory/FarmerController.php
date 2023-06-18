<?php

namespace App\Http\Controllers\Mobile\Factory;

use App\Http\Controllers\Controller;
use App\Models\Ponds;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FarmerController extends Controller
{
    public function getAllFarmerInformation(){


        //GET FARMER DATA
        try {
            $allFarmerInformation = Ponds::select(
                'Ponds.PondId',
                'Ponds.Location',
                'Ponds.LandSize',
                'Ponds.LandOwnershipBreakdown',
                'Ponds.Variety',
                'Ponds.NumberOfPond',
                'Ponds.Depth',
                'Ponds.PondPreparationMethod',
                'Ponds.PondImagePath',
                'Ponds.CreatedAt',
            )->with('PondOperationInfo:PondId,SpfPl,Feed,BioSecurity,WaterPh,Salinity,PLSource,AmountOfLoanDue,Probiotic,PLQuantity,PLReleaseDate,FeedSource,FeedReleaseDate,DiseaseSymptoms,ExpectedProductionQuantity,ExpectedProductionDate,Grade,Transportation,CreatedAt')
                ->paginate(10);

            return response()->json([
                'data' =>$allFarmerInformation
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage() . '-' . $exception->getLine()
            ], 200);
        }


    }
}
