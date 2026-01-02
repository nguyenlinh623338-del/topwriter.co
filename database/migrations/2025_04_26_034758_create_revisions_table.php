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
        Schema::create('revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('dashboard_sheet_id')->constrained()->onDelete('cascade');
            $table->foreignId('dashboard_item_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('keyword'); // Từ khóa tương ứng với bài viết
            $table->text('note'); // Nội dung góp ý chỉnh sửa
            $table->boolean('synced_to_sheet')->default(false); // Đã đồng bộ lên Google Sheet chưa
            $table->json('api_response')->nullable(); // Lưu response từ API
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revisions');
    }
};
