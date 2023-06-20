<?php

namespace App\Http\Controllers\Mobile\Farmer;

use App\Http\Controllers\Controller;
use App\Models\ShrimpAdvisory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShrimpAdvisoryController extends Controller
{
    public function getAllShrimpAdvisoryInformation(Request $request){

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
            try {
                $shrimpAdvisoryData = ShrimpAdvisory::select(
                    'ShrimpAdvisory.ShrimpAdvisoryId',
                    'ShrimpAdvisory.AttachmentName',
                    'ShrimpAdvisory.Attachment',
                    'ShrimpAdvisory.AttachmentPath',
                    'ShrimpAdvisory.DateFrom',
                    'ShrimpAdvisory.DateTo',
                    'ShrimpAdvisory.Status',

                ) ->skip($offset)->take($limit)->get();

                return response()->json([
                    'data' => $shrimpAdvisoryData,
                ]);
            }
            catch (\Exception $exception) {
                return response()->json([
                    'status' => 'error',
                    'message' => $exception->getMessage() . '-' . $exception->getLine()
                ], 200);
            }

        }


    }
}
