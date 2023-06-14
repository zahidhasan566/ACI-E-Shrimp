<?php

namespace App\Http\Controllers\Mobile\Farmer;

use App\Http\Controllers\Controller;
use App\Models\PondDetails;
use App\Models\Ponds;
use App\Models\User;
use App\Services\DeviceService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PondController extends Controller
{
    //STORE POND PREPARATION DATA
    public function storePondPreparationData(Request $request){

        $validator = Validator::make($request->all(), [
            'Location' => 'required',
            'LandSize' => 'required',
            'LandOwnershipBreakdown' => 'required',
            'Variety' => 'required',
            'NumberOfPond' => 'required',
            'Depth' => 'required',
            'PondPreparationMethod' => 'required',
            'PondImage' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }

        //Data Insert
        try {

            $getImage = $request->PondImage;
            $imageName = Auth::user()->Id.'-'.time().'.'.$getImage->extension();
            $imagePath = public_path(). '/assets/farmerPondImages/';
            $getImage->move($imagePath, $imageName);
            $imagePathWithName = $imagePath.$imageName;

            DB::beginTransaction();
            $pond = new Ponds();
            $pond->UserId = Auth::user()->Id;
            $pond->FarmerName = Auth::user()->Name;
            $pond->Location = $request->Location;
            $pond->LandSize = Auth::user()->LandSizeInBigha;
            $pond->LandOwnershipBreakdown = $request->LandOwnershipBreakdown;
            $pond->Variety = $request->Variety;
            $pond->NumberOfPond = $request->NumberOfPond;
            $pond->Depth = $request->Depth;
            $pond->PondPreparationMethod = $request->PondPreparationMethod;
            $pond->Depth = $request->Depth;
            $pond->PondImageName = $imageName;
            $pond->PondImagePath = $imagePathWithName;

            $pond->CreatedBy = Auth::user()->Id;
            $pond->UpdatedBy = Auth::user()->Id;
            $pond->CreatedAt = Carbon::now()->format('Y-m-d H:i:s');
            $pond->UpdatedAt = Carbon::now()->format('Y-m-d H:i:s');
            $pond->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pond Preparation Created Successfully'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage() . '-' . $exception->getLine()
            ], 200);
        }
    }


    //STORE POND OPERATION DATA
    public function storePondOperationData(Request $request){

        $validator = Validator::make($request->all(), [
            'PondId' => 'required',
            'SpfPl' => 'required',
            'Feed' => 'required',
            'BioSecurity' => 'required',
            'WaterPh' => 'required',
            'Salinity' => 'required',
            'PLSource' => 'required',
            'AmountOfLoanDue' => 'required',
            'Probiotic' => 'required',
            'PLQuantity' => 'required',
            'PLReleaseDate' => 'required|date',
            'FeedSource' => 'required',
            'FeedReleaseDate' => 'required|date',
            'DiseaseSymptoms' => 'required',
            'ExpectedProductionQuantity' => 'required',
            'ExpectedProductionDate' => 'required',
            'Grade' => 'required',
            'Transportation' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }

        //Data Insert
        try {

            DB::beginTransaction();
            $pondDetails = new PondDetails();
            $pondDetails->PondId = $request->PondId;
            $pondDetails->SpfPl = $request->SpfPl;
            $pondDetails->Feed = $request->Feed;
            $pondDetails->BioSecurity = $request->BioSecurity;
            $pondDetails->WaterPh = $request->WaterPh;
            $pondDetails->Salinity = $request->Salinity;
            $pondDetails->PLSource = $request->PLSource;
            $pondDetails->AmountOfLoanDue = $request->AmountOfLoanDue;
            $pondDetails->Probiotic = $request->Probiotic;
            $pondDetails->PLQuantity = $request->PLQuantity;
            $pondDetails->PLReleaseDate = $request->PLReleaseDate;
            $pondDetails->FeedSource = $request->FeedSource;
            $pondDetails->FeedReleaseDate = $request->FeedReleaseDate;
            $pondDetails->DiseaseSymptoms = $request->DiseaseSymptoms;
            $pondDetails->ExpectedProductionQuantity = $request->ExpectedProductionQuantity;
            $pondDetails->ExpectedProductionDate = $request->ExpectedProductionDate;
            $pondDetails->Grade = $request->Grade;
            $pondDetails->Transportation = $request->Transportation;

            $pondDetails->CreatedBy = Auth::user()->Id;
            $pondDetails->UpdatedBy = Auth::user()->Id;
            $pondDetails->CreatedAt = Carbon::now()->format('Y-m-d H:i:s');
            $pondDetails->UpdatedAt = Carbon::now()->format('Y-m-d H:i:s');
            $pondDetails->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pond Operation Created Successfully'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage() . '-' . $exception->getLine()
            ], 200);
        }

    }
    public function getAllPondInformation(){


        //GET USER BASED DATA
        try {
            $allPondInformation = Ponds::select(
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
                ->where('Ponds.CreatedBy',Auth::user()->Id)
                ->paginate(10);

            return response()->json([
                'data' =>$allPondInformation
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage() . '-' . $exception->getLine()
            ], 200);
        }


    }
}