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
     * Returns the renewal date.
     */
    public function getRenewalDate(): Carbon
    {
        $renewal = Setting::getDataByGroup('renewal', Membership::class);

        // Set the renewal date to the current year.
        $renewal = new Carbon(date('Y').'-'.$renewal['month'].'-'.$renewal['day']);
        $today = Carbon::today();

        // The renewal period for the current year is passed.
        if ($today->greaterThan($renewal)) {
            // Set the new renewal date.
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
        $renewalPeriodStart = $this->getRenewalDate()->subDays($days);

        return ($today->greaterThanOrEqualTo($renewalPeriodStart)) ? true : false;
    }

    public function isFreePeriod(): bool
    {
        $renewal = $this->getRenewalDate(); // new
        $today = Carbon::today();

        // Get the number of days the free period goes on.
        $days = Setting::getValue('renewal', 'free_period', 0, Membership::class);
        // Compute the starting of the free period which is the renewal date minus the number of days in the free period.
        $freePeriodStart = $this->getRenewalDate()->subDays($days);

        return ($today->greaterThanOrEqualTo($freePeriodStart) && $today->lessThan($renewal)) ? true : false;
    }

    public function checkRenewal(): string
    {
        $oldRenewalDate = MembershipSetting::getOldRenewalDate();

        // Check the old renewal date variable is set.
        if (!$oldRenewalDate) {
            return 'old_renewal_date_undefined';
        }

        // The renewal period has started.
        if ($this->isRenewalPeriod() && $oldRenewalDate->lessThan($this->getRenewalDate())) {
            // Reset all the member statuses to pending_renewal
            Membership::where('status', 'member')->update(['status' => 'pending_renewal']);
            // Cancel all the possible old pending payments.
            Payment::where('status', 'pending')->whereHas('membership', function ($query) {
                $query->where('status', 'pending_renewal');
            })->update(['status' => 'cancelled']);

            // This renewal date will become the old renewal date after the renewal period is over.
            MembershipSetting::setOldRenewalDate($this->getRenewalDate()->format('Y-m-d'));

            return 'start_renewal';
        }

        // No action has been performed.
        return 'no_renewal_action';
    }
}

