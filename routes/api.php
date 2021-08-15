<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SectionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::apiResource('questions', QuestionController::class);
Route::apiResource('sections', SectionController::class);
Route::apiResource('users', UserController::class);

Route::post("register",[AuthController::class,"register"]);
Route::post("login",[AuthController::class,"login"]);

Route::middleware('auth:sanctum')->group(
  function () {
      Route::get("user",[AuthController::class,"user"]);
      Route::delete("logout",[AuthController::class,"logout"]);
      Route::put("user/info",[AuthController::class,"updateInfo"]);
      Route::put("user/password",[AuthController::class,"updatePassword"]);
      Route::get("sections/{id}/review_questions",[SectionController::class,"reviewQuestions"]);
      Route::post("answer_reviews",[SectionController::class,"answerReviews"]);
      Route::post("answer_questions",[SectionController::class,"answerQuestions"]);
      Route::get("sections/{id}/new_questions",[SectionController::class,"newQuestions"]);
});