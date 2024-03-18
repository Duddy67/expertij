@include('themes.basic.partials.flash-message')

@if (!empty($rows)) 
    <x-item-list :columns="$columns" :rows="$rows" :url="$url" :checkable="false" />
@else
    <div class="alert alert-info" role="alert">
        {{ __('messages.generic.no_item_found') }}
    </div>
@endif
