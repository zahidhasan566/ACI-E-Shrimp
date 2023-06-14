<?php

namespace App\Http\Controllers\Mobile\Farmer;

use App\Http\Controllers\Controller;
use App\Models\ShrimpAdvisory;
use Illuminate\Http\Request;

class ShrimpAdvisoryController extends Controller
{
    public function getAllShrimpAdvisoryInformation(){

        $shrimpAdvisoryData = ShrimpAdvisory::select(
            'ShrimpAdvisory.ShrimpAdvisoryId',
            'ShrimpAdvisory.AttachmentName',
            'ShrimpAdvisory.Attachment',
            'ShrimpAdvisory.AttachmentPath',
            'ShrimpAdvisory.DateFrom',
            'ShrimpAdvisory.DateTo',
            'ShrimpAdvisory.Status',

        ) ->paginate(10);

        return response()->json([
            'data' => $shrimpAdvisoryData,
        ]);
    }
}
