<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinsConfig extends Model
{
    use HasFactory;
    protected $table = 'coins_conf';
    protected $fillable = [
        'status',
        'valor',
        'nome',
        'created_at',
        'updated_at',
    ];
}
