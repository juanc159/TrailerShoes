<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('requirement_manages', function (Blueprint $table) {
            $table->id();
            $table->longText('observation');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('requirement_id')->constrained('requirements');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requirement_manages');
    }
};
