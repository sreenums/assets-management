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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->constrained();
            $table->foreignId('hardware_standard_id')->constrained();
            $table->foreignId('technical_specification_id')->constrained();
            $table->foreignId('location_id')->constrained();      //Location can be from locations table or from users table linked to location
            //$table->foreignId('user_id')->constrained();
            $table->string('asset_tag')->unique();
            $table->string('serial_no')->unique();
            $table->string('purchase_order')->nullable();
            $table->tinyInteger('status')->default(null)->comment('1: Brand New, 2: Assigned, 3: Damaged');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
