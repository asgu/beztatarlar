<?php

namespace Modules\Language\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppLanguageMessagesSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            ['code' => 'RU', 'type' => 'backend', 'message_values' => '{}'],
            ['code' => 'TT', 'type' => 'backend', 'message_values' => '{}'],

            ['code' => 'RU', 'type' => 'frontend', 'message_values' => '{}'],
            ['code' => 'TT', 'type' => 'frontend', 'message_values' => '{}'],
        ];

        DB::table('app_language_messages')->insertOrIgnore($languages);
    }
}
