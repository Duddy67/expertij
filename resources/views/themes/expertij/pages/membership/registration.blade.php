@push('styles')
    <link rel="stylesheet" href="{{ asset('/vendor/adminlte/plugins/daterangepicker/daterangepicker.css') }}">
@endpush

<div class="position-relative">
    @include('themes.expertij.partials.flash-message')
    <form action="{{ route('memberships.store') }}" method="post" enctype="multipart/form-data" id="registration" role="form" class="php-email-form">
        @csrf
        <input type="hidden" id="store" value="{{ route('memberships.store') }}">
        @method('post')

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
                        <label for="last_name">{{ __('labels.user.last_name') }}</label>
                        <input type="text" name="last_name" class="form-control" id="last_name" required>
                        <div class="text-danger" id="last_nameError"></div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="first_name">{{ __('labels.user.first_name') }}</label>
                        <input type="text" name="first_name" class="form-control" id="first_name" required>
                        <div class="text-danger" id="first_nameError"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="birth_name">{{ __('labels.user.birth_name') }}</label>
                        <input type="text" name="birth_name" class="form-control" id="birth_name" required>
                        <div class="text-danger" id="birth_nameError"></div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="birth_date">{{ __('labels.user.birth_date') }}</label>
                        <input type="text" name="birth_date" class="form-control date" id="birth_date" data-date="0" data-format="D MMM YYYY">
                        <input type="hidden" id="_birth_date" name="_birth_date" value="">
                        <div class="text-danger" id="birth_dateError"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="birth_location">{{ __('labels.user.birth_location') }}</label>
                        <input type="text" name="birth_location" class="form-control" id="birth_location" required>
                        <div class="text-danger" id="birth_locationError"></div>
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
                        <label for="additional_address">{{ __('labels.address.additional_address') }}</label>
                        <input type="text" name="additional_address" class="form-control" id="additional_address">
                        <div class="text-danger" id="additional_addressError"></div>
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
                        <label for="password_confirmation">{{ __('labels.user.confirm_password') }}</label>
                        <input type="password" autocomplete="false" name="password_confirmation" class="form-control" id="password_confirmation" required>
                        <div class="text-danger" id="password_confirmationError"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group mt-3 mt-md-0">
                        <label for="associated_member">{{ __('labels.membership.associated_member') }}</label><br />
                        <input type="checkbox" class="form-check-input" name="associated_member" id="associated_member" />
                        <div class="text-danger" id="associated_memberError"></div>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="licences">
                <!-- Licences etc... -->
                <div class="form-group" id="licence-container">
                    @include('themes.expertij.partials.membership.registration.licence')
                </div> <!-- licence container -->
                <div class="text-center">
                    <button class="btn btn-success form-action-btn" data-form="registration" data-route="addLicence" type="button">{{ __('labels.membership.add_licence') }}</button>
                </div>

                <input type="hidden" id="addLicence" value="{{ route('memberships.addLicence') }}">
                <input type="hidden" id="deleteLicence" value="{{ url('/').'/memberships/delete-licence' }}">
                <input type="hidden" id="addAttestation" value="{{ route('memberships.addAttestation') }}">
            </div>

            <div class="tab-pane" id="professional_status">
                <!-- Professional status -->
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="professional_status">{{ __('labels.membership.professional_status') }}</label>
                        <select name="professional_status" class="form-select" id="professional_status" required>
                            <option value="">{{ __('labels.generic.select_option') }}</option>
                            @foreach ($options['professional_status'] as $option)
                                <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                            @endforeach
                        </select>
                        <div class="text-danger" id="professional_statusError"></div>
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
                        <label for="professional_status_info">{{ __('labels.membership.professional_status_info') }}</label>
                        <input type="text" name="professional_status_info" class="form-control" id="professional_status_info">
                        <div class="text-danger" id="professional_status_infoError"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="siret_number">{{ __('labels.membership.siret_number') }}</label>
                        <input type="text" name="siret_number" class="form-control" id="siret_number" required>
                        <div class="text-danger" id="siret_numberError"></div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="naf_code">{{ __('labels.membership.naf_code') }}</label>
                        <input type="text" name="naf_code" class="form-control" id="naf_code" required>
                        <div class="text-danger" id="nafCodeError"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="professional_attestation">{{ __('labels.generic.attestation') }}</label>
                        <input type="file" name="professional_attestation" class="form-control" id="professional_attestation" required>
                        <div class="text-danger" id="professional_attestationError"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="linguistic_training">{{ __('labels.membership.linguistic_training') }}</label>
                        <textarea name="linguistic_training" rows="5" cols="35" class="form-control" id="linguistic_training" required>
                        </textarea>
                        <div class="text-danger" id="linguistic_trainingError"></div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="extra_linguistic_training">{{ __('labels.membership.extra_linguistic_training') }}</label>
                        <textarea name="extra_linguistic_training" rows="5" cols="35" class="form-control" id="extra_linguistic_training" required>
                        </textarea>
                        <div class="text-danger" id="extra_linguistic_trainingError"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="professional_experience">{{ __('labels.membership.professional_experience') }}</label>
                        <textarea name="professional_experience" rows="5" cols="35" class="form-control" id="professional_experience" required>
                        </textarea>
                        <div class="text-danger" id="professional_experienceError"></div>
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
                        <label for="why_expertij">{{ __('labels.membership.why_expertij') }}</label>
                        <textarea name="why_expertij" rows="5" cols="35" class="form-control" id="why_expertij" required>
                        </textarea>
                        <div class="text-danger" id="why_expertijError"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center"><button class="btn btn-success" id="submit" type="button">{{ __('labels.membership.submit_application') }}</button></div>
    </form>
</div>

<div class="ajax-progress d-none" id="ajax-progress">
    <img src="{{ asset('/images/progress-icon.gif') }}" class="progress-icon" style="top:20%;" />
</div>

@push ('scripts')
    <script type="text/javascript" src="{{ asset('/vendor/adminlte/plugins/moment/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/adminlte/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('/vendor/codalia/c.ajax.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/admin/daterangepicker.js') }}"></script>
    <script src="{{ asset('/js/membership/registration.js') }}"></script>
@endpush

