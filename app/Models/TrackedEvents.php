<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackedEvents extends Model {
    //

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
}
