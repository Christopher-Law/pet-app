<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pet extends Model
{
    use HasFactory;
    use HasUlids;
    use HasTimestamps;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'breed',
        'date_of_birth',
        'sex',
        'is_dangerous_animal',
    ];

}
