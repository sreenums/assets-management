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
        Schema::table('asset_histories', function (Blueprint $table) {
            $table->tinyInteger('status_from')->nullable()->after('action');
            $table->tinyInteger('status_to')->nullable()->after('status_from');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_histories', function (Blueprint $table) {
            $table->dropColumn('status_from');
            $table->dropColumn('status_to');
        });
    }
};
