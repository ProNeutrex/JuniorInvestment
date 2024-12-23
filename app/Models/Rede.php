<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rede extends Model
{
    use HasFactory;

    public function LevelReferral()
    {
        return $this->hasMany(LevelReferral::class); 
    }
}


