<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class FeeStructureItem extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'fee_structure_id',
        'fee_type_id',
        'amount',
        'due_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function feeStructure()
    {
        return $this->belongsTo(FeeStructure::class);
    }

    public function feeType()
    {
        return $this->belongsTo(FeeType::class);
    }
}