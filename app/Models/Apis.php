<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apis extends Model
{
    public $timestamps = true;
    use HasFactory;
    public function keys (){
        return $this->hasMany(Keys::class);
    }
}
