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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('state')->default(1);
            $table->string('lastName');
            $table->string('idNumber');
            $table->foreignId('role_id')->nullable()->constrained('roles');
            $table->foreignId('identity_type_id')->constrained('identity_types');
            $table->foreignId('charge_id')->nullable()->constrained('charges');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('state');
            $table->dropColumn('lastName');
            $table->dropColumn('idNumber');
            $table->dropConstrainedForeignId('role_id');
            $table->dropConstrainedForeignId('identity_type_id');
            $table->dropConstrainedForeignId('charge_id');
        });
    }
};
