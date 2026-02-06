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
        Schema::create('dettes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->text('product');
            $table->string('quantity');
            $table->integer('montant');
            $table->enum('status', ['PAYER', 'ENCOURS'])->default('ENCOURS');
            $table->integer('author_id');
            $table->timestamps();
            $table->foreign('client_id')->references('id')->on('clients');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dettes');
    }
};
