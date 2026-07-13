<?php

namespace App\Observers;

use App\Models\Subscriber;
use App\Mail\PortfolioUpdated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PortfolioObserver
{
    /**
     * Handle the model "created" event.
     */
    public function created(object $model): void
    {
        $this->notifySubscribers($model);
    }

    /**
     * Handle the model "updated" event.
     */
    public function updated(object $model): void
    {
        $this->notifySubscribers($model);
    }

    private function notifySubscribers(object $model): void
    {
        $type = Str::snake(class_basename($model));
        $title = $model->title ?? $model->name ?? $model->role ?? 'New Update';
        $url = url('/'); // Could be specific based on type

        $subscribers = Subscriber::where('is_active', true)->get();
        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)->queue(new PortfolioUpdated($type, $title, $url));
        }
    }
}
