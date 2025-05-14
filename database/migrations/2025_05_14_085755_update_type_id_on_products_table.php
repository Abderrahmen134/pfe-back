<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTypeIdOnProductsTable extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Supprimer la clé étrangère si elle existe
            if (Schema::hasColumn('products', 'type_id')) {
                $table->dropForeign(['type_id']);
                $table->dropColumn('type_id');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            // Recréer le champ avec contrainte non nulle et clé étrangère
            $table->unsignedBigInteger('type_id');
            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['type_id']);
            $table->dropColumn('type_id');
        });

        // Optionnel : ajouter à nouveau comme nullable si nécessaire dans down()
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('type_id')->nullable();
        });
    }
}
