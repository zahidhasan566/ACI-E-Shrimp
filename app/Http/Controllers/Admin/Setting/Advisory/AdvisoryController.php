<?php

namespace App\Http\Controllers\Admin\Setting\Advisory;

use App\Http\Controllers\Controller;
use App\Models\ShrimpAdvisory;
use App\Services\DeviceService;
use App\Services\ImageBase64Service;
use BaseUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdvisoryController extends Controller
{
    public function index(Request $request){
        $take = $request->take;
        $search = $request->search;

        $advisory = ShrimpAdvisory::join('Users', 'Users.Id', 'ShrimpAdvisory.CreatedBy')
            ->where(function ($q) use ($search) {
                $q->where('ShrimpAdvisory.ShrimpAdvisoryId', 'like', '%' . $search . '%');
                $q->orWhere('ShrimpAdvisory.Attachment', 'like', '%' . $search . '%');
            })
            ->orderBy('ShrimpAdvisory.ShrimpAdvisoryId', 'asc')
            ->select('ShrimpAdvisory.ShrimpAdvisoryId','ShrimpAdvisory.AttachmentName', 'ShrimpAdvisory.Attachment','ShrimpAdvisory.AttachmentPath','ShrimpAdvisory.DateFrom', 'ShrimpAdvisory.DateTo',
                'ShrimpAdvisory.Status', 'Users.Name as Entry By', 'ShrimpAdvisory.CreatedAt');

        if (!empty($request->filters[0]['value'])) {
            $first = $request->filters[0]['value'][0];
            $second = $request->filters[0]['value'][1];

            $start_date = date("Y-m-d", strtotime($first));
            $end_date = date("Y-m-d", strtotime($second));

            $advisory = $advisory->whereBetween(DB::raw("CONVERT(DATE,ShrimpAdvisory.CreatedAt)"), [$start_date, $end_date]);
        }

        return $advisory->paginate($take);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'Attachment' => 'required|string',
            'EventName' => 'required|string',
            'Status' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }
        //Data Insert
        try {
            $uploadFileUrl = DeviceService::uploadFileUrl();
            DB::beginTransaction();
            $event = new ShrimpAdvisory();
            $event->AttachmentName = $request->EventName;
            $event->Attachment = ImageBase64Service::imageUpload($request->Attachment, 'EventFile', public_path('assets/advisory/'));
            $Path = $uploadFileUrl. 'assets/advisory/';
            $filePath = $Path.$event->Attachment;
            $event->AttachmentPath = $filePath;

            $event->DateFrom = $request->EventStartFrom;
            $event->DateTo = $request->EventEndTo;
            $event->Status = $request->Status;

            $event->CreatedBy =Auth::user()->Id;
            $event->UpdatedBy =Auth::user()->Id;
            $event->CreatedAt = Carbon::now()->format('Y-m-d H:i:s');
            $event->UpdatedAt = Carbon::now()->format('Y-m-d H:i:s');
            $event->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Event Created Successfully'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage() . '-' . $exception->getLine()
            ], 500);
        }
    }

    public function getEventInfo($EventID)
    {
        $event = ShrimpAdvisory::where('ShrimpAdvisoryId', $EventID)->first();

        return response()->json([
            'status' => 'success',
            'data' => $event
        ]);
    }

    public function updateEventData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'EventName' => 'required|string',
            'Status' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }
        //Data Insert
        try {
            DB::beginTransaction();
            $event = ShrimpAdvisory::where('ShrimpAdvisoryId', $request->EventID)->first();

            $event->AttachmentName = $request->EventName;
            if($request->AttachmentFlag ===1){
                $event->Attachment = ImageBase64Service::imageUpload($request->Attachment, 'EventFile', public_path('assets/advisory/'));
                $uploadFileUrl = DeviceService::uploadFileUrl();
                $Path = $uploadFileUrl. 'assets/advisory/';
                $filePath = $Path.$event->Attachment;
                $event->AttachmentPath = $filePath;
            }
            else{
                $event->Attachment = $request->Attachment;
            }
            $event->DateFrom = $request->EventStartFrom;
            $event->DateTo = $request->EventEndTo;
            $event->Status = $request->Status;

            $event->CreatedBy =Auth::user()->Id;
            $event->UpdatedBy =Auth::user()->Id;
            $event->CreatedAt = Carbon::now()->format('Y-m-d H:i:s');
            $event->UpdatedAt = Carbon::now()->format('Y-m-d H:i:s');
            $event->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Event Updated Successfully'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ], 500);
        }
    }
}
