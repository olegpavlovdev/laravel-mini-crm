<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ticket extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments')
            ->useDisk('public')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'application/pdf', 'image/gif', 'text/plain'])
            ->singleFile(false);
    }

    public const STATUS_NEW = 'new';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_PROCESSED = 'processed';

    protected $fillable = [
        'customer_id',
        'subject',
        'message',
        'status',
        'manager_response_date',
    ];

    protected $dates = [
        'manager_response_date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function scopeCreatedBetween($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }
}