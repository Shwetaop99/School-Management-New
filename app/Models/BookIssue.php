<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookIssue extends Model
{
    protected $fillable = [
    'book_id',
    'student_id',
    'issue_date',
    'due_date',
    'return_date',
    'status',
    'fine',
    'fine_type',
    'fine_reason',
    'fine_status',
    'remarks',
];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'fine' => 'decimal:2',
    ];

    /**
     * The book that was issued.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
 * The student who borrowed the book.
 */
public function student(): BelongsTo
{
    return $this->belongsTo(Student::class, 'student_id');
}
}