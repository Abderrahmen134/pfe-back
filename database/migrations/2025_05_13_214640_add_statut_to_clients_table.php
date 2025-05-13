<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatutToClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('clients', function (Blueprint $table) {
        $table->enum('statut', ['actif', 'non actif'])->default('actif');
    });
}




    /**
     * Reverse the migrations.
     *
     * @return void
     */
public function down()
{
    Schema::table('clients', function (Blueprint $table) {
        $table->dropColumn('statut');
    });
}
    }
