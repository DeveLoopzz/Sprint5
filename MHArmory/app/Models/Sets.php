<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sets extends Model
{
    protected $table = 'sets';
    public $timestamps = false;
    protected $fillable = [
        'name'
    ];

}
