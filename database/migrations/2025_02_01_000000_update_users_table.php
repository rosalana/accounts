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
            $table->unsignedBigInteger('rosalana_account_id')->unique()->nullable()->after('id');
            $table->dropColumn('email_verified_at');
            $table->dropColumn('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void 
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('rosalana_account_id');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
        });
    }
};
