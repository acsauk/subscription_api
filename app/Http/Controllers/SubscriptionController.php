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

    public function unsubscribe(Request $request)
    {
      $msisdn = $request->input('msisdn');
      $product_id = $request->input('product_id');

      $subscription = Subscription::where('msisdn', $msisdn)
                                  ->where('product_id', $product_id)
                                  ->first();
      $subscription->active = 0;

      $subscription->save();

      return $subscription;
    }

    public function search(Request $request)
    {
      $msisdn = $request->input('msisdn') ?: '';
      $product_id = $request->input('product_id') ?: '';

      $subscription;

      if($msisdn && $product_id) {
        $subscription = Subscription::where('msisdn', $msisdn)
                                    ->where('product_id', $product_id)
                                    ->first();
      } elseif ($msisdn && $product_id == '') {
        $subscription = Subscription::where('msisdn', $msisdn)->get();
      }

      return $subscription;
    }
}
