<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Donor extends Model
{
    protected $guarded = [];

    public function screenings()
    {
        return $this->hasMany(DonorScreening::class);
    }

    /**
     * e-RaktKosh Cooldown Rules:
     * - Male: 90 days cooldown
     * - Female: 120 days cooldown
     */
    public function checkDonationEligibility(): array
    {
        if (!$this->last_donation_date) {
            return ['eligible' => true, 'message' => 'Eligible to donate'];
        }

        $lastDate = Carbon::parse($this->last_donation_date);
        $cooldownDays = ($this->gender === 'Female') ? 120 : 90;
        $nextAllowedDate = $lastDate->copy()->addDays($cooldownDays);

        if (now()->lessThan($nextAllowedDate)) {
            $daysRemaining = now()->diffInDays($nextAllowedDate, false);
            return [
                'eligible' => false,
                'message' => "Mandatory cooldown active. Next eligible date: {$nextAllowedDate->format('d M Y')} ({$daysRemaining} days remaining)."
            ];
        }

        return ['eligible' => true, 'message' => 'Cooldown cleared. Eligible for screening.'];
    }
}
