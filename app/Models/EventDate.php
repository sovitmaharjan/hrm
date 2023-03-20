<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'date'
    ];

    public $casts = [
        'date' => 'date'
    ];
}
