<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\IdCard;
use App\Models\SchoolLeavingCertificate;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'students';

    protected $fillable = [

        // =====================================================
        // BASIC INFORMATION
        // =====================================================
        'student_id',
        'roll_number',

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

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'admission_date' => 'date',
        ];
    }

    /**
     * Full student name.
     */
    public function getFullNameAttribute(): string
    {
        return trim(
            collect([
                $this->first_name,
                $this->middle_name,
                $this->last_name,
            ])->filter()->implode(' ')
        );
    }

    public function student()
{
    return $this->belongsTo(Student::class, 'student_id');
}

/**
 * Student can have multiple ID cards.
 */
public function idCards(): HasMany
{
    return $this->hasMany(
        IdCard::class,
        'student_id'
    );
}

/**
 * Student attendance records.
 */
public function attendances(): HasMany
{
    return $this->hasMany(Attendance::class);
}

public function schoolLeavingCertificates()
{
    return $this->hasMany(SchoolLeavingCertificate::class);
}

public function supplyKits()
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