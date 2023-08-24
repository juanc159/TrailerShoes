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
            $table->date("expeditionDate")->nullable();
            $table->date("birthDate")->nullable();
            $table->foreignId("gender_id")->nullable()->constrained("genders");
            $table->string("weight")->nullable();
            $table->string("height")->nullable();
            $table->foreignId("civil_status_id")->nullable()->constrained("civil_statuses");
            $table->string("phone")->nullable();
            $table->string("cellphone")->nullable();
            $table->string("address")->nullable();
            $table->string("have_son")->nullable();
            $table->string("nameContact")->nullable();
            $table->string("relationshipContact")->nullable();
            $table->string("phoneContact")->nullable();
            $table->string("cellphoneContact")->nullable();
            $table->string("emailContact")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn("expeditionDate");
            $table->dropColumn("birthDate");
            $table->dropColumn("gender_id");
            $table->dropColumn("weight");
            $table->dropColumn("height");
            $table->dropColumn("civil_status");
            $table->dropColumn("phone");
            $table->dropColumn("cellphone");
            $table->dropColumn("address");
            $table->dropColumn("have_son");
            $table->dropColumn("nameContact");
            $table->dropColumn("relationshipContact");
            $table->dropColumn("phoneContact");
            $table->dropColumn("cellphoneContact");
            $table->dropColumn("emailContact");
        });
    }
};
