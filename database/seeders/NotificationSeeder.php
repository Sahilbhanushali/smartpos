<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\AppNotifier;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()->get();

        if ($users->isEmpty()) {
            return;
        }

        AppNotifier::send(
            $users,
            'Welcome to SmartPOS',
            'Your notification center is ready. Alerts for inventory and account activity will appear here.',
            'success',
            Heroicon::OutlinedBell,
        );

        AppNotifier::send(
            $users->where('is_super_admin', false),
            'Review your permissions',
            'Contact an administrator if you need access to additional modules.',
            'info',
            Heroicon::OutlinedShieldCheck,
        );

        AppNotifier::notifySuperAdmins(
            'System notifications enabled',
            'Database notifications are now active in the admin header.',
            'warning',
            Heroicon::OutlinedExclamationTriangle,
        );
    }
}
