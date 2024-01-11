<?php

namespace App\Traits;

use App\Models\Cms\Email;
use App\Models\User;
use App\Models\Membership;
use App\Models\Cms\Setting;
use App\Models\Membership\Setting as MembershipSetting;
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

    public function isRenewalPeriod(): bool
    {
        $today = Carbon::today();
        // Get the number of days the renewal period goes on.
        $days = Setting::getValue('renewal', 'period', 0, Membership::class);
        // Compute the starting of the renewal period which is the renewal date minus the number of days in the renewal period.
        $renewalPeriodStart = $this->getLatestRenewalDate()->subDays($days);

        return ($today->greaterThanOrEqualTo($renewalPeriodStart)) ? true : false;
    }

    public function isReminderTime(): bool
    {
        $today = Carbon::today();
        $days = Setting::getValue('renewal', 'reminder', '0', Membership::class);

        // Subtract x days before the renewal date.
        if (str_starts_with($days, '-')) {
            $reminderTime = $this->getLatestRenewalDate()->subDays($days);
        }
        // Add x days after the renewal date.
        else {
            $reminderTime = $this->getRenewalDate()->addDays($days);
        }

        return ($today->greaterThanOrEqualTo($reminderTime)) ? true : false;
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

    public function setRenewal(): string
    {
        // The renewal period has started.
        if (!MembershipSetting::checkFlag('renewal_reset') && $this->isRenewalPeriod()) {
            // Resets all the member statuses to pending_renewal
            //Membership::where('status', 'member')->update(['status' => 'pending_renewal']);
            // Activate the reset flag.
            MembershipSetting::toggleFlag('renewal_reset');

            return 'start_renewal';
        }
        // The renewal period is over.
        elseif (MembershipSetting::checkFlag('renewal_reset') && !$this->isRenewalPeriod()) {
            // Deactivate the reset flag.
            MembershipSetting::toggleFlag('renewal_reset');

            return 'stop_renewal';
        }

        return 'all_clear';
    }

    public function setReminder(): string
    {
        if (!MembershipSetting::checkFlag('renewal_reminder') && $this->isReminderTime()) {
            // Activate the reminder flag.
            MembershipSetting::toggleFlag('renewal_reminder');

            return 'reminder_time';
        }
        elseif (MembershipSetting::checkFlag('renewal_reminder') && !$this->isReminderTime()) {
            // Deactivate the reminder flag.
            MembershipSetting::toggleFlag('renewal_reminder');

            return 'stop_reminder_time';
        }

        return 'all_clear';
    }
}

