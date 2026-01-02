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
        Schema::table('dashboard_items', function (Blueprint $table) {
            $table->text('revision')->nullable()->after('insights');
            $table->string('revision_status')->nullable()->after('revision');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dashboard_items', function (Blueprint $table) {
            $table->dropColumn('revision');
            $table->dropColumn('revision_status');
        });
    }
}; 