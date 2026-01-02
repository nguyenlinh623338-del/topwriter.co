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
        Schema::create('trial_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('industry_type')->nullable(); // finance hoặc health
            $table->string('industry');
            $table->string('keyword_option')->default('provided'); // 'provided' or 'analysis'
            $table->text('keywords')->nullable();
            $table->text('primary_keywords')->nullable(); // For Ahrefs analysis option
            $table->json('competitor_links')->nullable(); // Store as JSON array
            $table->text('notes')->nullable();
            $table->boolean('direct_posting')->default(false);
            $table->string('website_url')->nullable();
            $table->string('website_username')->nullable();
            $table->text('website_password')->nullable();
            $table->boolean('payment_completed')->default(false);
            $table->string('payment_method')->nullable();
            $table->decimal('payment_amount', 8, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trial_registrations');
    }
};
