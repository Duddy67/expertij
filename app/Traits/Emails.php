<?php

namespace App\Traits;

use App\Models\Cms\Email;
use App\Models\User;
use App\Models\User\Group;

trait Emails
{
    public function membershipRequest(User $applicant): void
    {
        // Send a notification to the applicant.
        Email::sendEmail('candidate-application', $applicant);

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

            Email::sendEmail('membership-request', $data);
        }
    }

    /*
     * Informs the decision makers about the newest membership request.
     */
    public function alertDecisionMakers(User $applicant): bool
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

            if (Email::sendEmail('alert-decision-makers', $data)) {
                return true;
            }
        }

        return false;
    }
}

