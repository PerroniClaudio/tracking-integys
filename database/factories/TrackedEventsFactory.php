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

    // Indirizzi IP italiani comuni (classi di rete realistiche)
    private $ip_prefixes = [
        '62.94.',      // Telecom Italia
        '151.55.',     // Wind Tre
        '79.12.',      // Fastweb
        '93.63.',      // Vodafone
        '80.104.',     // TIM
        '5.170.',      // Tiscali
        '185.27.',     // Reti aziendali italiane
        '212.171.',    // Operatori regionali
    ];

    // Utilizziamo variabili statiche per condividere i dati tra tutte le istanze
    private static $ip_addresses = [];
    private static $session_codes = [];
    private static $user_sessions = []; // Mappa sessione => IP per coerenza
    private static $initialized = false;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        // Generiamo i dati realistici se non ancora inizializzati
        if (!self::$initialized) {
            $this->generateRealisticData();
            self::$initialized = true;
        }

        // Scegli una sessione casuale
        $session_id = $this->faker->randomElement(self::$session_codes);
        $session_data = self::$user_sessions[$session_id];

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
            'event_code' => $event,
            'ip_address' => $session_data['ip'],
            'ip_country' => "Italy",
            'ip_city' => $session_data['city'],
            'ip_zip' => $session_data['zip'],
            'user_agent' => $session_data['user_agent'],
            'referer' => $this->faker->randomElement([
                "search_engine",
                "social_network",
                "direct_traffic",
                "referral",
                "email"
            ]),
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
            'session_id' => $session_id,
            'created_at' => $this->faker->dateTimeBetween('-3 month', 'now'),
        ];
    }

    /**
     * Genera dati realistici per le sessioni utente
     */
    private function generateRealisticData() {
        // Generazione di 100 sessioni uniche
        for ($i = 0; $i < 100; $i++) {
            $session_id = $this->faker->uuid;
            self::$session_codes[] = $session_id;

            // Assegna un indirizzo IP realistico per l'Italia a questa sessione
            $ip_prefix = $this->faker->randomElement($this->ip_prefixes);
            $ip = $ip_prefix . $this->faker->numberBetween(1, 254) . '.' . $this->faker->numberBetween(1, 254);
            self::$ip_addresses[] = $ip;

            // Associa la sessione all'IP (un utente mantiene lo stesso IP durante una sessione)
            self::$user_sessions[$session_id] = [
                'ip' => $ip,
                'user_agent' => $this->faker->userAgent,
                'city' => $this->faker->city,
                'zip' => $this->faker->postcode
            ];
        }
    }
}
