<?php

namespace App\Listeners;

use App\Events\AdventurerEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AdventurerEventListener implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(AdventurerEvent $event): void
    {
        // Log the adventurer action
        \Log::info('Adventurer Event', [
            'adventurer_id' => $event->adventurerId,
            'action' => $event->action,
            'data' => $event->data,
        ]);

        // Perform any necessary actions based on the event
        switch ($event->action) {
            case 'quest_started':
                // Handle quest start
                break;
            case 'quest_completed':
                // Handle quest completion
                break;
            case 'level_up':
                // Handle level up
                break;
            case 'item_found':
                // Handle item discovery
                break;
            default:
                // Default action
                break;
        }
    }
}