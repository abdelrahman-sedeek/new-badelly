<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class subsubcategory extends Model
{
    use HasFactory;

    use SoftDeletes;
    protected $guarded= '';

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id')->withDefault();
    }
    public function products()
    {
        return $this->hasMany(Product::class,'subsubcategory_id');
    }
}
