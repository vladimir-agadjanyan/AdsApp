<?php

namespace App\Livewire\Notifications;

use App\Models\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Dropdown extends Component
{
    public bool $isOpen = false;

    public function toggle(): void
    {
        $this->isOpen = ! $this->isOpen;
    }

    public function markAsRead(int $notificationId): void
    {
        Notification::query()
            ->whereKey($notificationId)
            ->where('user_id', auth()->id())
            ->update([
                'is_read' => true,
            ]);
    }

    public function markAllAsRead(): void
    {
        Notification::query()
            ->where('user_id', auth()->id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
            ]);
    }

    public function render(): View
    {
        $notifications = Notification::query()
            ->where('user_id', auth()->id())
            ->latest()
            ->limit(5)
            ->get();

        $unreadCount = Notification::query()
            ->where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return view('livewire.notifications.dropdown', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }
}
