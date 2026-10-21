<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentHealthRecord extends Model
{
    use HasFactory;

    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | Student / Checkup Information
        |--------------------------------------------------------------------------
        */

        'student_id',
        'academic_year',
        'checkup_date',
        'checkup_type',
        'doctor_name',
        'health_center',
        'conducted_by',


        /*
        |--------------------------------------------------------------------------
        | Physical Examination
        |--------------------------------------------------------------------------
        */

        'height',
        'weight',
        'bmi',
        'pulse_rate',
        'blood_pressure',
        'temperature',


        /*
        |--------------------------------------------------------------------------
        | Vision
        |--------------------------------------------------------------------------
        */

        'vision_right',
        'vision_left',
        'near_vision_right',
        'near_vision_left',
        'uses_spectacles',
        'spectacle_power',


        /*
        |--------------------------------------------------------------------------
        | Dental
        |--------------------------------------------------------------------------
        */

        'dental_status',
        'dental_caries',
        'gum_problem',
        'oral_hygiene',


        /*
        |--------------------------------------------------------------------------
        | ENT
        |--------------------------------------------------------------------------
        */

        'right_ear',
        'left_ear',
        'hearing_problem',
        'nose_status',
        'throat_status',


        /*
        |--------------------------------------------------------------------------
        | General Health
        |--------------------------------------------------------------------------
        */

        'general_health',
        'skin_status',
        'respiratory_status',
        'heart_status',
        'abdomen_status',
        'musculoskeletal_status',


        /*
        |--------------------------------------------------------------------------
        | Nutrition
        |--------------------------------------------------------------------------
        */

        'nutritional_status',
        'anemia_screening',


        /*
        |--------------------------------------------------------------------------
        | Medical History
        |--------------------------------------------------------------------------
        */

        'known_health_condition',
        'allergy',
        'current_medication',
        'medical_history',


        /*
        |--------------------------------------------------------------------------
        | Referral / Follow-up
        |--------------------------------------------------------------------------
        */

        'referral_required',
        'referral_to',
        'referral_date',
        'treatment_advised',
        'follow_up_date',
        'follow_up_status',
        'follow_up_remarks',


        /*
        |--------------------------------------------------------------------------
        | Final Assessment
        |--------------------------------------------------------------------------
        */

        'overall_health_status',
        'doctor_remarks',
        'teacher_remarks',
        'parent_remarks',
    ];


    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [

            'checkup_date' => 'date',

            'referral_date' => 'date',

            'follow_up_date' => 'date',

            'height' => 'decimal:2',

            'weight' => 'decimal:2',

            'bmi' => 'decimal:2',

            'temperature' => 'decimal:1',

            'uses_spectacles' => 'boolean',

            'referral_required' => 'boolean',

        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Student Relationship
    |--------------------------------------------------------------------------
    */

    public function student(): BelongsTo
    {
        return $this->belongsTo(
            Student::class,
            'student_id'
        );
    }
}