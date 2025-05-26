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
        Schema::create('t_user_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('t_user_id')->comment('ユーザーID');
            $table->string('setting_key')->comment('設定キー');
            $table->text('setting_value')->comment('設定値');
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
        Schema::dropIfExists('t_user_settings');
    }
};