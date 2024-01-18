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

    /*
     * Checks if it's time to send a reminder to the members.
     */
    public function isReminderTime(): bool
    {
        $today = Carbon::today();
        $days = Setting::getValue('renewal', 'reminder', '0', Membership::class);

        // The reminder time starts x days before the renewal date.
        if (str_starts_with($days, '-')) {
            $days = ltrim($days, '-');
            // Subtract x days before the renewal date.
            $reminderTime = $this->getLatestRenewalDate()->subDays($days);
            // The renewal date is the end of the reminder time.
            $endReminderTime = $this->getLatestRenewalDate();
        }
        // The reminder time starts x days after the renewal date.
        else {
            // Add x days after the renewal date.
            $reminderTime = $this->getRenewalDate()->addDays($days);
            // Add x more days to end the reminder time.
            $days = $days * 2;
            $endReminderTime = $this->getRenewalDate()->addDays($days);
        }

        return ($today->greaterThanOrEqualTo($reminderTime) && $today->lessThan($endReminderTime)) ? true : false;
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
            // Reset all the member statuses to pending_renewal
            //Membership::where('status', 'member')->update(['status' => 'pending_renewal']);
            // Cancel all the possible old pending payments.
            /*Payment::where('status', 'pending')->whereHas('membership', function ($query) {
                $query->where('status', 'pending_renewal');
            })->update(['status' => 'cancelled']);*/

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

