<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TrackedEvents extends Model {
    //
    use HasFactory;


    protected $fillable = [
        'event_code',
        'ip_address',
        'ip_country',
        'ip_city',
        'ip_zip',
        'user_agent',
        'referer',
        'url',
        'scroll_percentage',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'element_id',
        'custom_get_params',
        'custom_post_params',
        'session_id',
    ];

    protected static function boot() {
        parent::boot();

        // Applica uno scope globale per escludere i record con "http://localhost:3000/" nel campo "url"
        static::addGlobalScope('excludeLocalhost', function (Builder $builder) {
            $builder->where('url', 'not like', 'http://localhost:3000/%');
        });

        static::addGlobalScope('excludeVercel', function (Builder $builder) {
            $builder->where('url', 'not like', '%vercel%');
        });
    }

    public function domain() {
        $url = $this->url;
        $matches = [];
        if (preg_match('/^(https?:\/\/[^\/]+)/i', $url, $matches)) {
            return $matches[0];
        }
        return $url; // Ritorna l'URL originale se non riesce a estrarre il dominio
    }

    public function contactFormRequest() {
        return $this->hasOne(ContactFormRequest::class);
    }
}
