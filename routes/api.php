<?php

use Illuminate\Http\Request;
use App\Subscription;
use App\Htpp\Controllers\SubscriptionController;

Route::get('/subscriptions/subscribe', 'SubscriptionController@subscribe');
Route::post('/subscriptions/subscribe', 'SubscriptionController@subscribe');

Route::get('/subscriptions/unsubscribe', 'SubscriptionController@unsubscribe');
Route::post('/subscriptions/unsubscribe', 'SubscriptionController@unsubscribe');

Route::get('/subscriptions', 'SubscriptionController@search');
Route::post('/subscriptions', 'SubscriptionController@search');
