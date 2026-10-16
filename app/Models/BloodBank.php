<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodBank extends Model
{
    protected $guarded = [];

    public function batches()
    {
        return $this->hasMany(BloodBatch::class);
    }

    public function camps()
    {
        return $this->hasMany(Camp::class);
    }
}
