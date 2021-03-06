<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = ['msisdn',
                           'product_id',
                           'active',
                           'subscribed_date',
                           'unsubscribed_date'];
}
