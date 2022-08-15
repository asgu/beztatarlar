<?php

namespace Modules\User\Database\Seeders;

use App\Helpers\DateHelper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;

class CreateStudentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insertOrIgnore([
            [
                'id' => 2,
                'created_at' => DateHelper::now(),
                'email'      => 'student@student.ru',
                'password'   => Hash::make(env('ADMIN_PASSWORD', 123456)),
                'role'       => User::ROLE_STUDENT,
                'status'     => User::STATUS_ACTIVE
            ]
        ]);

        DB::table('user_profiles')->insertOrIgnore([
            [
                'user_id' => 2,
                'name'    => 'Наталья',
                'surname' => 'Иванова',
                'patronymic' => 'Ивановна',
                'gender'  => 'female',
                'birthday' => DateHelper::now(),
                'phone' => '79279279279'
            ]
        ]);
    }
}
