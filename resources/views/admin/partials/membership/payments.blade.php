@if ($payments->isEmpty())
    <div class="alert alert-info" role="alert">
        {{ __('messages.generic.no_item_found') }}
    </div>
@else
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">{{ __('labels.generic.status') }}</th>
                <th scope="col">{{ __('labels.generic.payment_mode') }}</th>
                <th scope="col">{{ __('labels.generic.amount') }}</th>
                <th scope="col">{{ __('labels.generic.created_at') }}</th>
                <th scope="col">{{ __('labels.generic.item') }}</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
        @foreach ($payments as $payment)
            <tr> 
                <td>
                    @if ($payment->status != 'pending')
                        {{ __('labels.membership.'.$payment->status) }}
                    @else
                        <select name="_payment_status" class="form-control" id="payment-status">
                            <option value="pending" {{ $payment->status == 'pending' ? 'selected' : '' }}>{{ __('labels.membership.pending') }}</option>
                            <option value="completed" {{ $payment->status == 'completed' ? 'selected' : '' }}>{{ __('labels.membership.completed') }}</option>
                            <option value="cancelled" {{ $payment->status == 'cancelled' ? 'selected' : '' }}>{{ __('labels.membership.cancelled') }}</option>
                        </select>
                    @endif
                </td>
                <td>{{ __('labels.generic.'.$payment->mode) }}</td>
                <td>{{ $payment->amount.' '.$payment->currency }}</td>
                <td>@date ($payment->created_at)</td>
                <td>{{ __('labels.membership.'.$payment->item) }}</td>
                <td>
                    <button type="button" id="save-payment-status" class="btn btn-space btn-success"><i class="fa fa-save"></i> Sauvegarder</button>
                </td>
            </tr> 
        @endforeach
        </tbody>
    </table>
@endif
