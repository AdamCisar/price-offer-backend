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
        Schema::create('price_offer_customers', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('price_offer_id');

            $table->foreign('price_offer_id')
                  ->references('id')
                  ->on('price_offers')
                  ->onDelete('cascade');

            $table->string('name'); 
            $table->string('address'); 
            $table->string('city'); 
            $table->string('zip');  
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_offer_customers');
    }
};
