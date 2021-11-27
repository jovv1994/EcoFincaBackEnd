<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    public function cantones()
    {
        return $this->hasMany('App\Models\Canton');
    }
}


