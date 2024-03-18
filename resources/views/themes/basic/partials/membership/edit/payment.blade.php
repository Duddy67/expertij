<form action="{{ route('memberships.payment') }}" id="paymentForm" method="post">
  @csrf
  <div id="payment-modes">
  <table class="table">
    @if (($membership->status == 'pending_renewal' && !$membership->free_period) || ($membership->status == 'member' && !$membership->hasInsurance()))
        <tr>
            <td>
                <h6>Insurance description</h6>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
            </td>
        </tr>

        @if ($membership->status == 'pending_renewal')
            <tr>
                <td>
                    <input type="radio" id="f0" name="insurance_code" value="f0" checked>
                    No Insurance
                </td>
            </tr>
        @endif

        @foreach ($prices as $key => $price)
            @if (str_starts_with($key, 'insurance_fee'))
                @php $code = substr($key, strpos($key, '_') + 5); @endphp
                <tr>
                    <td>
                        <input type="radio" id="{{ $code }}" name="insurance_code" value="{{ $code }}" {{ ($key == 'insurance_fee_f1' && $membership->status == 'member') ? 'checked' : '' }}>
                        {{ __('labels.membership.'.$key) }} - {{ $price }} E
                    </td>
                </tr>
            @endif
        @endforeach
    @endif

    @if ($membership->status == 'pending_renewal' || $membership->status == 'pending_subscription')
        <tr>
          <td>
            @php $subscriptionFee = ($membership->associated_member) ? $prices['associated_subscription_fee'] : $prices['subscription_fee']; @endphp
            <h6>Subscription description: {{ $subscriptionFee }} E</h6>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
          </td>
        </tr>
    @endif

    @if ($membership->free_period && $membership->status == 'pending_renewal')
        <tr>
          <td>
            <input type="radio" id="free-period" name="payment_mode" value="free_period" checked>
            {{ __('labels.membership.free_period') }}
          </td>
        </tr>
    @else
        <tr>
          <td>
            <input type="radio" id="cheque" name="payment_mode" value="cheque" checked>
            {{ __('labels.generic.cheque') }}
          </td>
        </tr>
        <tr>
          <td>
            <input type="radio" id="bank-transfer" name="payment_mode" value="bank_transfer">
            {{ __('labels.generic.bank_transfer') }}
          </td>
        </tr>
    @endif
      <tr>
	<td>
	  <button type="button" id="payment-btn" class="btn btn-success">{{ __('labels.generic.pay_now') }}</button>
	</td>
      </tr>
  </table>
  </div>
</form>

