<?php

namespace App\Support;

use App\Models\User;
use App\Notifications\FilamentStoredNotification;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class AppNotifier
{
    /**
     * @param  User | Collection<int, User> | array<int, User>  $users
     */
    public static function send(
        User | Collection | array $users,
        string $title,
        ?string $body = null,
        string $status = 'info',
        Heroicon | string | null $icon = null,
    ): void {
        if (! is_iterable($users)) {
            $users = [$users];
        }

        $notification = Notification::make()
            ->title($title)
            ->status($status);

        if (filled($body)) {
            $notification->body($body);
        }

        if ($icon) {
            $notification->icon($icon);
        }

        $payload = $notification->getDatabaseMessage();

        foreach ($users as $user) {
            $user->notify(new FilamentStoredNotification($payload));

            event(new \Filament\Notifications\Events\DatabaseNotificationsSent($user));
        }
    }

    public static function notifySuperAdmins(
        string $title,
        ?string $body = null,
        string $status = 'info',
        Heroicon | string | null $icon = null,
    ): void {
        $admins = User::query()->where('is_super_admin', true)->get();

        if ($admins->isEmpty()) {
            return;
        }

        self::send($admins, $title, $body, $status, $icon);
    }

    public static function notifyForModelAction(
        User $user,
        Model $model,
        string $action,
        string $status = 'success',
    ): void {
        $label = class_basename($model);

        self::send(
            $user,
            "{$label} {$action}",
            "The {$label} \"{$model->getAttribute('name')}\" was {$action} successfully.",
            $status,
        );
    }
}
