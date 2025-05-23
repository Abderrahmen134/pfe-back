<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatutadToAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('admins', function (Blueprint $table) {
        $table->enum('statutad', ['actif', 'non actif'])->default('actif');
    });
}



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
{
    Schema::table('admins', function (Blueprint $table) {
        $table->dropColumn('statutad');
    });
}
}
