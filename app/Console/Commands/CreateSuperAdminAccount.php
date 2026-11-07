<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateSuperAdminAccount extends Command
{
    protected $signature = 'superadmin:create-account';

    protected $description = 'Create a separate Super Admin account';

    public function handle()
    {
        $email = 'superadmin@example.com';

        if (User::where('email', $email)->exists()) {
            $this->error('This Super Admin email already exists.');
            return self::FAILURE;
        }

        $role = DB::table('roles')
            ->where('name', 'super_admin')
            ->where('status', 'Active')
            ->first();

        if (!$role) {
            $this->error('Active Super Admin role was not found.');
            return self::FAILURE;
        }

        $name = $this->ask('Enter Super Admin name');

        if (!$name) {
            $this->error('Name is required.');
            return self::FAILURE;
        }

        $password = $this->secret('Enter a new Super Admin password');
        $confirmation = $this->secret('Confirm Super Admin password');

        if (!$password || $password !== $confirmation) {
            $this->error('Password is empty or confirmation does not match.');
            return self::FAILURE;
        }

        if (strlen($password) < 12) {
            $this->error('Use a password with at least 12 characters.');
            return self::FAILURE;
        }

        $user = new User();

        $user->forceFill([
            'name' => $name,
            'email' => $email,
            'login_id' => $email,
            'password' => $password,
            'role_id' => $role->id,
            'super_admin_role_id' => null,
            'school_id' => null,
            'status' => 'Active',
            'super_admin_two_factor_enabled' => false,
            'super_admin_two_factor_secret' => null,
        ]);

        $user->save();

        $this->info('Separate Super Admin account created successfully!');
        $this->line('Login email: ' . $email);
        $this->line('Role: Super Admin');
        $this->line('Two-factor authentication: Setup required');

        return self::SUCCESS;
    }
}