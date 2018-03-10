<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subscription;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
      $subscription = Subscription::create($request->all());
      return $subscription;
    }
}
