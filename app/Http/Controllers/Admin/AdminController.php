<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function login(){
        return view("admin.login");
    }

    public function authenticateAdmin(Request $request){
        try{
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return response()->json([
                    "status" => "success",
                    "url" => url("/dashboard")
                ]);
            }else{
                return response()->json([
                    "status" => "error",
                    "message" =>  "Invalid Login Credentails"
                ]);
            }
        }catch(\Exception $e){
            Log::error($e);
            return response()->json([
                'status' => "error",
                "message" => "OOps something went wrong. Unable to login"
            ]);
        }
    }


    public function logout(){
        Auth::logout();
        return redirect()->to("/");
    }
}
