<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('price_offer_items', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('item_id'); 
            $table->unsignedBigInteger('price_offer_id');
            
            $table->foreign('price_offer_id')
                  ->references('id')
                  ->on('price_offers')
                  ->onDelete('cascade');

            $table->string('unit'); 
            $table->string('title'); 
            $table->integer('quantity'); 
            $table->decimal('price'); 
            $table->decimal('total'); 
            $table->timestamps();  

            $table->index('price_offer_id');
            $table->index('item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_offer_items');
    }
};
