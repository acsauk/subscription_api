<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subscription;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
      return Subscription::create($request->all());
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

      if($msisdn && $product_id) {
        return Subscription::where('msisdn', $msisdn)
                                    ->where('product_id', $product_id)
                                    ->first();
      } elseif ($msisdn && $product_id == '') {
        return Subscription::where('msisdn', $msisdn)->get();
      } elseif ($msisdn == '' && $product_id) {
        return Subscription::where('product_id', $product_id)->get();
      }
    }
}
