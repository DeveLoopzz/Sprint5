<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArmorsHaveSkills extends Model
{
    protected $table = 'armors_have_skills';
    public $timestamps = false;
    
    public function skills()
    {
        return $this->belongsToMany(Skills::class, 'armors_have_skills', 'id_armors', 'id_skills')
                    ->withPivot('level');
    }

}
