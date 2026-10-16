<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonorScreening extends Model
{
    protected $guarded = [];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    /**
     * Clinical Mini-Physical Rules:
     * - Weight >= 45 kg
     * - Hemoglobin >= 12.5 g/dL
     * - Pulse: 60 - 100 bpm
     */
    public static function evaluateFitness($weight, $hemoglobin, $pulse): array
    {
        $reasons = [];
        if ($weight < 45.0) {
            $reasons[] = 'Weight under 45 kg minimum threshold';
        }
        if ($hemoglobin < 12.5) {
            $reasons[] = 'Hemoglobin levels below 12.5 g/dL minimum standard';
        }
        if ($pulse < 60 || $pulse > 100) {
            $reasons[] = 'Pulse rate out of acceptable range (60-100 bpm)';
        }

        if (count($reasons) > 0) {
            return [
                'result' => 'Temporary Deferral',
                'reasons' => implode('; ', $reasons)
            ];
        }

        return [
            'result' => 'Fit',
            'reasons' => 'All mini-physical vital indicators verified and cleared.'
        ];
    }
}
