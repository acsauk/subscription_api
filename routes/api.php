<?php

use Illuminate\Http\Request;
use App\Subscription;
use App\Htpp\Controllers\SubscriptionController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/subscriptions/subscribe', 'SubscriptionController@subscribe');
Route::get('/subscriptions/unsubscribe', 'SubscriptionController@unsubscribe');
Route::get('/subscriptions', 'SubscriptionController@search');
