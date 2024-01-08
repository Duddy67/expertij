<?php

namespace App\Traits;

use App\Models\Cms\Email;
use App\Models\User;
use App\Models\Membership;
use App\Models\Cms\Setting;
use Carbon\Carbon;

trait Renewal
{
    public function getRenewalDate(): Carbon
    {
        $renewal = Setting::getDataByGroup('renewal', Membership::class);

        return new Carbon(date('Y').'-'.$renewal['month'].'-'.$renewal['day']);
    }

    public function getLatestRenewalDate(): Carbon
    {
        $renewal = $this->getRenewalDate(); 
        $today = Carbon::today();

        // The renewal period for the current year is passed.
        if ($today->greaterThan($renewal)) {
            $renewal->addYear();
        }

        return $renewal;
    }

    public function isFreePeriod(): bool
    {
        $renewal = $this->getRenewalDate(); 
        $today = Carbon::today();

        // Get the number of days the free period goes on.
        $days = Setting::getValue('renewal', 'free_period', 0, Membership::class);
        // Compute the starting of the free period which is the renewal date minus the number of days in the free period.
        $freePeriodStarting = $this->getRenewalDate()->subDays($days);

        return ($today->greaterThanOrEqualTo($freePeriodStarting) && $today->lessThan($renewal)) ? true : false;
    }
}

