<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SMSContentController;
use App\Http\Controllers\Admin\ServicesController;
use App\Http\Controllers\Admin\ATPromoController;
use App\Http\Controllers\Admin\ShortcodeController;
use App\Http\Controllers\Admin\ThirdPartyController;
use App\Http\Controllers\Admin\VFPromoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', [AdminController::class, "login"])->name("login");
Route::post("/admin-login", [AdminController::class, "authenticateAdmin"]);
Route::get("/logout", [AdminController::class, "logout"]);

Route::group(['middleware' => 'auth'], function () {
    Route::get("/dashboard", [DashboardController::class, "index"]);
    Route::get("/add-content", [SMSContentController::class, "smsContent"]);
    Route::post("/load-excel-content", [SMSContentController::class, "loadExcelContent"]);
    Route::post("save-content", [SMSContentController::class, "saveContent"]);
    Route::get("/all-services", [SMSContentController::class, "allServices"]);
    Route::get("/new-service", [ServicesController::class, "newService"]);
    Route::post("/new-service", [ServicesController::class, "saveService"]);
    Route::get("/service-content", [SMSContentController::class, "getContent"]);
    Route::post("/delete-sms-content", [SMSContentController::class, "deleteContent"]);
    Route::get("/short-codes", [ShortcodeController::class, "getShortcodes"]);



    //AT PROMO
    Route::get("/add-at-questions", [ATPromoController::class, "addQuestions"]);
    Route::post("/add-at-questions", [ATPromoController::class, "saveQuestion"]);
    Route::post("load-at-excel-questions", [ATPromoController::class, "loadExcelQuestions"]);
    Route::get("/at-questions", [ATPromoController::class, "getQuestions"]);
    Route::post("/delete-at-question", [ATPromoController::class, "deleteQuestion"]);

    //VODAFONE PROMO
    Route::get("/add-vf-questions", [VFPromoController::class, "addQuestions"]);
    Route::post("/add-vf-questions", [VFPromoController::class, "saveQuestion"]);
    Route::get("/vf-questions", [VFPromoController::class, "getQuestions"]);
    Route::post("/add-vf-questions", [VFPromoController::class, "saveQuestion"]);
    Route::post("/delete-vf-question", [VFPromoController::class, "deleteQuestion"]);
    Route::post("/load-vf-excel-questions", [VFPromoController::class, "loadExcelQuestions"]);

    //ADMIN THIRD PART ROUTES
    Route::get("/new-third-party", [ThirdPartyController::class, "addThirdParty"]);
    Route::get("/third-parties", [ThirdPartyController::class, "getThirdParties"]);
    Route::post("/save-third-party", [ThirdPartyController::class, "saveCompany"]);
});

