<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class brand extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'title',
        'image',
        'is_active',
        // Add any other attributes here that you want to be mass assignable
    ];
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
