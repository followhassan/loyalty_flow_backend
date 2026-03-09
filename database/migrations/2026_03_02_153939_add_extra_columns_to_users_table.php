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
            $table->tinyInteger('user_type')->default(0)->comment('0=user, 1=merchant, 2=agent');
            $table->string('verification_code')->nullable();
            $table->boolean('is_verified')->default(0);
            $table->string('country_code', 10)->nullable();
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->decimal('total_balance', 12, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
