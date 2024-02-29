<?php

namespace App\Traits;

use App\Models\Cms\Email;
use App\Models\User;
use App\Models\Membership;
use App\Models\Cms\Setting;
use App\Models\Cms\Payment;
use App\Models\Membership\Setting as MembershipSetting;
use Carbon\Carbon;

trait Renewal
{
    /*
     * Returns the running renewal date.
     */
    public function getRenewalDate(): Carbon
    {
        $renewal = Setting::getDataByGroup('renewal', Membership::class);

        return new Carbon(date('Y').'-'.$renewal['month'].'-'.$renewal['day']);
    }

    /*
     * Returns the new renewal date.
     */
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

    public function isRenewalPeriod(): bool
    {
        $today = Carbon::today();
        // Get the number of days the renewal period goes on.
        $days = Setting::getValue('renewal', 'period', 0, Membership::class);
        // Compute the starting of the renewal period which is the renewal date minus the number of days in the renewal period.
        $renewalPeriodStart = $this->getLatestRenewalDate()->subDays($days);

        return ($today->greaterThanOrEqualTo($renewalPeriodStart)) ? true : false;
    }

    public function isFreePeriod(): bool
    {
        $renewal = $this->getRenewalDate(); 
        $today = Carbon::today();

        // Get the number of days the free period goes on.
        $days = Setting::getValue('renewal', 'free_period', 0, Membership::class);
        // Compute the starting of the free period which is the renewal date minus the number of days in the free period.
        $freePeriodStart = $this->getRenewalDate()->subDays($days);

        return ($today->greaterThanOrEqualTo($freePeriodStart) && $today->lessThan($renewal)) ? true : false;
    }

    public function checkRenewal(): string
    {
        $runningRenewalDate = Setting::getDataByGroup('running_renewal_date', Membership::class);
        // Set to the current renewal date in case the $runningRenewalDate variable is null.
        $runningRenewalDate = ($runningRenewalDate) ? Carbon::create($runningRenewalDate) : $this->getRenewalDate();

        // The renewal period has started.
        if ($this->isRenewalPeriod() && $runningRenewalDate->lessThan($this->getLatestRenewalDate())) {
            // Reset all the member statuses to pending_renewal
            Membership::where('status', 'member')->update(['status' => 'pending_renewal']);
            // Cancel all the possible old pending payments.
            Payment::where('status', 'pending')->whereHas('membership', function ($query) {
                $query->where('status', 'pending_renewal');
            })->update(['status' => 'cancelled']);

            // Set the flag to the new running renewal date (ie: the latest renewal date).
            MembershipSetting::setRunningRenewalDate($this->getLatestRenewalDate()->format('Y-m-d'));

            return 'start_renewal';
        }

        // No action has been performed.
        return 'no_renewal_action';
    }
}

