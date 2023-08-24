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
        Schema::create('requirement_type_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requirement_type_id')->constrained('requirement_types');
            $table->foreignId('charge_id')->constrained('charges');
            $table->integer('order');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requirement_type_charges');
    }
};
