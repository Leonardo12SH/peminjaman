<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_details_212102', function (Blueprint $table) {
            $table->id('id_212102');
            $table->foreignId('transaksi_id_212102')->references('id_212102')->on('transaksi_212102')->cascadeOnUpdate();
            $table->foreignId('menuitem_id_212102')->references('id_212102')->on('menu_items_212102')->cascadeOnUpdate();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi_details_212102');
    }
};
