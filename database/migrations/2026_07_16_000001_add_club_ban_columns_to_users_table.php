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
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'club_banned')) {
                $table->boolean('club_banned')->default(false)->after('club_id');
            }

            if (! Schema::hasColumn('users', 'club_ban_reason')) {
                $table->string('club_ban_reason')->nullable()->after('club_banned');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'club_ban_reason')) {
                $table->dropColumn('club_ban_reason');
            }

            if (Schema::hasColumn('users', 'club_banned')) {
                $table->dropColumn('club_banned');
            }
        });
    }
};
