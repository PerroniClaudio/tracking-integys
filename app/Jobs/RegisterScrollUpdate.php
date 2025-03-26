<?php

namespace App\Jobs;

use App\Models\TrackedEvents;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RegisterScrollUpdate implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $payload;

    /**
     * Create a new job instance.
     */
    public function __construct($payload) {
        //

        $this->payload = $payload;
    }

    /**
     * Execute the job.
     */
    public function handle(): void {
        //

        $originalEvent = TrackedEvents::where("session_id", $this->payload['session_id'])
            ->where("url", $this->payload['url'])
            ->where("event_code", "PAGE_VIEW")
            ->orWhere("event_code", "ARTICLE_VIEW")
            ->latest()
            ->first();

        if (!$originalEvent) {
            return;
        }

        if ($originalEvent->scroll_percentage >= $this->payload['scroll_percentage']) {
            return;
        }

        $originalEvent->scroll_percentage = $this->payload['scroll_percentage'];
        $originalEvent->save();
    }
}
