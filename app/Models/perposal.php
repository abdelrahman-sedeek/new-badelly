<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class perposal extends Model{
        protected $guarded='';
        public function product(){
            return $this->belongsTo(product::class);
        }
        public function user(){
            return $this->belongsTo(product::class);
        }
        
        public function perposal(){
            return $this->belongsTo(perposal::class);
        }

}
