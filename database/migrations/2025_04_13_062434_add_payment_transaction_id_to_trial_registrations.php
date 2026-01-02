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
        Schema::table('trial_registrations', function (Blueprint $table) {
            $table->string('payment_transaction_id')->nullable()->after('payment_amount');
            $table->string('payment_capture_id')->nullable()->after('payment_transaction_id');
            $table->boolean('payment_refunded')->default(false)->after('payment_capture_id');
            $table->string('refund_transaction_id')->nullable()->after('payment_refunded');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trial_registrations', function (Blueprint $table) {
            $table->dropColumn(['payment_transaction_id', 'payment_capture_id', 'payment_refunded', 'refund_transaction_id']);
        });
    }
};
