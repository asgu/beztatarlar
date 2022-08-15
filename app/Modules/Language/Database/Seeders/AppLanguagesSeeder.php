<?php

namespace Modules\Language\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppLanguagesSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            ['name' => 'Русский', 'code' => 'RU', 'sort_index' => 1],
            ['name' => 'Татарский', 'code' => 'TT', 'sort_index' => 2],
        ];

        DB::table('app_languages')->insertOrIgnore($languages);
    }
}
