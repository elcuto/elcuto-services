<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\SMSContentImport;
use App\Models\AllService;
use Database\Seeders\AllServiceTableSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;


class SMSContentController extends Controller
{
    public function smsContent() : View
    {
        $services = AllService::all();
        return view("admin.sms_content", compact("services"));
    }

    public function saveContent(Request $request){
        try{
            $service = AllService::where("id", $request->service_id)->first();
            DB::connection("test_db_pgsql")->table($service->msg_table_name)->insert([
                "message" => $request->sms_content,
                "created_at" => now(),
                "updated_at" => now(),
            ]);
            return response()->json([
                "status" => "success",
                "message" => "SMS Content successfully saved"
            ]);
        }catch(\Exception $e){
            Log::error($e);
            return response()->json([
                "status" => "error",
                "message" => "Unable to add SMS content. Please try again"
            ]);
        }
    }


    public function loadExcelContent(Request $request){
        try{
            $service = AllService::where("id", $request->service_id)->first();
            if($service == null){
                return response()->json([
                    "status" => "error",
                    "message" => "Please select a service"
                ]);
            }
            Excel::import(new SMSContentImport($service->msg_table_name), request()->file('excel_file'));
            return response()->json([
                "status" => "success",
                "message" => "Content successfully queued"
            ]);
        }catch(\Exception $e){
            Log::error($e);
            return response()->json([
                "status" => "error",
                "message" => "Unable to load excel file"
            ]);
        }
    }

    public function allServices() : View
    {
        $services = AllService::all();
        return view('admin.services', compact("services"));
    }

    public function getContent(Request $request)
    {
        $service = AllService::where("id", $request->query("service_id", null))->first();
        if($service == null){
            return back()->with("error", "Service not found");
        }
        $contents = DB::connection("test_db_pgsql")->table($service->msg_table_name)->orderBy("id", "DESC")->get();
        return view("admin.service_contents", compact("contents", "service"));
    }

    public function deleteContent(Request $request)
    {
        try{
            $service = AllService::where("id", $request->service_id)->first();
            if($service){
                DB::connection("test_db_pgsql")->table($service->msg_table_name)->where("id", $request->content_id)->delete();
            }
            return response()->json([
                "status" => "success",
                "message" => "SMS Content successfully deleted"
            ]);
        }catch(\Exception $e){
            Log::error($e);
            return response()->json([
                'status' => 'error',
                'message' => 'Unable to delete content. Something went wrong'
            ]);
        }
    }

}
