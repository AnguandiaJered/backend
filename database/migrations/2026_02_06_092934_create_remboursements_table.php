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
        Schema::create('remboursements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('dette_id');
            $table->integer('montant');
            $table->enum('status', ['TERMINER', 'ENCOURS'])->default('ENCOURS');
            $table->integer('author_id');
            $table->timestamps();
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('dette_id')->references('id')->on('dettes');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remboursements');
    }
};
