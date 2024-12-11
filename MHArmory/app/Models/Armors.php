<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Skills;
use App\Models\Sets;

class Armors extends Model
{
    protected $table = 'armors';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'type',
    ];
    protected $casts = [
        'effect' => 'array',  
    ];

    public function sets() 
    {
        return $this->belongsToMany(Sets::class, 'sets_have_armors');
    }

    public function skills() 
    {
        return $this->belongsToMany(Skills::class, 'armors_have_skills')->withPivot('level');
    }

    public function getType()
    {
        return ['Helmet', 'Chest', 'Gloves', 'Faulds', 'Boots'];
    }
}
