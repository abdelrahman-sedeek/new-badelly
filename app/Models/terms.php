<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class terms extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = ['title', 'parent_id','created_at'];

    public function children()
    {
        return $this->hasMany(terms::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(terms::class, 'parent_id');
    }
}
