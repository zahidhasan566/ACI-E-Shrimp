<?php

namespace App\Http\Controllers\Mobile\Buyer;

use App\Http\Controllers\Controller;
use App\Models\BuyerProducts;
use App\Models\PondDetails;
use App\Services\DeviceService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BuyerProductController extends Controller
{
    public function storeProductInformation(Request $request){
        $validator = Validator::make($request->all(), [
            'ProductName' => 'required',
            'ProductImageName' => 'required',
            'ProductDetails' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }  //Data Insert
        try {

            DB::beginTransaction();
            $buyerProducts = new BuyerProducts();
            $buyerProducts->ProductName = $request->ProductName;
            $buyerProducts->ProductImageName = $request->ProductImageName;
            $buyerProducts->ProductDetails = $request->ProductDetails;
            $buyerProducts->Status = 1;


            $buyerProducts->CreatedBy = Auth::user()->Id;
            $buyerProducts->UpdatedBy = Auth::user()->Id;
            $buyerProducts->CreatedAt = Carbon::now()->format('Y-m-d H:i:s');
            $buyerProducts->UpdatedAt = Carbon::now()->format('Y-m-d H:i:s');
            $buyerProducts->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Buyer Product Created Successfully'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage() . '-' . $exception->getLine()
            ], 200);
        }
    }

    public function getAllProductInformation(Request $request){
        $page = $request->skip;
        $limit = 50;
        $offset = $page == 1 ? 0 :  $limit * ($page - 1);

        $validator = Validator::make($request->all(), [
            'skip' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }
        else {
            try {
                $uploadFileUrl = DeviceService::uploadFileUrl();
                $Path = $uploadFileUrl . 'assets/buyer/products/';

                $products = BuyerProducts::select(
                    'BuyerProducts.BuyerProductId',
                    'BuyerProducts.ProductName',
                    'BuyerProducts.ProductImageName',
                    DB::raw("(CASE WHEN BuyerProducts.ProductName IS NOT NULL THEN  '$Path'+ ProductImageName ELSE NULL END) AS ImageLink "),
                    'BuyerProducts.ProductName ',
                    'BuyerProducts.ProductDetails',
                    'BuyerProducts.Status',
                )->skip($offset)->take($limit)->get();

                return response()->json([
                    'data' => $products
                ]);
            }catch (\Exception $exception) {
                return response()->json([
                    'status' => 'error',
                    'message' => $exception->getMessage() . '-' . $exception->getLine()
                ], 200);
            }
        }
    }
}
