<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class product extends Model
{
    use SoftDeletes;

    protected $guarded='';
    public function category()
    {
        return $this->belongsTo(category::class);
    }
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
    public function subsubcategory()
    {
        return $this->belongsTo(subsubcategory::class);
    }
    public function user()
    {
        return $this->belongsTo(user::class);
    }
    public function perposal(){
        return $this->hasMany(perposal::class,'product_id');
    }
    public function access(){
        return $this->belongsTo(access::class);
    }
}
