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
        Schema::create('m_user_roles', function (Blueprint $table) {
            $table->id();
            $table->string('role_name')->comment('ロール名');
            $table->datetime('created_at')->nullable();
            $table->datetime('updated_at')->nullable();
            $table->datetime('deleted_at')->nullable();
            $table->string('created_by')->nullable()->comment('作成者');
            $table->string('updated_by')->nullable()->comment('更新者');
            $table->string('deleted_by')->nullable()->comment('削除者');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_user_roles');
    }
};