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
        Schema::table('l_user_logs', function (Blueprint $table) {
            $table->foreign('t_user_id')->references('id')->on('t_users');
        });

        Schema::table('t_user_roles', function (Blueprint $table) {
            $table->foreign('t_user_id')->references('id')->on('t_users');
            $table->foreign('m_user_role_id')->references('id')->on('m_user_roles');
        });

        Schema::table('t_user_settings', function (Blueprint $table) {
            $table->foreign('t_user_id')->references('id')->on('t_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('l_user_logs', function (Blueprint $table) {
            $table->dropForeign(['t_user_id']);
        });

        Schema::table('t_user_roles', function (Blueprint $table) {
            $table->dropForeign(['t_user_id']);
            $table->dropForeign(['m_user_role_id']);
        });

        Schema::table('t_user_settings', function (Blueprint $table) {
            $table->dropForeign(['t_user_id']);
        });
    }
};