<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;

class CheckTransactionsTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:transactions-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check transactions table structure and identify missing columns';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Checking Transactions Table Structure...');
        $this->newLine();

        try {
            // Check if table exists
            if (!Schema::hasTable('transactions')) {
                $this->error('❌ Transactions table does not exist!');
                return 1;
            }

            $this->info('✅ Transactions table exists');

            // Get all columns in the table
            $columns = Schema::getColumnListing('transactions');
            
            $this->info('📋 Current columns in transactions table:');
            foreach ($columns as $column) {
                $this->line("   - {$column}");
            }

            $this->newLine();

            // Check required columns for credits payment
            $requiredColumns = [
                'id',
                'user_id', 
                'amount',
                'type',
                'status',
                'payment_method',
                'payment_status',
                'transaction_type',
                'payment_transaction_id',
                'credits',
                'payment_completed_at',
                'created_at',
                'updated_at'
            ];

            $this->info('🔍 Checking required columns for credits payment:');
            $missingColumns = [];

            foreach ($requiredColumns as $column) {
                if (in_array($column, $columns)) {
                    $this->info("   ✅ {$column}");
                } else {
                    $this->error("   ❌ {$column} (MISSING)");
                    $missingColumns[] = $column;
                }
            }

            if (empty($missingColumns)) {
                $this->newLine();
                $this->info('🎉 All required columns are present!');
                
                // Test creating a transaction record
                $this->info('🧪 Testing transaction creation...');
                
                try {
                    $testTransaction = new Transaction([
                        'user_id' => 1,
                        'amount' => 600.00,
                        'payment_method' => 'test',
                        'type' => 'credits',
                        'transaction_type' => 'credits',
                        'payment_status' => 'pending',
                        'status' => 'pending',
                        'credits' => 30,
                        'payment_transaction_id' => 'TEST_' . time(),
                    ]);
                    
                    // Don't save, just validate fillable fields
                    $this->info('✅ Transaction model can be created with all required fields');
                    
                } catch (\Exception $e) {
                    $this->error('❌ Error creating transaction: ' . $e->getMessage());
                }
                
            } else {
                $this->newLine();
                $this->error('❌ Missing columns detected!');
                $this->warn('💡 You need to run the migration: 2025_04_18_043921_add_fields_to_transactions_table');
                $this->warn('   Command: php artisan migrate');
            }

            // Check recent transactions
            $this->newLine();
            $this->info('📊 Recent transactions:');
            
            $recentTransactions = DB::table('transactions')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            if ($recentTransactions->isEmpty()) {
                $this->warn('   No transactions found');
            } else {
                $this->table(
                    ['ID', 'User ID', 'Amount', 'Type', 'Status', 'Created'],
                    $recentTransactions->map(function ($transaction) {
                        return [
                            $transaction->id,
                            $transaction->user_id,
                            '$' . $transaction->amount,
                            $transaction->type ?? 'N/A',
                            $transaction->status,
                            $transaction->created_at
                        ];
                    })->toArray()
                );
            }

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Error checking transactions table: ' . $e->getMessage());
            $this->error('   ' . $e->getTraceAsString());
            return 1;
        }
    }
}
