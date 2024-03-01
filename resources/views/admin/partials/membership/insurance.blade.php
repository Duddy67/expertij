
@if ($membership->hasInsurance())
    @php $insurance = $membership->getInsurance(); @endphp
    @php $status = ($membership->status == 'member') ? __('labels.generic.running') : __('labels.generic.expired'); @endphp
    <table class="table table-striped mt-4">
        <thead>
            <tr>
                <th scope="col">{{ __('labels.generic.name') }}</th>
                <th scope="col">{{ __('labels.generic.amount') }}</th>
                <th scope="col">{{ __('labels.membership.coverage') }}</th>
                <th scope="col">{{ __('labels.generic.status') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
               <td>{{ $insurance->name }}</td>
               <td>{{ $insurance->price }}</td>
               <td>{{ $insurance->coverage }}</td>
               <td>{{ $status }}</td>
            </tr>
        </tbody>
    </table>
@else
    <div class="alert alert-info mt-4" role="alert">
        {{ __('messages.generic.no_item_found') }}
    </div>
@endif
