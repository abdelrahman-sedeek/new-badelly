<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class access extends Model
{
    protected $guarded='';
    public function perposals()
    {
        return $this->hasMany(perposal::class, 'perposal_id');
    }
    public function products()
    {
        return $this->hasMany(product::class, 'product_id');
    }
}
