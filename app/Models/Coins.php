<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coins extends Model
{
    use HasFactory;
    protected $table = 'coins';
    protected $fillable = [
        'quantia',
        'valor',
        'usuario',
        'status',
        'carteira',
        'created_at',
        'updated_at',
    ];
}
