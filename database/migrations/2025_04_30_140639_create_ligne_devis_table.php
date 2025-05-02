<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLigneDevisTable extends Migration
{
    public function up()
    {
        Schema::create('ligne_devis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_devis');
            $table->unsignedBigInteger('id_product');
            $table->integer('quantite');
            $table->decimal('remise', 8, 2)->default(0);
            $table->decimal('total_ht', 10, 2)->default(0);
            $table->decimal('tva', 5, 2)->default(19.00);
            $table->decimal('total_ttc', 10, 2)->default(0);
            $table->timestamps();

            // Foreign keys (optionnel si tu veux relier aux autres tables)
            // $table->foreign('id_devis')->references('id')->on('devis')->onDelete('cascade');
            // $table->foreign('id_product')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ligne_devis');
    }
}
