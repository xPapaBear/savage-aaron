<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Multiplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'value', 'label', 'giveaway_start_date', 'giveaway_end_date', 'status'
    ];
}
