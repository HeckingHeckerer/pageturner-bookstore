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
            $table->string('default_shipping_address')->nullable();
            $table->string('default_shipping_city')->nullable();
            $table->string('default_shipping_state')->nullable();
            $table->string('default_shipping_zip')->nullable();
            $table->string('default_shipping_country')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['default_shipping_address', 'default_shipping_city', 'default_shipping_state', 'default_shipping_zip', 'default_shipping_country']);
        });
    }
};
