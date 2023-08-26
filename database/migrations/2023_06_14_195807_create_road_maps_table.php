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
        Schema::create('road_maps', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Str::uuid());
            $table->string('feature');
            $table->string('category');
            $table->string('status');
            $table->string('version')->nullable();
            $table->timestamp('estimated_completion_date')->nullable();
            $table->timestamp('completed_date')->nullable();
            $table->text('notes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('road_maps');
    }
};
