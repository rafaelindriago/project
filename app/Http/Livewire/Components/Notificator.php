<?php

declare(strict_types=1);

namespace App\Http\Livewire\Components;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Livewire\Component;

class Notificator extends Component
{
    public array $notifications = [];

    public function boot(): void
    {
        $this->listeners['notificator:nofity'] = 'notify';
    }

    public function render(): View
    {
        return view('livewire.components.notificator');
    }

    public function notify(string $title, string $message, string $type = 'info'): void
    {
        $this->notifications[] = [
            'timestamp' => Carbon::now()->timestamp,
            'title'     => $title,
            'message'   => $message,
            'type'      => $type,
        ];

        $this->dispatchBrowserEvent('notificator:show');
    }

    public function dismiss(int $key): void
    {
        Arr::forget($this->notifications, $key);
    }
}
