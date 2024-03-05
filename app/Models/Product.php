<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $guarded = [];
    use HasFactory;
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function imageUrl(): string
    {
        return Storage::disk('public')->url($this->photo);
    }
}
