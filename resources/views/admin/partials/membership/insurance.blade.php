
@if ($insurance)
@else
    <div class="alert alert-info mt-4" role="alert">
        {{ __('messages.generic.no_item_found') }}
    </div>
@endif
