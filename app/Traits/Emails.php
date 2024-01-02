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

        $recipients = [];

        if ($group = Group::where('name', 'office')->first()) {
          foreach ($group->users as $user) {
              $recipients[] = $user->email;
          }
        }

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
        $recipients = [];

        if ($group = Group::where('name', 'decision-maker')->first()) {
          foreach ($group->users as $user) {
              $recipients[] = $user->email;
          }
        }

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
        $recipients = [];

        if ($group = Group::where('name', 'office')->first()) {
          foreach ($group->users as $user) {
              $recipients[] = $user->email;
          }
        }

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
        // Get the status and replace underscore (if any) by hyphen as the status will be used as email code.
        $status = str_replace('_', '-', $membership->status);
        // Get the member/applicant.
        $member = $membership->user;

        // The subscription fee amount is required.
        if ($status == 'pending-subscription') {
            $prices = Setting::getDataByGroup('prices', $membership);
            $member->subscription_fee = ($membership->associated_member) ? $prices['associated_subscription_fee'] : $prices['subscription_fee'];
        }

        if (Email::sendEmail($status, $member)) {
            return true;
        }

        return false;
    }
}

