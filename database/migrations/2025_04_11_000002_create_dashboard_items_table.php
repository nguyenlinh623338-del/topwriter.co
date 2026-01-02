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
        Schema::create('dashboard_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dashboard_sheet_id')->constrained()->onDelete('cascade');
            $table->string('keyword');
            $table->text('link_top')->nullable();
            $table->text('idea')->nullable();
            $table->text('guidelines')->nullable();
            $table->text('link_docs')->nullable();
            $table->text('link_post')->nullable();
            $table->integer('credit')->default(0);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dashboard_items');
    }
}; 