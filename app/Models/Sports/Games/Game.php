<?php

namespace App\Models\Sports\Games;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $table = 'sports_games';

    protected $fillable = [
        'title',
        'sport_name',
        'event_type',
        'academic_year',
        'class',
        'section',
        'event_date',
        'start_time',
        'end_time',
        'venue',
        'organizer',
        'description',
        'status',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];
}