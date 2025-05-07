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
        Schema::create('transaksi_212102', function (Blueprint $table) {
            $table->id('id_212102');
            $table->foreignId('user_id_212102')
                ->constrained('users_212102', 'id_212102')
                ->cascadeOnUpdate();
            $table->foreignId('item_id_212102')
                ->constrained('menu_items_212102', 'id_212102')
                ->cascadeOnUpdate();
            $table->string('no_transaksi_212102')->unique();
            $table->decimal('price_212102', 13, 2);
            $table->decimal('total_price_212102', 13, 2);
            $table->text('noted_212102');
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable();
            $table->tinyInteger('status_212102');
            $table->timestamps();
            $table->softDeletes(); // creates 'deleted_at'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi_212102');
    }
};
