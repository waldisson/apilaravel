<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllTables extends Migration
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
            $table->string('name',100);
            $table->string('email',100)->unique();
            $table->string('password',200);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('price');
            $table->string('image')->default('default.png');
            $table->timestamps();
        });

        Schema::create('Endereco', function (Blueprint $table) {
            $table->id();
            $table->string('endereco');
            $table->string('bairro');
            $table->string('telefone');
            $table->string('cep');
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
        Schema::dropIfExists('products');
        Schema::dropIfExists('Endereco');
    }
}
