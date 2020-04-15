<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 64)->nullable(false)->unique();
            $table->string('email', 128)->nullable(false)->unique();
            $table->string('password', 72)->nullable(false);
            $table->string('name', 64)->nullable(true);
            $table->text('avatar')->nullable(true);
            $table->string('provider_id')->nullable(true);
            $table->string('provider')->nullable(true);
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
        Schema::dropIfExists('users');
    }
}
