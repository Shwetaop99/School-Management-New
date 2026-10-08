<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\IdCard;
use App\Models\SchoolLeavingCertificate;
use App\Models\Attendance;
use App\Models\StudentSupplyKit;
use App\Models\StudentHealthRecord;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'students';

    /*
    |--------------------------------------------------------------------------
    | Mass Assignable Fields
    |--------------------------------------------------------------------------
    */
    protected $fillable = [

        // =====================================================
        // BASIC INFORMATION
        // =====================================================
'student_id',
'roll_number',
'saral_id',

        'first_name',
        'middle_name',
        'last_name',
        'marathi_name',

        'gender',
        'date_of_birth',
        'birth_place',

        'aadhar_card_no',
        'phone',
        'email',

        'profile_image',

        // =====================================================
        // ACADEMIC INFORMATION
        // =====================================================

        'academic_year',
        'class',
        'section',

        'admission_class',
        'admission_date',

        'register_no',
        'book_no',
        'appar_id',
        'pen_no',

        'medium',
        'mother_tongue',

        'nationality',
        'religion',
        'caste',
        'sub_caste',

        'status',

        // =====================================================
        // PARENTS / GUARDIAN
        // =====================================================

        'father_name',
        'father_phone',
        'father_occupation',

        'mother_name',
        'mother_phone',
        'mother_occupation',

        'guardian_name',
        'guardian_relation',
        'guardian_phone',

        // =====================================================
        // PREVIOUS SCHOOL
        // =====================================================

        'previous_school_name',
        'previous_school_address',
        'previous_school_class',

        'previous_school_medium',
        'previous_school_board',

        'previous_school_result',
        'previous_school_remarks',

        // =====================================================
        // ADDRESS
        // =====================================================

        'address',
        'country',
        'state',
        'district',
        'taluka',
        'city_village',
        'pincode',
    ];

    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'admission_date' => 'date',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Full Student Name
    |--------------------------------------------------------------------------
    */
    public function getFullNameAttribute(): string
    {
        return trim(
            collect([
                $this->first_name,
                $this->middle_name,
                $this->last_name,
            ])
                ->filter()
                ->implode(' ')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Student Relationship
    |--------------------------------------------------------------------------
    */
    public function student()
    {
        return $this->belongsTo(
            Student::class,
            'student_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ID Cards
    |--------------------------------------------------------------------------
    */
    public function idCards(): HasMany
    {
        return $this->hasMany(
            IdCard::class,
            'student_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Attendance Records
    |--------------------------------------------------------------------------
    */
    public function attendances(): HasMany
    {
        return $this->hasMany(
            Attendance::class,
            'student_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | School Leaving Certificates
    |--------------------------------------------------------------------------
    */
    public function schoolLeavingCertificates(): HasMany
    {
        return $this->hasMany(
            SchoolLeavingCertificate::class,
            'student_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Student Supply Kits
    |--------------------------------------------------------------------------
    */
    public function supplyKits(): HasMany
    {
        return $this->hasMany(
            StudentSupplyKit::class,
            'student_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Health Records
    |--------------------------------------------------------------------------
    */
    public function healthRecords(): HasMany
    {
        return $this->hasMany(
            StudentHealthRecord::class,
            'student_id'
        );
    }
}