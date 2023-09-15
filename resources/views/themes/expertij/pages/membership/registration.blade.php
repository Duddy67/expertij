@push('styles')
    <link rel="stylesheet" href="{{ asset('/vendor/adminlte/plugins/daterangepicker/daterangepicker.css') }}">
@endpush

<div class="position-relative">
    @include('themes.expertij.partials.flash-message')
    <form action="{{ route('memberships.store') }}" method="post" enctype="multipart/form-data" id="registration" role="form" class="php-email-form">
        @csrf

        <nav class="nav nav-tabs">
            <a class="nav-item nav-link active" href="#personal_information" data-bs-toggle="tab">{{ __('labels.generic.personal_information') }}</a>
            <a class="nav-item nav-link" href="#licences" data-bs-toggle="tab">{{ __('labels.membership.licences') }}</a>
            <a class="nav-item nav-link" href="#professional_status" data-bs-toggle="tab">{{ __('labels.membership.professional_status') }}</a>
        </nav>

        <div class="tab-content">
            <div class="tab-pane active" id="personal_information">
                <!-- User and membership data -->
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="civility">{{ __('labels.user.civility') }}</label>
                        <select name="civility" class="form-select" id="civility" required>
                            <option value="">{{ __('labels.generic.select_option') }}</option>
                            @foreach ($options['civility'] as $option)
                                <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                            @endforeach
                        </select>
                        <div class="text-danger" id="civilityError"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="lastName">{{ __('labels.user.last_name') }}</label>
                        <input type="text" name="last_name" class="form-control" id="lastName" required>
                        <div class="text-danger" id="lastNameError"></div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="firstName">{{ __('labels.user.first_name') }}</label>
                        <input type="text" name="first_name" class="form-control" id="firstName" required>
                        <div class="text-danger" id="firstNameError"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="birthName">{{ __('labels.user.birth_name') }}</label>
                        <input type="text" name="birth_name" class="form-control" id="birthName" required>
                        <div class="text-danger" id="birthNameError"></div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="birthDate">{{ __('labels.user.birth_date') }}</label>
                        <input type="text" name="birth_date" class="form-control date" id="birthDate" data-date="0" data-format="D MMM YYYY">
                        <input type="hidden" id="_birthDate" name="_birth_date" value="">
                        <div class="text-danger" id="birthDateError"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="birthLocation">{{ __('labels.user.birth_location') }}</label>
                        <input type="text" name="birth_location" class="form-control" id="birthLocation" required>
                        <div class="text-danger" id="birthLocationError"></div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="citizenship">{{ __('labels.user.citizenship') }}</label>
                        <select name="citizenship" class="form-select" id="citizenship" required>
                            <option value="">{{ __('labels.generic.select_option') }}</option>
                            @foreach ($options['citizenship'] as $option)
                                <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                            @endforeach
                        </select>
                        <div class="text-danger" id="citizenshipError"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="street">{{ __('labels.address.street') }}</label>
                        <input type="text" name="street" class="form-control" id="street" required>
                        <div class="text-danger" id="streetError"></div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="additionalAddress">{{ __('labels.address.additional_address') }}</label>
                        <input type="text" name="additional_address" class="form-control" id="additionalAddress">
                        <div class="text-danger" id="additionalAddressError"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="postcode">{{ __('labels.address.postcode') }}</label>
                        <input type="text" name="postcode" class="form-control" id="postcode" required>
                        <div class="text-danger" id="postcodeError"></div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="city">{{ __('labels.address.city') }}</label>
                        <input type="text" name="city" class="form-control" id="city" required>
                        <div class="text-danger" id="cityError"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="phone">{{ __('labels.address.phone') }}</label>
                        <input type="text" name="phone" class="form-control" id="phone" required>
                        <div class="text-danger" id="phoneError"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group mt-3 mt-md-0">
                        <label for="email">{{ __('labels.user.email') }}</label>
                        <input type="email" class="form-control" name="email" id="email" required>
                        <div class="text-danger" id="emailError"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="password">{{ __('labels.user.password') }}</label>
                        <input type="password" autocomplete="false" name="password" class="form-control" id="password" required>
                        <div class="text-danger" id="passwordError"></div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="passwordConfirmation">{{ __('labels.user.confirm_password') }}</label>
                        <input type="password" autocomplete="false" name="password_confirmation" class="form-control" id="passwordConfirmation" required>
                        <div class="text-danger" id="passwordConfirmationError"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group mt-3 mt-md-0">
                        <label for="associatedMember">{{ __('labels.membership.associated_member') }}</label><br />
                        <input type="checkbox" class="form-check-input" name="associated_member" id="associatedMember" />
                        <div class="text-danger" id="emailError"></div>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="licences">
            <!-- Licences etc... -->
            </div>

            <div class="tab-pane" id="professional_status">
                <!-- Professional status -->
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="professionalStatus">{{ __('labels.membership.professional_status') }}</label>
                        <select name="professional_status" class="form-select" id="professionalStatus" required>
                            <option value="">{{ __('labels.generic.select_option') }}</option>
                            @foreach ($options['professional_status'] as $option)
                                <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                            @endforeach
                        </select>
                        <div class="text-danger" id="professionalStatusError"></div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="since">{{ __('labels.generic.since') }}</label>
                        <select name="since" class="form-select" id="since" required>
                            <option value="">{{ __('labels.generic.select_option') }}</option>
                            @foreach ($options['since'] as $option)
                                <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                            @endforeach
                        </select>
                        <div class="text-danger" id="sinceError"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="professionalStatusInfo">{{ __('labels.membership.professional_status_info') }}</label>
                        <input type="text" name="professional_status_info" class="form-control" id="professionalStatusInfo">
                        <div class="text-danger" id="professionalStatusInfoError"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="siretNumber">{{ __('labels.membership.siret_number') }}</label>
                        <input type="text" name="siret_number" class="form-control" id="siretNumber" required>
                        <div class="text-danger" id="siretNumberError"></div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="nafCode">{{ __('labels.membership.naf_code') }}</label>
                        <input type="text" name="naf_code" class="form-control" id="nafCode" required>
                        <div class="text-danger" id="nafCodeError"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="professionalAttestation">{{ __('labels.generic.attestation') }}</label>
                        <input type="file" name="professional_attestation" class="form-control" id="professionalAttestation" required>
                        <div class="text-danger" id="professionalAttestationError"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="linguisticTraining">{{ __('labels.membership.linguistic_training') }}</label>
                        <textarea name="linguistic_training" rows="5" cols="35" class="form-control" id="linguisticTraining" required>
                        </textarea>
                        <div class="text-danger" id="linguisticTrainingError"></div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="extraLinguisticTraining">{{ __('labels.membership.extra_linguistic_training') }}</label>
                        <textarea name="extra_linguistic_training" rows="5" cols="35" class="form-control" id="extraLinguisticTraining" required>
                        </textarea>
                        <div class="text-danger" id="extraLinguisticTrainingError"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="professionalExperience">{{ __('labels.membership.professional_experience') }}</label>
                        <textarea name="professional_experience" rows="5" cols="35" class="form-control" id="professionalExperience" required>
                        </textarea>
                        <div class="text-danger" id="professionalExperienceError"></div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="observations">{{ __('labels.membership.observations') }}</label>
                        <textarea name="observations" rows="5" cols="35" class="form-control" id="observations">
                        </textarea>
                        <div class="text-danger" id="observationsError"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="whyExpertij">{{ __('labels.membership.why_expertij') }}</label>
                        <textarea name="why_expertij" rows="5" cols="35" class="form-control" id="whyExpertij" required>
                        </textarea>
                        <div class="text-danger" id="whyExpertijError"></div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@push ('scripts')
    <script type="text/javascript" src="{{ asset('/vendor/adminlte/plugins/moment/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/adminlte/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('/vendor/codalia/c.ajax.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/admin/daterangepicker.js') }}"></script>
    <script src="{{ asset('/js/membership/registration.js') }}"></script>
@endpush

