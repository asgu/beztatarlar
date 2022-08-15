<?php

namespace Modules\Admin\Database\Seeders;

use App\Helpers\DateHelper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;

class CreateAdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insertOrIgnore([
            [
                'id'         => 1,
                'created_at' => DateHelper::now(),
                'email'      => 'admin@admin.ru',
                'password'   => Hash::make(env('ADMIN_PASSWORD', 123456)),
                'role'       => User::ROLE_ADMIN,
                'status'     => User::STATUS_ACTIVE
            ]
        ]);

        DB::table('user_profiles')->insertOrIgnore([
            [
                'user_id' => 1,
                'name'    => 'Админ',
                'surname' => 'Админ',
                'gender'  => 'male',
            ]
        ]);
    }
}
