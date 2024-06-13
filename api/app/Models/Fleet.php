<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fleet extends Model
{
    use HasFactory;

    protected $table = 'fleet';

    protected $fillable = [
        'category',
        'class',
        'equipment_no',
        'programmed_and_non_programmed_stop',
        'availability',
        'operating_time',
        'availability_use',
        'stand_by',
        'tonnage',
        'ton_per_hour',
    ];

}
