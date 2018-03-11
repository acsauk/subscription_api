<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subscription;
use GuzzleHttp\Client;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        return Subscription::create([
          'msisdn' => $request->input('msisdn'),
          'product_id' => $request->input('product_id'),
          'subscribed_date' => Carbon::now()->format('Y-m-d')
        ]);
    }

    public function unsubscribe(Request $request)
    {
        $msisdn = $request->input('msisdn');
        $product_id = $request->input('product_id');

        $subscription = $this->get_subscription($msisdn, $product_id);
        $subscription->active = 0;
        $subscription->save();

        return $subscription;
    }

    public function search(Request $request)
    {
        $msisdn = $request->input('msisdn') ?: '';
        $product_id = $request->input('product_id') ?: '';

        if($msisdn && $product_id) {
            return $this->get_subscription($msisdn, $product_id);
        } elseif ($msisdn && $product_id == '') {
            return $this->get_subscriptions_by_msisdn($msisdn);
        } elseif ($msisdn == '' && $product_id) {
            return $this->get_subscriptions_by_product_id($product_id);
        }
    }

    private function get_subscriptions_by_msisdn($msisdn)
    {
        $alias = $msisdn;

        if($msisdn[0] != 'A') {
            $alias = $this->get_msisdn_alias($msisdn);
        }

        return Subscription::where('msisdn', $msisdn)
                           ->orWhere('msisdn', $alias)
                           ->get();
    }

    private function get_subscriptions_by_product_id($product_id)
    {
        return Subscription::where('product_id', $product_id)->get();
    }

    private function get_subscription($msisdn, $product_id)
    {
        return Subscription::where('msisdn', $msisdn)
                         ->where('product_id', $product_id)
                         ->first();
    }

    private function get_msisdn_alias($msisdn)
    {
        $client = new Client();
        return $client->request('GET', "http://interview.pmcservices.co.uk/alias/lookup?msisdn={$msisdn}")->getBody();
    }
}
