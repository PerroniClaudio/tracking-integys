<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TrackedEvents;

class ContactFormRequest extends Model {
    /** @use HasFactory<\Database\Factories\ContactFormRequestFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'business_name',
        'subject',
        'message',
        'tracked_event_id',
    ];

    public function trackedEvent() {
        return $this->belongsTo(TrackedEvents::class, 'tracked_event_id', 'id');
    }
}
