<?php

namespace Modules\Admin\Console\Commands;

use App\Helpers\DateHelper;
use Illuminate\Console\Command;
use Modules\User\Models\User;
use Modules\User\Modules\Profile\Models\UserProfile;
use Modules\User\Modules\Profile\Services\UserProfileService;
use Modules\User\Services\UserService;

class AdminCommand extends Command
{
    protected $signature = 'admin:create {email} {password}';
    protected $description = 'Create admin';

    /**
     * @throws \Exception
     */
    public function handle(UserService $service, UserProfileService $profileService)
    {
        $admin = new User();
        $admin->email = $this->argument('email');
        $admin->password = $this->argument('password');
        $admin->created_at = DateHelper::now();
        $admin->role = User::ROLE_ADMIN;
        $admin->status = User::STATUS_ACTIVE;
        $service->save($admin);
        $admin->refresh();

        $profile = new UserProfile();
        $profile->name = 'Админ';
        $profile->surname = 'Админ';
        $profile->gender = 'male';

        $profileService->attachToUser($admin, $profile);
    }
}
