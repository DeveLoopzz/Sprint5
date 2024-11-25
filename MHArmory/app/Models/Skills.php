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

    public function armors()
    {
        return $this->belongsToMany(Armors::class, 'armors_have_skills')->withPivot('level');
    }

    public function getEffect($level) {
        
    }


}
