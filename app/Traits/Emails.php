<?php

namespace App\Traits;

use App\Models\Cms\Email;
use App\Models\User;
use App\Models\User\Group;

trait Emails
{
    public function membershipRequest($applicant)
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

    public function alertDecisionMakers($applicant)
    {

    }
}

