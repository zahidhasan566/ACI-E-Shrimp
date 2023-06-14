<?php

namespace App\Http\Controllers\Mobile\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Ponds;
use Illuminate\Http\Request;

class BuyerFarmerController extends Controller
{
    public function getAllFarmerInformation(){


        //GET FARMER DATA
        try {
            $allFarmerInformation = Ponds::select(
                'Ponds.Location',
                'Ponds.LandSize',
                'Ponds.LandOwnershipBreakdown',
                'Ponds.Variety',
                'Ponds.NumberOfPond',
                'Ponds.Depth',
                'Ponds.PondPreparationMethod',
                'Ponds.PondImagePath',

                'PondDetails.PondId',
                'PondDetails.SpfPl',
                'PondDetails.Feed',
                'PondDetails.BioSecurity',
                'PondDetails.WaterPh',
                'PondDetails.Salinity',
                'PondDetails.PLSource',
                'PondDetails.AmountOfLoanDue',
                'PondDetails.Probiotic',
                'PondDetails.PLQuantity',
                'PondDetails.PLReleaseDate',
                'PondDetails.FeedSource',
                'PondDetails.FeedReleaseDate',
                'PondDetails.DiseaseSymptoms',
                'PondDetails.ExpectedProductionQuantity',
                'PondDetails.ExpectedProductionDate',
                'PondDetails.Grade',
                'PondDetails.Transportation'
            )
                ->leftjoin('PondDetails','PondDetails.PondId','Ponds.PondId')
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
