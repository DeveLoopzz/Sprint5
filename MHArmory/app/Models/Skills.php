<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skills extends Model
{
    protected $table = 'skills';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'effect',
    ];

    public function getEffect($level) {
        
    }

    
}