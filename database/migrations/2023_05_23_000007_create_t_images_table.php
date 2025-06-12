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
        Schema::create('t_images', function (Blueprint $table) {
            $table->id();
            $table->string('original_filename')->comment('画像のオリジナルファイル名');
            $table->string('converted_filename')->comment('画像の変換後のファイル名');
            $table->string('upload_url')->comment('アップロード先のフルURL');
            $table->string('upload_path')->comment('アップロード先パス');
            $table->string('mimetype')->comment('画像のmimetype');
            $table->string('image_type')->comment('画像タイプ');
            $table->integer('width')->nullable()->comment('画像幅');
            $table->integer('height')->nullable()->comment('画像高さ');
            $table->bigInteger('file_size')->comment('ファイルサイズ（バイト）');
            $table->string('file_hash')->comment('画像のハッシュ');
            $table->datetime('created_at')->nullable();
            $table->datetime('updated_at')->nullable();
            $table->datetime('deleted_at')->nullable();
            $table->string('created_by')->nullable()->comment('作成者');
            $table->string('updated_by')->nullable()->comment('更新者');
            $table->string('deleted_by')->nullable()->comment('削除者');

            // Add indexes for commonly searched fields
            $table->index('file_hash');
            $table->index('mimetype');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_images');
    }
};