<?php

namespace App\Traits;

use App\Models\Cms\Email;
use App\Models\User;
use App\Models\User\Group;
use App\Models\Membership;
use App\Models\Cms\Setting;

trait Emails
{
    public function membershipRequest(User $applicant): bool
    {
        $result = true;

        // Send a notification to the applicant.
        if (!Email::sendEmail('candidate-application', $applicant)) {
            $result = false;
        }

        // Inform the users from the office group about the request.

        $recipients = $this->getRecipientsByGroup('office');

        if (!empty($recipients)) {
            $data = new \stdClass();
            $data->first_name = $applicant->first_name;
            $data->last_name = $applicant->last_name;
            $data->recipients = $recipients;

            if (!Email::sendEmail('membership-request', $data)) {
                $result = false;
            }
        }

        return $result;
    }

    /*
     * Informs the decision makers about the newest membership request.
     */
    public function decisionMakersAlert(User $applicant): bool
    {
        $recipients = $this->getRecipientsByGroup('decision-maker');

        if (!empty($recipients)) {
            $data = new \stdClass();
            $data->first_name = $applicant->first_name;
            $data->last_name = $applicant->last_name;
            $data->recipients = $recipients;

            if (Email::sendEmail('decision-makers-alert', $data)) {
                return true;
            }
        }

        return false;
    }

    /*
     * Informs some administrators about a new vote regarding a membership request.
     */
    public function voteAlert(User $decisionMaker, User $applicant): bool
    {
        $recipients = $this->getRecipientsByGroup('office');

        if (!empty($recipients)) {
            $data = new \stdClass();
            $data->first_name = $decisionMaker->first_name;
            $data->last_name = $decisionMaker->last_name;
            $data->applicant_first_name = $applicant->first_name;
            $data->applicant_last_name = $applicant->last_name;
            $data->recipients = $recipients;

            if (Email::sendEmail('vote-alert', $data)) {
                return true;
            }
        }

        return false;
    }

    /*
     * Sends the appropriate email to the member/applicant according to the given status change.
     * Handled statuses: pending_subscription, refused, revoked, cancelled.
     */
    public function statusChange(Membership $membership): bool
    {
        // Get the status and replace underscore (if any) by hyphen as the status is used as email code.
        $code = str_replace('_', '-', $membership->status);
        // Get the member or applicant.
        $user = $membership->user;

        // The subscription fee amount is required.
        if ($code == 'pending-subscription') {
            $prices = Setting::getDataByGroup('prices', $membership);
            $user->subscription_fee = ($membership->associated_member) ? $prices['associated_subscription_fee'] : $prices['subscription_fee'];
        }

        if (Email::sendEmail($code, $user)) {
            return true;
        }

        return false;
    }

    public function member(Membership $membership, bool $isNew): bool
    {
        // Get the new member.
        $member = $membership->user;

        $code = ($isNew) ? 'new-member' : 'subscription-renewal';
        // Check if the new member registered during the free period and set the email code accordingly.
        $code = ($membership->free_period) ? 'new-member-free-period' : $code;

        if (Email::sendEmail($code, $member)) {
            return true;
        }

        return false;
    }

    public function payment(Membership $membership): bool
    {
        $user = $membership->user;
        $payment = $membership->getLastPayment();
        $user->amount = $payment->amount;
        $user->item = __('labels.generic.'.$payment->item);
        $user->payment_mode = __('labels.generic.'.$payment->mode);
        $code = 'payment-'.$payment->status;

        if (!Email::sendEmail($code, $user)) {
            return false;
        }

        // Informs the administrators about the payment.
        $code = $code.'-alert';
        $recipients = $this->getRecipientsByGroup('office');

        if (!empty($recipients)) {
            $user->recipients = $recipients;

            if (!Email::sendEmail($code, $user)) {
                return false;
            }
        }

        return true;
    }

    public function getRecipientsByGroup($groupName): array
    {
        $recipients = [];

        if ($group = Group::where('name', $groupName)->first()) {
            foreach ($group->users as $user) {
                $recipients[] = $user->email;
            }
        }

        return $recipients;
    }
}

