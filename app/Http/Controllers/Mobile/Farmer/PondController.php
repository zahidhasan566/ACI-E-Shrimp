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
            'PondSizeInBigha' => 'required',
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
            $uploadFileUrl = DeviceService::uploadFileUrl();
            $getImage = $request->PondImage;
            $imageName = Auth::user()->Id.'-'.time().'.'.$getImage->extension();
            $Path = $uploadFileUrl. 'assets/farmerPondImages/';
            $imagePath = public_path(). '/assets/farmerPondImages/';
            $getImage->move($imagePath, $imageName);
            $imagePathWithName = $Path.$imageName;

            DB::beginTransaction();
            $pond = new Ponds();
            $pond->UserId = Auth::user()->Id;
            $pond->FarmerName = Auth::user()->Name;
            $pond->Location = $request->Location;
            $pond->PondSizeInBigha = $request->PondSizeInBigha;
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
    public function getAllPondPreparationData(Request $request){
        $page = $request->skip;
        $limit = 20;
        $offset = $page == 1 ? 0 :  $limit * ($page - 1);

        $validator = Validator::make($request->all(), [
            'skip' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }


        //GET USER BASED DATA
        try {

            $allPondInformation = Ponds::select(
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
            )
                ->leftjoin('Users','Users.Id','Ponds.CreatedBy')
                ->where('Ponds.CreatedBy',Auth::user()->Id)
                ->skip($offset)->take($limit)->get();

            $allPondInformationCount = Ponds::where('CreatedBy',Auth::user()->Id)->count();
            return response()->json([
                'allPondPreparationCount' =>$allPondInformationCount,
                'data' =>$allPondInformation
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage() . '-' . $exception->getLine()
            ], 200);
        }

    }

    //Get All Pond Preparation data
    public function getAllPondOperationData(Request $request){

        $page = $request->skip;
        $limit = 20;
        $offset = $page == 1 ? 0 :  $limit * ($page - 1);

        $validator = Validator::make($request->all(), [
            'PondId' => 'required',
            'skip' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }
        //GET USER BASED DATA
        try {
            $allPondInformation = PondDetails::select(
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
                DB::raw("FORMAT(PondDetails.ExpectedProductionDate,'dd-MM-yyyy') as ExpectedProductionDate"),
                'PondDetails.Grade',
                'PondDetails.Transportation',
                DB::raw("FORMAT(PondDetails.CreatedAt,'dd-MM-yyyy') as CreatedAt"),
            )
                ->where('PondDetails.PondId',$request->PondId)
                ->where('PondDetails.CreatedBy',Auth::user()->Id)
                ->skip($offset)->take($limit)->get();

            $allPondOperationCount = PondDetails::where('CreatedBy',Auth::user()->Id)->count();

            return response()->json([
                'allPondOperationCount' =>$allPondOperationCount,
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
