<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Balls extends Model
{
    protected $table = 'balls';

    protected $fillable = ['nr_of_colors', 'distribution','groups'];

}
