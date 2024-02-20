@extends ('admin.layouts.default')

@section ('main')
    <h3>@php echo (isset($sharing)) ? __('labels.generic.edit_document_sharing') : __('labels.generic.create_document_sharing'); @endphp</h3>

    @include('admin.partials.x-toolbar')

        <ul class="nav nav-tabs mt-4" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details-tab-pane" type="button" role="tab" aria-controls="details-tab-pane" aria-selected="true">@php echo __('labels.generic.details'); @endphp</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents-tab-pane" type="button" role="tab" aria-controls="documents-tab-pane" aria-selected="false">@php echo __('labels.title.documents'); @endphp</button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade active show" id="details-tab-pane" role="tab-panel" aria-labelledby="details-tab" tabindex="0">
    @php $action = (isset($sharing)) ? route('admin.memberships.sharings.update', $query) : route('admin.memberships.sharings.store', $query) @endphp
    <form method="post" action="{{ $action }}" id="itemForm">
        @csrf

        @if (isset($sharing))
            @method('put')
        @endif

        @foreach ($fields as $field)
            @php $value = (isset($sharing)) ? old($field->name, $field->value) : old($field->name); @endphp
            <x-input :field="$field" :value="$value" />
        @endforeach


        <input type="hidden" id="cancelEdit" value="{{ route('admin.memberships.sharings.cancel', $query) }}">
        <input type="hidden" id="close" name="_close" value="0">
        <x-js-messages />

        @if (isset($sharing))
            <input type="hidden" id="_dateFormat" value="{{ $dateFormat }}">
            <input type="hidden" id="_locale" value="{{ config('app.locale') }}">
        @endif
    </form>
        </div>
        <div class="tab-pane fade mt-4" id="documents-tab-pane" role="tab-panel" aria-labelledby="documents-tab" tabindex="0">
        @include('admin.partials.sharing.document-sharing')
        </div>
        </div>


    @if (isset($sharing))
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