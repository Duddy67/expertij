@extends ('admin.layouts.default')

@section ('main')
    <h3>@php echo (isset($item)) ? __('labels.generic.edit_document_sharing') : __('labels.generic.create_document_sharing'); @endphp</h3>

    @include('admin.partials.x-toolbar')

    @php $action = (isset($item)) ? route('admin.memberships.sharings.update', $query) : route('admin.memberships.sharings.store', $query) @endphp
    <form method="post" action="{{ $action }}" id="itemForm">
        @csrf

        @if (isset($item))
            @method('put')
        @endif

        @foreach ($fields as $field)
            @php $value = (isset($item)) ? old($field->name, $field->value) : old($field->name); @endphp
            <x-input :field="$field" :value="$value" />
        @endforeach

        @include('admin.partials.document-sharing')

        <input type="hidden" id="cancelEdit" value="{{ route('admin.memberships.sharings.cancel', $query) }}">
        <input type="hidden" id="close" name="_close" value="0">
        <x-js-messages />

        @if (isset($item))
            <input type="hidden" id="_dateFormat" value="{{ $dateFormat }}">
            <input type="hidden" id="_locale" value="{{ config('app.locale') }}">
        @endif
    </form>

    @if (isset($item))
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
    <script type="text/javascript" src="{{ asset('/js/admin/document.sharing.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/admin/form.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/admin/disable.toolbars.js') }}"></script>
@endpush
