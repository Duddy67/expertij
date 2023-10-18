@if ($payments->isEmpty())
    <div class="alert alert-info" role="alert">
        {{ __('messages.generic.no_item_found') }}
    </div>
@else
@endif
