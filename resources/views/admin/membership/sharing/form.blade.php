@extends ('admin.layouts.default')

@section ('main')
    <h3>@php echo (isset($sharing)) ? __('labels.generic.edit_document_sharing') : __('labels.generic.create_document_sharing'); @endphp</h3>

    @include('admin.partials.x-toolbar')

    @php $action = (isset($sharing)) ? route('admin.memberships.sharings.update', $query) : route('admin.memberships.sharings.store', $query) @endphp

    <form method="post" action="{{ $action }}" id="itemForm">
        @csrf

        @if (isset($sharing))
            @method('put')
        @endif

        @php $value = (isset($sharing)) ? old('name', $fields[0]->value) : old('name'); @endphp
        <x-input :field="$fields[0]" :value="$value" />
        @php array_shift($fields); // Remove the very first field (ie: title) from the array. @endphp

        <ul class="nav nav-tabs mt-4" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details-tab-pane" type="button" role="tab" aria-controls="details-tab-pane" aria-selected="true">@php echo __('labels.generic.details'); @endphp</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="shared_with-tab" data-bs-toggle="tab" data-bs-target="#shared_with-tab-pane" type="button" role="tab" aria-controls="shared_with-tab-pane" aria-selected="false">@php echo __('labels.generic.shared_with'); @endphp</button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            @foreach ($fields as $key => $field)
                @if (isset($field->tab))
                    @php $active = ($field->tab == 'details') ? 'show active' : '';
                         $dataTab = $field->tab; @endphp
                    <div class="tab-pane fade {{ $active }}" id="{{ $field->tab }}-tab-pane" role="tab-panel" aria-labelledby="{{ $field->tab }}-tab" tabindex="0">
                @endif

                @if (isset($field->tab) || (isset($field->extra) && in_array('new_row', $field->extra)))
                    <div class="row">
                @endif

                <div class="col-6">
                @php $value = (isset($sharing)) ? old($field->name, $field->value) : old($field->name); @endphp
                <x-input :field="$field" :value="$value" />
                </div>

                @if (isset($fields[$key + 1]->tab) || (isset($fields[$key + 1]->extra) && in_array('new_row', $fields[$key + 1]->extra)) || !isset($fields[$key + 1]))
                    </div>
                @endif

                @if (!next($fields) || isset($fields[$key + 1]->tab))
                    </div>
                @endif
            @endforeach
        </div>


        <input type="hidden" id="cancelEdit" value="{{ route('admin.memberships.sharings.cancel', $query) }}">
        <input type="hidden" id="close" name="_close" value="0">
        <x-js-messages />

        @if (isset($sharing))
            <input type="hidden" id="_dateFormat" value="{{ $dateFormat }}">
            <input type="hidden" id="_locale" value="{{ config('app.locale') }}">
        @else
            @include('admin.partials.sharing.document-sharing')
        @endif
    </form>

    @if (isset($sharing))
        @include('admin.partials.sharing.document-sharing')

        <form id="deleteItem" action="{{ route('admin.memberships.sharings.destroy', $query) }}" method="post">
            @method('delete')
            @csrf
        </form>
    @endif

@endsection

@push ('style')
    <link rel="stylesheet" href="{{ asset('/vendor/codalia/css/c.datepicker.css') }}">
@endpush

@push ('scripts')
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/localeData.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/locale/{{ config('app.locale') }}.js"></script>
    <script type="text/javascript" src="{{ asset('/vendor/codalia/lang/'.config('app.locale').'.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/codalia/c.datepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/datepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/codalia/c.ajax.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/codalia/c.repeater.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/admin/document.sharing.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/admin/form.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/admin/disable.toolbars.js') }}"></script>
@endpush
