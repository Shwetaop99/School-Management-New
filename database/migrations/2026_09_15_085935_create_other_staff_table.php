<?php

<<<<<<< HEAD
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtherStaff extends Model
{
    protected $table = 'other_staff';

    protected $fillable = [
        'staff_id',
        'name',
        'profile_photo',
        'gender',
        'date_of_birth',
        'phone',
        'email',
        'address',
        'designation',
        'department',
        'qualification',
        'joining_date',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'joining_date' => 'date',
    ];
}
=======
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('other_staff', function (Blueprint $table) {
            $table->id();
            $table->string('staff_id')->unique();
            $table->string('name');
            $table->string('profile_photo')->nullable();
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('designation')->nullable();
            $table->string('department')->nullable();
            $table->string('qualification')->nullable();
            $table->date('joining_date')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('other_staff');
    }
};
>>>>>>> 0a09c488f4a20273ecd9ae676f586922e6c95631
