<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class subcategory extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded= '';

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function products()
    {
        return $this->hasMany(Product::class,'subcategory_id');
    }
    public function subsubcategories()
    {
        return $this->hasMany(Subsubcategory::class, 'Subcategory_id');
    }
}
