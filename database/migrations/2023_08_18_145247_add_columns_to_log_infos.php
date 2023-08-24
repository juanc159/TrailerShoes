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
        Schema::table('log_infos', function (Blueprint $table) {
            $table->longText("before")->nullable();
            $table->longText("after")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_infos', function (Blueprint $table) {
            $table->dropColumn("before");
            $table->dropColumn("after");
        });
    }
};
