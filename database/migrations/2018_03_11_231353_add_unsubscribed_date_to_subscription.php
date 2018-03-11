<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUnsubscribedDateToSubscription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         Schema::table('subscriptions', function (Blueprint $table) {
             $table->date('unsubscribed_date')->nullable();
         });
     }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
         Schema::table('subscriptions', function (Blueprint $table) {
             $table->dropColumn('unsubscribed_date');
         });
     }
}
