<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Armors;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Skills extends Model
{
    use HasFactory;
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
