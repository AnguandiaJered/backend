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
        Schema::create('perte_produits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produit_id');
            $table->bigInteger('quantite');
            $table->unsignedBigInteger('gaspillage_id');
            $table->integer('author_id');
            $table->timestamps();
            $table->foreign('produit_id')->references('id')->on('produits');
            $table->foreign('gaspillage_id')->references('id')->on('type_pertes');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perte_produits');
    }
};
