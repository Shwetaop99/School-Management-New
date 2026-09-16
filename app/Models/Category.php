<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Category;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    /**
     * A category can have many books.
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}