<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VFPromoController extends Controller
{
    public function addQuestions() : View
    {
        return view('admin.vf_promo_questions');
    }

    public function getQuestions() : View
    {
        $questions =  DB::connection("vf_connection")->table("PromoMsg")->get();
        return view("admin.vf_questions", compact("questions"));
    }

    public function saveQuestion(Request $request)
    {
        try {
            DB::connection("vf_connection")->table("PromoMsg")->insert([
                "Question" => $request->question,
                "Date"=> null,
                "Answers" => $request->answer,
                "PossibleAnswers" => $request->answer,
                "created_at" => now(),
                "updated_at" => now()
            ]);
            return response()->json([
                "status" => "success",
                "message" => "Question successfully added to Vodafone Promo Questions"
            ]);
        }catch(\Exception $e){
            Log::error($e);
            return response()->json([
                "status" => 'error',
                "message" => "Unable to add questions"
            ]);
        }
    }

    public function deleteQuestion(Request $request){
        try{
            DB::connection("vf_connection")->table("PromoMsg")
                ->where("Id", $request->id)->delete();
            return response()->json([
                "status" => "success",
                "message" => "Vodafone Promo Question successfully deleted"
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
