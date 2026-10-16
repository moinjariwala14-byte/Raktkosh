<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodBatch extends Model
{
    protected $guarded = [];

    public function bloodBank()
    {
        return $this->belongsTo(BloodBank::class);
    }
}
