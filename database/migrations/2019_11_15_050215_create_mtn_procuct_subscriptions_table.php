<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMtnProcuctSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtn_product_subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product');
            $table->string('primary_key');
            $table->string('secondary_key');
            $table->string('bearer_token');
            $table->string('target_environment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mtn_product_subscriptions');
    }
}
