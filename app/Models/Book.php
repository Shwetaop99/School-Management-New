<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\BookIssue;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'isbn',
        'category',
        'category_id',
        'publisher',
        'quantity',
        'available_quantity',
        'shelf_number',
        'language',
        'publication_date',
        'cover_image',
        'description',
        'status',
    ];

    protected $casts = [
        'publication_date' => 'date',
    ];

    /**
     * A book belongs to one category.
     */
    public function categoryRelation(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function issues(): HasMany
{
    return $this->hasMany(BookIssue::class);
}
}