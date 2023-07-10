<?php

namespace App\Http\Controllers\Mobile\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Ponds;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                DB::raw("FORMAT(PondDetails.PLReleaseDate,'dd-MM-yyyy') as PLReleaseDate"),
                'PondDetails.FeedSource',
                DB::raw("FORMAT(PondDetails.FeedReleaseDate,'dd-MM-yyyy') as FeedReleaseDate"),
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
