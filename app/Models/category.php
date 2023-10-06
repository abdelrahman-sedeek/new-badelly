<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class category extends Model
{
    use SoftDeletes;
    // protected $with = ['subcategory'];
    protected $gaurded='';
    public function subcategories()
    {
        return $this->hasMany(Subcategory::class, 'category_id');
    }
    public function products()
    {
        return $this->hasMany(Product::class,'category_id');
    }
}
