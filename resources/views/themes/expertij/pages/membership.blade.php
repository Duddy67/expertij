@push('styles')
    <link rel="stylesheet" href="{{ asset('/vendor/codalia/css/c.datepicker.css') }}">
@endpush

<div class="position-relative">
    @include('themes.expertij.partials.flash-message')
    <nav class="nav nav-tabs">
        <a class="nav-item nav-link active" id="membership-tab" href="#membership" data-bs-toggle="tab">{{ __('labels.title.membership') }}</a>
        <a class="nav-item nav-link" id="licences-tab" href="#licences" data-bs-toggle="tab">{{ __('labels.membership.licences') }}</a>
        <a class="nav-item nav-link" id="professional_information-tab" href="#professional_information" data-bs-toggle="tab">{{ __('labels.membership.professional_status') }}</a>
        @if ((in_array($membership->status, ['pending_subscription', 'pending_renewal']) && !$membership->hasPendingPayment()) || ($membership->status == 'member' && !$membership->hasInsurance()))
            @php $label = ($membership->status == 'member' && !$membership->hasInsurance()) ? 'insurances' : 'payment'; @endphp
            <a class="nav-item nav-link" id="payment-tab" href="#payment" data-bs-toggle="tab">{{ __('labels.generic.'.$label) }}</a>
        @endif

        @if (!empty($invoices) || !empty($documents))
            <a class="nav-item nav-link" id="documents-tab" href="#documents" data-bs-toggle="tab">{{ __('labels.title.documents') }}</a>
        @endif
    </nav>

    <div class="tab-content">
        <div class="tab-pane active" id="membership">
            <div class="col-md-6 form-group">
                <label for="status">{{ __('labels.generic.status') }}</label>
                <input type="text" name="_status" class="form-control" id="status" value="{{ __('labels.membership.'.$membership->status) }}" disabled>
            </div>

            @if ($membership->status == 'member' && $membership->hasInsurance())
                <div class="col-md-6 form-group">
                    <label for="status">{{ __('labels.membership.insurance') }}</label>
                    <input type="text" name="_insurance" class="form-control" id="insurance" value="{{ __('labels.membership.insurance_'.$membership->insurance_code) }}" disabled>
                </div>
            @endif
        </div>

        <div class="tab-pane" id="licences">
            <!-- Licences etc... -->
            <form action="#" method="post" enctype="multipart/form-data" id="licenceForm" role="form" class="php-email-form">
                @csrf
                <input type="hidden" id="updateLicences" value="{{ route('memberships.licences.update') }}">
                @method('put')

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
            </form>
            <div class="row mt-5 d-flex align-items-center justify-content-center">
                <div class="col-md-6 offset-md-4">
                    <button class="btn btn-success form-action-btn" data-form="licenceForm" data-route="updateLicences" type="button">
                        {{ __('labels.membership.update_licences') }}
                    </button>
                </div>
            </div>
        </div>

        <div class="tab-pane" id="professional_information">
            <!-- Professional information -->
            <form action="#" method="post" id="membershipForm" role="form" class="php-email-form">
                @csrf
                <input type="hidden" id="update" value="{{ route('memberships.update') }}">
                @method('put')

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
                        <label for="resume">{{ __('labels.generic.resume') }}</label>
                        <input type="file" name="resume" class="form-control" id="resume">
                        <div class="text-danger" id="resumeError"></div>
                    </div>
                </div>

                @if (!$membership->associated_member)
                    <div class="row">
                        <div class="col-md-6 form-group" id="attestation-file-button">
                        @if ($membership->professionalAttestation)
                            @include('themes.expertij.partials.membership.edit.document-file-button', ['fileUrl' => $membership->professionalAttestation->getUrl(), 'fileName' => $membership->professionalAttestation->file_name])
                        @else
                            @include('themes.expertij.partials.membership.edit.missing-document-button')
                        @endif
                        </div>
                        <div class="col-md-6 form-group" id="resume-file-button">
                        @if ($membership->resume)
                            @include('themes.expertij.partials.membership.edit.document-file-button', ['fileUrl' => $membership->resume->getUrl(), 'fileName' => $membership->resume->file_name])
                        @else
                            @include('themes.expertij.partials.membership.edit.missing-document-button')
                        @endif
                        </div>
                    </div>
                @endif

                <input type="hidden" id="_locale" value="{{ config('app.locale') }}">
                <input type="hidden" id="_isAssociatedMember" value="{{ $membership->associated_member }}">
            </form>
            <div class="row mt-5 d-flex align-items-center justify-content-center">
                <div class="col-md-6 offset-md-4">
                    <button class="btn btn-success form-action-btn" data-form="membershipForm" data-route="update" type="button">
                        {{ __('labels.membership.update_professional_status') }}
                    </button>
                </div>
            </div>
        </div>

        @if ((in_array($membership->status, ['pending_subscription', 'pending_renewal']) && !$membership->hasPendingPayment()) || ($membership->status == 'member' && !$membership->hasInsurance()))
            <div class="tab-pane" id="payment">
                @include('themes.expertij.partials.membership.edit.payment')
            </div>
        @endif

        @if (!empty($invoices))
            <div class="tab-pane" id="documents">
                @include('themes.expertij.partials.membership.edit.document')
            </div>
        @endif
    </div>
</div>

<form action="#" method="post" id="items" role="form">
    @csrf
    <input type="hidden" name="_method" id="_item_form_method" value="post">
</form>
<x-js-messages />

@push ('scripts')
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/localeData.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/locale/{{ config('app.locale') }}.js"></script>
    <script type="text/javascript" src="{{ asset('/vendor/codalia/lang/'.config('app.locale').'.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/codalia/c.datepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/datepicker.js') }}"></script>
    <script src="{{ asset('/vendor/codalia/c.ajax.js') }}"></script>
    <script src="{{ asset('/js/membership.js') }}"></script>
@endpush

