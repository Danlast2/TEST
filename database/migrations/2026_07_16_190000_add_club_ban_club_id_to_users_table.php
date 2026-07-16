<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'club_ban_club_id')) {
                $table->unsignedBigInteger('club_ban_club_id')->nullable()->after('club_banned');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'club_ban_club_id')) {
                $table->dropColumn('club_ban_club_id');
            }
        });
    }
};
