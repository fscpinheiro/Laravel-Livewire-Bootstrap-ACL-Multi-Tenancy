<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('historicos', function (Blueprint $table) {
            $table->id();
            $table->uuid('cliente_id')->index();
            $table->uuid('user_id')->index();
            $table->uuid('app_id')->index();
            $table->timestamp('datahora');
            $table->string('ip');            
            $table->string('acao');
            $table->string('browser');
            $table->string('so');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('app_id')->references('id')->on('apps');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('historicos');
    }
};
