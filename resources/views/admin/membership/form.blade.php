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

        <ul class="nav nav-tabs mt-4" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="membership-tab" data-bs-toggle="tab" data-bs-target="#membership-tab-pane" type="button" role="tab" aria-controls="membership-tab-pane"
                aria-selected="true">@php echo __('labels.title.membership'); @endphp</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="professional_information-tab" data-bs-toggle="tab" data-bs-target="#professional_information-tab-pane" type="button" role="tab" aria-controls="professional_information-tab-pane" aria-selected="false">@php echo __('labels.membership.professional_status'); @endphp</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="licences-tab" data-bs-toggle="tab" data-bs-target="#licences-tab-pane" type="button" role="tab" aria-controls="licences-tab-pane" aria-selected="false">@php echo __('labels.membership.licences'); @endphp</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">@php echo __('labels.generic.profile'); @endphp</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="votes-tab" data-bs-toggle="tab" data-bs-target="#votes-tab-pane" type="button" role="tab" aria-controls="votes-tab-pane" aria-selected="false">@php echo __('labels.membership.votes'); @endphp</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="payments-tab" data-bs-toggle="tab" data-bs-target="#payments-tab-pane" type="button" role="tab" aria-controls="payments-tab-pane" aria-selected="false">@php echo __('labels.generic.payments'); @endphp</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="insurance-tab" data-bs-toggle="tab" data-bs-target="#insurance-tab-pane" type="button" role="tab" aria-controls="insurance-tab-pane" aria-selected="false">@php echo __('labels.membership.insurance'); @endphp</button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            @php
                    $dataTab = null;
                    $dateFormats = [];
            @endphp
            @foreach ($fields as $key => $field)
                @if (isset($field->tab))
                    @php $active = ($field->tab == 'membership') ? 'show active' : '';
                         $dataTab = $field->tab; @endphp
                    <div class="tab-pane fade {{ $active }}" id="{{ $field->tab }}-tab-pane" role="tab-panel" aria-labelledby="{{ $field->tab }}-tab" tabindex="0">
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

            <div class="tab-pane fade {{ $active }}" id="licences-tab-pane" role="tab-panel" aria-labelledby="licences-tab" tabindex="0">
                <div class="container" id="licence-container" data-latest-index="{{ $membership->licences->count() - 1 }}">
                    @foreach ($membership->licences as $i => $licence)
                        @include('admin.partials.membership.licence', ['licence' => $licence, 'i' => $i])
                    @endforeach
                </div> <!-- licence container -->
            </div>

            <div class="tab-pane fade {{ $active }}" id="profile-tab-pane" role="tab-panel" aria-labelledby="profile-tab" tabindex="0">
                @include('admin.partials.membership.profile', ['user' => $membership->user])
            </div>

            <div class="tab-pane fade {{ $active }}" id="votes-tab-pane" role="tab-panel" aria-labelledby="votes-tab" tabindex="0">
                @include('admin.partials.membership.votes', ['user' => $membership->user])
            </div>

            <div class="tab-pane fade {{ $active }}" id="payments-tab-pane" role="tab-panel" aria-labelledby="payments-tab" tabindex="0">
                @include('admin.partials.membership.payments', ['payments' => $membership->payments->sortDesc()])
            </div>

            <div class="tab-pane fade {{ $active }}" id="insurance-tab-pane" role="tab-panel" aria-labelledby="insurance-tab" tabindex="0">
                @include('admin.partials.membership.insurance', ['insurance' => $membership->insurance])
            </div>
        </div>

        <input type="hidden" id="cancelEdit" value="{{ route('admin.memberships.cancel', $query) }}">
        <input type="hidden" id="close" name="_close" value="0">
        <input type="hidden" id="siteUrl" value="{{ url('/') }}">
        <x-js-messages />

        @if (isset($membership))
            <input type="hidden" id="_dateFormat" value="{{ $dateFormat }}">
            <input type="hidden" id="_locale" value="{{ config('app.locale') }}">
            <input type="hidden" id="_sendingEmails" value="{{ $membership->sending_emails }}">
            <input type="hidden" id="_currentStatus" value="{{ $membership->status }}">
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

        <form id="setPayment" action="{{ route('admin.memberships.setPayment', $query) }}" method="post">
            @method('put')
            @csrf
            <input type="hidden" name="payment_status" id="paymentStatus" value="">
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
    <script type="text/javascript" src="{{ asset('/js/admin/membership.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/admin/form.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/admin/disable.toolbars.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/tinymce/filemanager.js') }}"></script>
@endpush
