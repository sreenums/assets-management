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
        Schema::table('technical_specifications', function (Blueprint $table) {
            $table->foreignId('hardware_standard_id')->after('id')->constrained(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('technical_specifications', function (Blueprint $table) {
            $table->dropColumn('hardware_standard_id');
        });
    }
};
