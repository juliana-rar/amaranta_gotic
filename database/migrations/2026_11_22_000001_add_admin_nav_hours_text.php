<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! DB::table('site_texts')->where('key', 'admin.nav.horarios')->exists()) {
            DB::table('site_texts')->insert([
                'key' => 'admin.nav.horarios',
                'value' => '🕒 Horarios',
            ]);
        }
    }

    public function down(): void
    {
        DB::table('site_texts')->where('key', 'admin.nav.horarios')->delete();
    }
};
