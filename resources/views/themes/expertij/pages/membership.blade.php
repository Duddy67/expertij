@push('styles')
    <link rel="stylesheet" href="{{ asset('/vendor/adminlte/plugins/daterangepicker/daterangepicker.css') }}">
@endpush

<div class="position-relative">
    @include('themes.expertij.partials.flash-message')
    <form action="#" method="post" enctype="multipart/form-data" id="updateMembership" role="form" class="php-email-form">
        @csrf
        <input type="hidden" id="update" value="{{ route('memberships.update') }}">
        @method('put')

        <nav class="nav nav-tabs">
            <a class="nav-item nav-link active" id="membership-tab" href="#membership" data-bs-toggle="tab">{{ __('labels.title.membership') }}</a>
            <a class="nav-item nav-link" id="licences-tab" href="#licences" data-bs-toggle="tab">{{ __('labels.membership.licences') }}</a>
            <a class="nav-item nav-link" id="professional_information-tab" href="#professional_information" data-bs-toggle="tab">{{ __('labels.membership.professional_status') }}</a>
        </nav>

        <div class="tab-content">
            <div class="tab-pane active" id="membership">
                <div class="col-md-6 form-group">
                    <label for="status">{{ __('labels.generic.status') }}</label>
                    <input type="text" name="_status" class="form-control" id="status" value="{{ $membership->status }}" disabled>
                </div>
            </div>

            <div class="tab-pane" id="licences">
                <!-- Licences etc... -->
                <div class="form-group" id="licence-container" data-latest-index="{{ $membership->licences->count() - 1 }}">
                    @foreach ($membership->licences as $i => $licence)
                        @include('themes.expertij.partials.membership.edit.licence', ['licence' => $licence, 'i' => $i])
                    @endforeach
                </div> <!-- licence container -->
                <div class="text-end mt-3">
                    <button class="btn btn-success form-action-btn" data-form="items" data-type="licence" data-route="createItem" type="button">
                        {{ __('labels.membership.add_licence') }}
                    </button>
                </div>

                <input type="hidden" id="createItem" value="{{ route('memberships.items.create') }}">
                <input type="hidden" id="deleteItem" value="{{ route('memberships.items.delete', 0) }}">
            </div>
            </form>
            <div class="tab-pane" id="professional_information">
                <!-- Professional information -->
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="professional_status">{{ __('labels.membership.professional_status') }}</label>
                        <select name="professional_status" class="form-select" id="professional_status" required>
                            <option value="">{{ __('labels.generic.select_option') }}</option>
                            @foreach ($options['professional_status'] as $option)
                                @php $selected = ($option['value'] == $membership->professional_status) ? 'selected="selected"' : ''; @endphp
                                <option value="{{ $option['value'] }}" {{ $selected }}>{{ $option['text'] }}</option>
                            @endforeach
                        </select>
                        <div class="text-danger" id="professional_statusError"></div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="since">{{ __('labels.generic.since') }}</label>
                        <select name="since" class="form-select" id="since" required>
                            <option value="">{{ __('labels.generic.select_option') }}</option>
                            @foreach ($options['since'] as $option)
                                @php $selected = ($option['value'] == $membership->since) ? 'selected="selected"' : ''; @endphp
                                <option value="{{ $option['value'] }}" {{ $selected }}>{{ $option['text'] }}</option>
                            @endforeach
                        </select>
                        <div class="text-danger" id="sinceError"></div>
                    </div>
                </div>
                <div class="row" id="professional_status_info_row">
                    <div class="col-md-6 form-group">
                        <label for="professional_status_info">{{ __('labels.membership.professional_status_info') }}</label>
                        <input type="text" name="professional_status_info" class="form-control" id="professional_status_info" value="{{ $membership->professional_status_info }}">
                        <div class="text-danger" id="professional_status_infoError"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="siret_number">{{ __('labels.membership.siret_number') }}</label>
                        <input type="text" name="siret_number" class="form-control" id="siret_number" value="{{ $membership->siret_number }}" required>
                        <div class="text-danger" id="siret_numberError"></div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="naf_code">{{ __('labels.membership.naf_code') }}</label>
                        <input type="text" name="naf_code" class="form-control" id="naf_code" value="{{ $membership->naf_code }}" required>
                        <div class="text-danger" id="naf_codeError"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="professional_attestation">{{ __('labels.generic.attestation') }}</label>
                        <input type="file" name="professional_attestation" class="form-control" id="professional_attestation">
                        <div class="text-danger" id="professional_attestationError"></div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>{{ __('labels.generic.attestation') }}</label>
                        <div id="professional_attestation_document">
                            <a class="btn btn-success" href="{{ url('/').$membership->professionalAttestation->getUrl() }}" target="_blank">
                            {{ $membership->professionalAttestation->file_name }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5 d-flex align-items-center justify-content-center">
            <div class="col-md-6 offset-md-4">
                <button class="btn btn-success form-action-btn" data-form="updateMembership" data-route="update" type="button">
                    {{ __('labels.button.update') }}
                </button>
            </div>
        </div>
    </form>
</div>

<form action="#" method="post" id="items" role="form">
    @csrf
    <input type="hidden" name="_method" id="_item_form_method" value="post">
</form>

@push ('scripts')
    <script type="text/javascript" src="{{ asset('/vendor/adminlte/plugins/moment/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/adminlte/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('/vendor/codalia/c.ajax.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/admin/daterangepicker.js') }}"></script>
    <script src="{{ asset('/js/membership/registration.js') }}"></script>
@endpush

