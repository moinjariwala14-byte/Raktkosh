<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Camp extends Model
{
    protected $guarded = [];

    public function bloodBank()
    {
        return $this->belongsTo(BloodBank::class);
    }
}
