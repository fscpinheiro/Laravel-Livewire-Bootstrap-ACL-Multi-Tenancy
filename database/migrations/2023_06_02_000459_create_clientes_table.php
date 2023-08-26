<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Str;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid());
            $table->string('razaosocial');
            $table->string('fantasia');
            $table->string('slugname');
            $table->string('documento');
            $table->integer('situacao'); //1-ATIVO 0-CANCELADO 2-SUSPENSO
            $table->text('logo')->nullable();
            $table->string('tpcliente');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
