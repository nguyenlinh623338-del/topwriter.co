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
        Schema::create('keywords', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trial_registration_id')->constrained()->onDelete('cascade');
            $table->string('keyword');
            $table->integer('order')->default(0);
            $table->boolean('is_completed')->default(false);
            $table->string('status')->default('pending');
            $table->text('result_url')->nullable();
            $table->timestamps();
            
            $table->index('keyword');
            $table->index(['trial_registration_id', 'is_completed']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keywords');
    }
};
