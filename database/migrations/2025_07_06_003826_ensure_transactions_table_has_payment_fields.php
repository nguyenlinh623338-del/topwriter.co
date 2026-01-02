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
            // Only add columns if they don't exist (safe for production)
            if (!Schema::hasColumn('transactions', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }
            
            if (!Schema::hasColumn('transactions', 'payment_status')) {
                $table->string('payment_status')->nullable();
            }
            
            if (!Schema::hasColumn('transactions', 'transaction_type')) {
                $table->string('transaction_type')->nullable();
            }
            
            if (!Schema::hasColumn('transactions', 'payment_transaction_id')) {
                $table->string('payment_transaction_id')->nullable();
            }
            
            if (!Schema::hasColumn('transactions', 'credits')) {
                $table->integer('credits')->nullable();
            }
            
            if (!Schema::hasColumn('transactions', 'payment_completed_at')) {
                $table->timestamp('payment_completed_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Only drop columns if they exist
            $columnsToDrop = [];
            
            if (Schema::hasColumn('transactions', 'payment_method')) {
                $columnsToDrop[] = 'payment_method';
            }
            
            if (Schema::hasColumn('transactions', 'payment_status')) {
                $columnsToDrop[] = 'payment_status';
            }
            
            if (Schema::hasColumn('transactions', 'transaction_type')) {
                $columnsToDrop[] = 'transaction_type';
            }
            
            if (Schema::hasColumn('transactions', 'payment_transaction_id')) {
                $columnsToDrop[] = 'payment_transaction_id';
            }
            
            if (Schema::hasColumn('transactions', 'credits')) {
                $columnsToDrop[] = 'credits';
            }
            
            if (Schema::hasColumn('transactions', 'payment_completed_at')) {
                $columnsToDrop[] = 'payment_completed_at';
            }
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
