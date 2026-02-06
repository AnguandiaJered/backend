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
        Schema::create('approvisionnements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produit_id');
            $table->bigInteger('quantite');
            $table->integer('prix');
            $table->unsignedBigInteger('fournisseur_id');
            $table->integer('author_id');
            $table->timestamps();
            $table->foreign('produit_id')->references('id')->on('produits');
            $table->foreign('fournisseur_id')->references('id')->on('fournisseurs');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvisionnements');
    }
};
