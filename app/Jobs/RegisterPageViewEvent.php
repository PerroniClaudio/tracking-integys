<?php

namespace App\Jobs;

use App\Models\TrackedEvents;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RegisterPageViewEvent implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $payload;

    /**
     * Create a new job instance.
     */
    public function __construct($payload) {
        //

        $this->payload = $payload;
    }

    private function getCountry($ip_address) {

        if ($ip_address == '127.0.0.1' || $ip_address == '::1') {
            return "Unknown";
        }

        $response = Http::get("http://ip-api.com/json/{$ip_address}", [
            "fields" => "status,message,country,countryCode,region,regionName,city,district,zip,lat,lon,timezone,isp,org,as,query"
        ]);

        if ($response->successful()) {
            return $response;
        } else {
            return "Unknown";
        }
    }


    private function verifyEmail($email) {

        $email_regexp = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";

        if (preg_match($email_regexp, $email)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Execute the job.
     */
    public function handle(): void {
        //

        $payload = $this->payload;
        $response = $this->getCountry($payload['ip_address']);
        $eventData = [
            "event_code" => "PAGE_VIEW",
            "ip_address" => $payload['ip_address'],
            "ip_country" => $response != "Unknown" ? $response['country'] : "Unknown",
            "ip_city" => $response != "Unknown" ? $response['city'] : "Unknown",
            "ip_zip" => $response != "Unknown" ? $response['zip'] : "Unknown",
            "user_agent" => $payload['user_agent'],
            "referer" => $payload['referer'],
            "url" => $payload['url'],
            "scroll_percentage" => 0,
            "utm_source" => $payload['utm_source'],
            "utm_medium" => $payload['utm_medium'],
            "utm_campaign" => $payload['utm_campaign'],
            "utm_term" => $payload['utm_term'],
            "utm_content" => $payload['utm_content'],
            "element_id" => $payload['element_id'],
            "custom_get_params" => $payload['custom_get_params'],
            "custom_post_params" => $payload['custom_post_params'],
            "session_id" => $payload['session_id'],
            "user_email" => $this->verifyEmail($payload['user_email']) ? $payload['user_email'] : null,
        ];

        TrackedEvents::create($eventData);
    }
}
