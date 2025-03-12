<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrackedEvents>
 */
class TrackedEventsFactory extends Factory {

    private $articles = [
        "https://news.integys.com/news/enti-locali-e-dpo-in-servizio-un-approfondimento-necessario",
        "https://news.integys.com/news/l-affidamento-del-dpo-negli-enti-locali-spunti-di-valutazione",
        "https://news.integys.com/news/la-sicurezza-informatica-negli-enti-locali-sfide-e-strategie-nell-era-digitale",
        "https://news.integys.com/news/l-importanza-delle-competenze-digitali-nell-era-della-cybersecurity",
        "https://news.integys.com/news/crimini-informatici-analisi-prevenzione-e-compliance-integrata-e-collaborativa-dpo-e-odv",
    ];

    private $buttons = [
        "BTN-INT-1",
        "BTN-INT-2",
        "BTN-INT-3",
        "BTN-INT-4",
        "BTN-INT-5",
    ];


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {

        $event = $this->faker->randomElement(['PAGE_VIEW', 'ARTICLE_VIEW', 'DID_PRESS_BUTTON']);

        if ($event === "ARTICLE_VIEW") {
            $url = $this->faker->randomElement($this->articles);
        } else {
            $url = "https://news.integys.com";
        }

        if ($event === "DID_PRESS_BUTTON") {
            $element_id = $this->faker->randomElement($this->buttons);
        } else {
            $element_id = "";
        }

        return [
            //
            'event_code' => $this->faker->randomElement(['PAGE_VIEW', 'ARTICLE_VIEW', 'DID_PRESS_BUTTON']),
            'ip_address' => $this->faker->ipv4,
            'ip_country' => "Italy",
            'ip_city' => $this->faker->city,
            'ip_zip' => $this->faker->postcode,
            'user_agent' => $this->faker->userAgent,
            'referer' => $this->faker->url,
            'url' => $url,
            'scroll_percentage' => $this->faker->numberBetween(0, 100),
            'utm_source' => $this->faker->word,
            'utm_medium' => $this->faker->word,
            'utm_campaign' => $this->faker->word,
            'utm_term' => $this->faker->word,
            'utm_content' => $this->faker->word,
            'element_id' => $element_id,
            'custom_get_params' => "{}",
            'custom_post_params' => "{}",
            'session_id' => $this->faker->uuid,
        ];
    }
}
