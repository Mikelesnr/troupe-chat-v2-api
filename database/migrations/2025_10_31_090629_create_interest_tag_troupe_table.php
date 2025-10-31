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
        Schema::create('interest_tag_troupe', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('troupe_id');
            $table->uuid('interest_tag_id');
            $table->timestamps();

            $table->foreign('troupe_id')->references('id')->on('troupes')->onDelete('cascade');
            $table->foreign('interest_tag_id')->references('id')->on('interest_tags')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interest_tag_troupe');
    }
};
