<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\TermsController;
use App\Http\Controllers\socialController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\PerposalController;
use App\Http\Controllers\API\categoryController;
use App\Http\Controllers\API\ProductsController;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\API\superAdmin\ProductSuperController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();

});
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

/**
 *  user routes
 */

// **** product routes
Route::post("/updateProduct/{id}",[ProductsController::class,'update']);
Route::get("/showProduct/{id}",[ProductsController::class,'show']);
Route::get("/allProduct",[ProductsController::class,'showAll']);
Route::get("/adminAllProduct",[ProductsController::class,'index']);
Route::get("/editProduct/{id}",[ProductsController::class,'edit']);
Route::get("/deleteProduct/{id}",[ProductsController::class,'delete']);

//   ******* auth middleware ********************************
Route::middleware('auth:api')->group(function (){
    
    Route::post("/forgetPassword",[ForgotPasswordController::class,'sendEmail']);
    Route::post("/resetPassword",[ForgotPasswordController::class,'resetEmail']);
    Route::post("/addsubcategory/{category_id}",[categoryController::class,'create']);
    Route::post("/products",[ProductsController::class,'create']);

    Route::post("/proposal/{product}",[PerposalController::class,'create_perposal']);
    Route::get("/get_proposal",[PerposalController::class,'get_perposal']);
    Route::post("/proposal_status/{perposal}",[PerposalController::class,'perposal_status']);

});
Route::middleware(['auth:api','chat'])->group(function (){

    Route::post("/chat/{receiver_id}",[ChatController::class,'create_message']);
    Route::get("/chat/{id}",[ChatController::class,'get_messages']);
    Route::get("/chats",[ChatController::class,'all_chats']);

});
//         search category routes

Route::get("/category_product/{id}",[categoryController::class,'searchUsingCategory']);
Route::get("/subcategory_product/{id}",[categoryController::class,'searchUsingSubCategory']);
Route::get("/subsubcategory_product/{id}",[categoryController::class,'searchUsingSubsubCategory']);



/**
 *
 *              Admin routes
 *
 */

Route::get("/showAllCategory",[categoryController::class,'index']);

Route::prefix('admin')->middleware(['auth:api','superAdmin'])->group(function ()
{
    Route::get("/allUser",[UserController::class,'index']);
    Route::post("/deleteProduct/{id}",[ProductSuperController::class,'delete']);
    //              category routes
    Route::get("/showCategory/{id}",[categoryController::class,'show']);
    Route::post("/deleteCategory/{id}",[categoryController::class,'delete']);
    Route::post("/createCategory",[categoryController::class,'create']);
    Route::post("/subCategory/{id}",[categoryController::class,'createSubCategory']);
    Route::post("/subsubCategory/{id}",[categoryController::class,'createSubsubCategory']);
    Route::post("/createTerms",[TermsController::class,'create']);
    Route::post("/createAbout",[AboutController::class,'create']);
    
} );

// ********* Terms ****************
Route::get("/terms",[TermsController::class,'index']);
Route::get("/About",[AboutController::class,'index']);

// ************* auth with social accounts ****************

Route::group(['middleware' => ['api']], function () {
    Route::get("auth/facebook/callback",[socialController::class,'handelFacebookCallback']);
    Route::get("auth/facebook",[socialController::class,'redirectToFacebook']);
    Route::get("auth/google/callback",[socialController::class,'handelGoogleCallback']);
    Route::get("auth/google",[socialController::class,'redirectToGoogle']);
    // your routes here
});

