<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Skills;
use App\Models\Sets;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Armors extends Model
{
    use HasFactory;
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
        return $this->belongsToMany(Sets::class, 'sets_have_armors', 'id_armors', 'id_sets');
    }

    public function skills() 
    {
        return $this->belongsToMany(Skills::class, 'armors_have_skills', 'id_armors', 'id_skills')->withPivot('level');
    }

    public function getType()
    {
        return ['Helmet', 'Chest', 'Gloves', 'Faulds', 'Boots'];
    }
}
