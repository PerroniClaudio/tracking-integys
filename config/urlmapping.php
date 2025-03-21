<?php

namespace App\Config;

class UrlMapping {

    protected static $prefixes = [
        "https://news.integys.com" => "news_integys",
        "https://integys.com" => "news_integys",
        "https://www.dpodelcomune.com/" => "dpodelcomune"
    ];

    public static function getPrefix($index) {
        if (isset(self::$prefixes[$index])) {
            return self::$prefixes[$index];
        }
        return null;
    }
}
