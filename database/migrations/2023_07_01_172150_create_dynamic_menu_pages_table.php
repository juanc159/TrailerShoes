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
        Schema::create('dynamic_menu_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title'); //Texto en el boton
            $table->string('icon'); //icono
            $table->boolean('principal')->default(0);
            $table->foreignId('father_id')->nullable()->constrained('dynamic_menu_pages'); // padre
            $table->boolean('state')->default(0); // estado
            $table->json('metaData');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dynamic_menu_pages');
    }
};
