<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\ATPromoQuestionImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ATPromoController extends Controller
{
    public function addQuestions() : View
    {
        return view('admin.at_promo_questions');
    }

    public function saveQuestion(Request $request)
    {
        try {
            DB::connection("at_mega_promo")->table("at_promo_msg")->insert([
                "question" => $request->question,
                "answer" => $request->answer,
                "possible_answer" => $request->answer,
                "created_at" => now(),
                "updated_at" => now()
            ]);
            return response()->json([
                "status" => "success",
                "message" => "Question successfully added to AT Promo Questions"
            ]);
        }catch(\Exception $e){
            Log::error($e);
            return response()->json([
                "status" => 'error',
                "message" => "Unable to add questions"
            ]);
        }
    }


    public function getQuestions() : View
    {
        $questions =  DB::connection("at_mega_promo")->table("at_promo_msg")->get();
        return view("admin.at_questions", compact("questions"));
    }

    public function loadExcelQuestions(Request $request){
        try {
            if($request->hasFile("excel_file")){
                Excel::import(new ATPromoQuestionImport(), request()->file('excel_file'));
                return response()->json([
                    "status" => "success",
                    "message" => "Question successfully added to AT Promo Questions"
                ]);
            }else{
                return response()->json([
                    "status" => "error",
                    "message" => "Please select an excel file with questions to upload"
                ]);
            }
        }catch(\Exception $e){
            Log::error($e);
            return response()->json([
                "status" => 'error',
                "message" => "Unable to load questions"
            ]);
        }
    }

    public function deleteQuestion(Request $request){
        try{
            DB::connection("at_mega_promo")->table("at_promo_msg")
                ->where("id", $request->id)->delete();
            return response()->json([
                "status" => "success",
                "message" => "AT Promo Question successfully deleted"
            ]);
        }catch (\Exception $e){
            Log::error($e);
            return response()->json([
                "status" => 'error',
                "message" => "Unable to delete question"
            ]);
        }
    }
}
