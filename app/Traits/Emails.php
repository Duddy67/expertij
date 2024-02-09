<?php

namespace App\Traits;

use App\Models\Cms\Email;
use App\Models\User;
use App\Models\User\Group;
use App\Models\Membership;
use App\Models\Cms\Setting;
use App\Models\Cms\Payment;
use App\Traits\Renewal;

trait Emails
{
    use Renewal;

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
        $data = new \stdClass();
        $data->first_name = $user->first_name;
        $data->last_name = $user->last_name;
        $data->email = $user->email;

        // The subscription fee amount is required.
        if ($code == 'pending-subscription') {
            $prices = Setting::getDataByGroup('prices', $membership);
            $data->subscription_fee = ($membership->associated_member) ? $prices['associated_subscription_fee'] : $prices['subscription_fee'];
        }

        if (Email::sendEmail($code, $data)) {
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

    public function freePeriodValidation(Membership $membership, Payment $payment): bool
    {
        $user = $membership->user;
        $data = new \stdClass();
        $data->first_name = $user->first_name;
        $data->last_name = $user->last_name;
        $data->email = $user->email;
        $data->payment_date = $payment->updated_at->format('d/m/Y');

        $code = 'free-period-validation';

        if (!Email::sendEmail($code, $data)) {
            return false;
        }

        // Informs the administrators about the validation.
        if (!$this->sendEmailToGroup($code.'-alert', $data, 'office')) {
            return false;
        }

        return true;
    }

    public function offlinePayment(Membership $membership, Payment $payment): bool
    {
        $user = $membership->user;
        $data = new \stdClass();
        $data->first_name = $user->first_name;
        $data->last_name = $user->last_name;
        $data->email = $user->email;
        $data->payment_mode = __('labels.generic.'.$payment->mode);
        $prices = Setting::getDataByGroup('prices', $membership);

        $code = 'offline-payment-';

        if ($payment->item == 'subscription') {
            $code .= 'subscription';
            $data->subscription_fee = ($membership->associated_member) ? $prices['associated_subscription_fee'] : $prices['subscription_fee'];
        }
        elseif (str_starts_with($payment->item, 'subscription_insurance_') || str_starts_with($payment->item, 'insurance_')) {
            $code .= 'insurance';
            // Get the insurance formula (f1, f2...).
            $formula = substr($payment->item, -2);
            $data->insurance_formula = __('labels.membership.insurance_'.$formula);
            $data->insurance_fee = $prices['insurance_fee_'.$formula];

            // The member wants to pay for both the subscription and the insurance.
            if (str_starts_with($payment->item, 'subscription_insurance_')) {
                $data->subscription_fee = ($membership->associated_member) ? $prices['associated_subscription_fee'] : $prices['subscription_fee'];
                $data->total_amount = $data->subscription_fee + $data->insurance_fee;
                $code .= 'subscription-insurance';
            }
        }

        if (!Email::sendEmail($code, $data)) {
            return false;
        }

        // Informs the administrators about the payment.
        if (!$this->sendEmailToGroup($code.'-alert', $data, 'office')) {
            return false;
        }

        return true;
    }

    public function payment(Membership $membership): bool
    {
        $user = $membership->user;
        $payment = $membership->getLastPayment();
        $attachments = [];
        $data = new \stdClass();
        $data->first_name = $user->first_name;
        $data->last_name = $user->last_name;
        $data->email = $user->email;
        $data->amount = $payment->amount;
        $data->item = __('labels.membership.'.$payment->item);
        $data->payment_mode = __('labels.generic.'.$payment->mode);

        // Check for invoices.
        foreach ($payment->invoices as $invoice) {
            $attachments[] = [
                'file' => storage_path('app/public/'.$invoice->disk_name),
                'options' => ['as' => $invoice->file_name, 'mime' => $invoice->content_type]
            ];
        }

        if (!empty($attachments)) {
            $data->attachments = $attachments;
        }

        $code = 'payment-'.$payment->status;

        if (!Email::sendEmail($code, $data)) {
            return false;
        }

        // Don't send possible invoices to administrators.
        if (isset($data->attachments)) {
            unset($data->attachments);
        }

        // Informs the administrators about the payment.
        if (!$this->sendEmailToGroup($code.'-alert', $data, 'office')) {
            return false;
        }

        return true;
    }

    /*
     * Informs the members about the renewal membership.
     */
    public function renewalAlert(): bool
    {
        $recipients = User::whereHas('membership', function ($query) {
            $query->where('status', 'pending_renewal');
        })->pluck('email')->toArray();

        $data = new \stdClass();
        $data->subscription_fee = Setting::getValue('prices', 'subscription_fee', 0, Membership::class);
        $data->associated_subscription_fee = Setting::getValue('prices', 'associated_subscription_fee', 0, Membership::class);
        $data->renewal_date = $this->getRenewalDate()->format('d/m/Y');

        if (!empty($recipients)) {
            $data->recipients = $recipients;

            if (!Email::sendEmail('pending-renewal', $data)) {
                return false;
            }
        }

        return true;
    }

    public function sendEmailToGroup($code, $data, $group): bool
    {
        $recipients = $this->getRecipientsByGroup($group);

        if (!empty($recipients)) {
            $data->recipients = $recipients;

            if (!Email::sendEmail($code, $data)) {
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

