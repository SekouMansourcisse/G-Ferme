<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'calendar_event_master';
    protected $primaryKey = 'event_id';

    protected $fillable = [
        'event_name',
        'event_start_date',
        'event_end_date',
        'description',
    ];
}

