<?php

namespace App\Jobs;

use App\Models\TrackedEvents;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class RegisterArticleViewEvent implements ShouldQueue {
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
        $response = Http::get("http://ip-api.com/json/{$ip_address}", [
            "fields" => "status,message,country,countryCode,region,regionName,city,district,zip,lat,lon,timezone,isp,org,as,query"
        ]);

        if ($response->successful()) {
            return $response;
        } else {
            return "Unknown";
        }
    }

    /**
     * Execute the job.
     */
    public function handle(): void {
        //

        $payload = $this->payload;
        $response = $this->getCountry($payload['ip_address']);

        if ($response != "Unknown") {
            TrackedEvents::create([
                "event_code" => "ARTICLE_VIEW",
                "ip_address" => $payload['ip_address'],
                "ip_country" => $response['country'],
                "ip_city" => $response['city'],
                "ip_zip" => $response['zip'],
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
            ]);
        } else {

            TrackedEvents::create([
                "event_code" => "ARTICLE_VIEW",
                "ip_address" => $payload['ip_address'],
                "ip_country" => "Unknown",
                "ip_city" => "Unknown",
                "ip_zip" => "Unknown",
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
            ]);
        }
    }
}
