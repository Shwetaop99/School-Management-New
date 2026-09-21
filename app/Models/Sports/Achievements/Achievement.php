<?php

namespace App\Models\Sports\Achievements;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $table = 'sports_achievements';

    protected $fillable = [
        'title',
        'student_name',
        'sport_name',
        'achievement_type',
        'academic_year',
        'class',
        'section',
        'competition_name',
        'position',
        'achievement_date',
        'venue',
        'description',
        'status',
    ];

    protected $casts = [
        'achievement_date' => 'date',
    ];
}