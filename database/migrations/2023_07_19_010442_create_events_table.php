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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            // $table->string("event_google_id");
            $table->string('summary');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->foreignId('calendar_type_id')->constrained('calendar_types');
            $table->foreignId('user_id')->constrained('users');
            $table->string('link');
            $table->boolean('public');
            // $table->text("guests");
            $table->string('location');
            $table->longText('description');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
