<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Newslatter extends Model
{
    /** @use HasFactory<\Database\Factories\NewslatterFactory> */
    use HasFactory;

    protected $fillable = ['email'];
}
