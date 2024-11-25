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

    public function armors()
    {
        return $this->belongsToMany(Armors::class, 'sets_have_armors');
    }
}
