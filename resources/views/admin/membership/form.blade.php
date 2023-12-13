@extends ('admin.layouts.default')

@section ('main')
    <h3>{{ __('labels.membership.edit_membership') }}</h3>

    @include('admin.partials.x-toolbar')

    <form method="post" action="{{ route('admin.memberships.update', $query) }}" id="itemForm" enctype="multipart/form-data">
        @csrf
        @method('put')

         @php $value = (isset($membership)) ? old('name', $membership->user->name) : old('name'); @endphp
         <x-input :field="$fields[0]" :value="$value" />
         @php array_shift($fields); // Remove the very first field (ie: name) from the array. @endphp

        <nav class="nav nav-tabs">
            <a class="nav-item nav-link active" href="#membership" data-toggle="tab">@php echo __('labels.title.membership'); @endphp</a>
            <a class="nav-item nav-link" href="#professional_status" data-toggle="tab">@php echo __('labels.membership.professional_status'); @endphp</a>
            <a class="nav-item nav-link" href="#licences" data-toggle="tab">@php echo __('labels.membership.licences'); @endphp</a>
            <a class="nav-item nav-link" href="#profile" data-toggle="tab">@php echo __('labels.generic.profile'); @endphp</a>
            <a class="nav-item nav-link" href="#payments" data-toggle="tab">@php echo __('labels.generic.payments'); @endphp</a>
            <a class="nav-item nav-link" href="#insurance" data-toggle="tab">@php echo __('labels.membership.insurance'); @endphp</a>
        </nav>

        <div class="tab-content">
            @php
                    $dataTab = null;
                    $dateFormats = [];
            @endphp
            @foreach ($fields as $key => $field)
                @if (isset($field->tab))
                    @php $active = ($field->tab == 'membership') ? ' active' : '';
                         $dataTab = $field->tab; @endphp
                    <div class="tab-pane{{ $active }}" id="{{ $field->tab }}">
                @endif

                @if (isset($field->dataset))
                    @php $field->dataset->tab = $dataTab; @endphp
                @else
                    @php $dataset = (object) ['tab' => $dataTab];
                         $field->dataset = $dataset; @endphp
                @endif

                @php $value = (isset($membership) || str_starts_with($field->name, 'alias_extra_field_')) ? old($field->name, $field->value) : old($field->name); @endphp
                <x-input :field="$field" :value="$value" />

                @if ($field->name == 'image')
                    <div class="col post-image">
                        @php $path = (isset($membership) && $membership->image) ? url('/').$membership->image->getThumbnailUrl() : asset('/images/camera.png'); @endphp
                        <img src="{{ $path }}" id="post-image" />
                        <button type="button" id="deleteDocumentBtn" data-form-id="deleteImage" class="btn btn-danger float-right">Delete image</button>
                    </div>
                @endif

                @if ($field->type == 'date' && isset($field->format))
                     @php $dateFormats[$field->name] = $field->format; @endphp
                @endif

                @if (!next($fields) || isset($fields[$key + 1]->tab))
                    @if ($field->name == 'why_expertij')
                        @if ($membership->professionalAttestation)
                            @include('themes.expertij.partials.membership.edit.attestation-file-button', ['fileUrl' => $membership->professionalAttestation->getUrl(), 'fileName' => $membership->professionalAttestation->file_name])
                        @else
                            @include('themes.expertij.partials.membership.edit.missing-document-button')
                        @endif
                    @endif

                    </div>
                @endif
            @endforeach

            <div class="tab-pane" id="licences">
                <div class="form-group" id="licence-container" data-latest-index="{{ $membership->licences->count() - 1 }}">
                    @foreach ($membership->licences as $i => $licence)
                        @include('admin.partials.membership.licence', ['licence' => $licence, 'i' => $i])
                    @endforeach
                </div> <!-- licence container -->
            </div>

            <div class="tab-pane" id="profile">
                @include('admin.partials.membership.profile', ['user' => $membership->user])
            </div>

            <div class="tab-pane" id="payments">
                @include('admin.partials.membership.payments', ['payments' => $membership->payments])
            </div>

            <div class="tab-pane" id="insurance">
                @include('admin.partials.membership.insurance', ['insurance' => $membership->insurance])
            </div>
        </div>

        <input type="hidden" id="cancelEdit" value="{{ route('admin.memberships.cancel', $query) }}">
        <input type="hidden" id="close" name="_close" value="0">
        <input type="hidden" id="siteUrl" value="{{ url('/') }}">

        @if (isset($membership))
            <input type="hidden" id="_dateFormat" value="{{ $dateFormat }}">
            <input type="hidden" id="_locale" value="{{ config('app.locale') }}">
        @endif

        @foreach ($dateFormats as $key => $value)
            <input type="hidden" name="_date_formats[{{ $key }}]" value="{{ $value }}">
        @endforeach
    </form>

    @if (isset($membership))
        <form id="deleteItem" action="{{ route('admin.memberships.destroy', $query) }}" method="post">
            @method('delete')
            @csrf
        </form>

        <form id="emails" action="{{ route('admin.memberships.sendEmails', $query) }}" method="post">
            @method('put')
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
    <script type="text/javascript" src="{{ asset('/vendor/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/codalia/c.ajax.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/admin/form.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/admin/disable.toolbars.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/admin/membership.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/tinymce/filemanager.js') }}"></script>
@endpush
