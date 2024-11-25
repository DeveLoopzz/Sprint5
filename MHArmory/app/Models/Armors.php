<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Armors extends Model
{
    protected $table = 'armors';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'type',
    ];

    public function getType()
    {
        return ['Helmet', 'Chest', 'Gloves', 'Faulds', 'Boots'];
    }
}
