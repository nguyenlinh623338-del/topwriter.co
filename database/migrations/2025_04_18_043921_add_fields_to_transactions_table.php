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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('transaction_type')->nullable();
            $table->string('payment_transaction_id')->nullable();
            $table->integer('credits')->nullable();
            $table->timestamp('payment_completed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('payment_method');
            $table->dropColumn('payment_status');
            $table->dropColumn('transaction_type');
            $table->dropColumn('payment_transaction_id');
            $table->dropColumn('credits');
            $table->dropColumn('payment_completed_at');
        });
    }
};
