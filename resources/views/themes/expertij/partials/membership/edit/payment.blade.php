<form action="{{ route('memberships.payment') }}">
  <div id="payment-modes">
  <table class="table">
    <tr>
      <td>
	<h6>Insurance description</h6>
	Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
      </td>
    </tr>
    <tr>
      <td>
	<input type="radio" id="f0" name="insurance_code" value="f0" checked>
	No Insurance
      </td>
    </tr>

    @foreach ($prices as $key => $price)
        @if (str_starts_with($key, 'insurance_fee'))
            @php $code = substr($key, strpos($key, '_') + 5); @endphp
            <tr>
                <td>
                    <input type="radio" id="{{ $code }}" name="insurance_code" value="{{ $code }}">
                    {{ __('labels.membership.'.$key) }} - {{ $price }} E
                </td>
            </tr>
        @endif
    @endforeach
    <tr>
      <td>
        @php $subscriptionFee = ($membership->associated_member) ? $prices['associated_subscription_fee'] : $prices['subscription_fee']; @endphp
	<h6>Subscription description: {{ $subscriptionFee }} E</h6>
	Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
      </td>
    </tr>
      @if ($membership->free_period)
          <tr>
            <td>
              <input type="radio" id="free-period" name="payment_mode" value="free_period" checked>
              Free Period
            </td>
          </tr>
      @else
          <tr>
            <td>
              <input type="radio" id="cheque" name="payment_mode" value="cheque" checked>
              Cheque payment
            </td>
          </tr>
          <tr>
            <td>
              <input type="radio" id="bank-transfer" name="payment_mode" value="bank_transfer">
              Bank transfer
            </td>
          </tr>
      @endif
      <tr>
	<td>
	  <input type="hidden" name="item" value="subscription">
	  <button type="submit" id="btn-payment" class="btn btn-success">{{ __('labels.generic.pay_now') }}</button>
	</td>
      </tr>
  </table>
  </div>
</form>

