<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
Schema::create('clients', function (Blueprint $table) {
$table->id();
$table->string('prenom');
$table->string('nom');
$table->string('email')->unique();
$table->string('mot_de_passe');
$table->string('phone')->nullable();
$table->string('gouvernorat')->nullable();
$table->string('api_token', 80)->unique()->nullable()->default(null);
$table->timestamps();
});
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
