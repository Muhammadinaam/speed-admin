<?php

namespace MuhammadInaamMunir\SpeedAdmin\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateAdminUser extends Command
{
    protected $signature = 'speed-admin:create-admin-user';

    protected $description = 'Create Admin User';

    public function handle()
    {
        $this->info('Creating admin user');

        $user = new \App\Models\User();
        $user->name = 'Admin';
        $user->email = 'admin@admin.com';
        $user->password = bcrypt('admin');
        $user->is_superadmin = true;
        $user->save();

        $this->info('Admin user created. email: admin@admin.com, password: admin');
    }
}