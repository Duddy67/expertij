@push('styles')
    <link rel="stylesheet" href="{{ asset('/vendor/codalia/css/c.datepicker.css') }}">
@endpush

<div class="position-relative">
    @include('themes.basic.partials.flash-message')
    <form action="#" method="post" enctype="multipart/form-data" id="updateProfile" role="form" class="php-email-form">
        @csrf
        <input type="hidden" id="update" value="{{ route('profile.update') }}">
        @method('put')

        @if ($user->membership()->exists())
            <div class="row mb-2">
                <div class="col-md-6">
                    <label for="civility">{{ __('labels.user.civility') }}</label>
                    <select name="civility" class="form-select" id="civility" required>
                        <option value="">{{ __('labels.generic.select_option') }}</option>
                        @foreach ($options['civility'] as $option)
                            @php $selected = ($user->civility == $option['value']) ? 'selected="selected"' : ''; @endphp
                            <option value="{{ $option['value'] }}" {{ $selected }}>{{ $option['text'] }}</option>
                        @endforeach
                    </select>
                    <div class="text-danger" id="civilityError"></div>
                </div>
            </div>
        @endif

        <div class="row mb-2 d-flex align-items-center justify-content-center">
            <div class="col-md-6">
                <label for="last_name">{{ __('labels.user.last_name') }}</label>
                <input type="text" name="last_name" class="form-control" id="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                <div class="text-danger" id="last_nameError"></div>
            </div>
            <div class="col-md-6">
                <label for="first_name">{{ __('labels.user.first_name') }}</label>
                <input type="text" name="first_name" class="form-control" id="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                <div class="text-danger" id="first_nameError"></div>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-6 mt-3 mt-md-0">
                <label for="email">{{ __('labels.user.email') }}</label>
                <input type="email" class="form-control" name="email" id="email" value="{{ old('email', $user->email) }}" required>
                <div class="text-danger" id="emailError"></div>
            </div>
        </div>
        <div class="row mb-2 d-flex align-items-center justify-content-center">
            <div class="col-md-6">
                <label for="password">{{ __('labels.user.password') }}</label>
                <input type="password" autocomplete="false" name="password" class="form-control" id="password" required>
                <div class="text-danger" id="passwordError"></div>
            </div>
            <div class="col-md-6">
                <label for="password_confirmation">{{ __('labels.user.confirm_password') }}</label>
                <input type="password" autocomplete="false" name="password_confirmation" class="form-control" id="password_confirmation" required>
                <div class="text-danger" id="password_confirmationError"></div>
            </div>
        </div>

        @if ($user->membership()->exists())
            <div class="row mb-2 d-flex align-items-center justify-content-center">
                <div class="col-md-6">
                    <label for="birth_name">{{ __('labels.user.birth_name') }}</label>
                    <input type="text" name="birth_name" class="form-control" id="birth_name" value="{{ old('birth_name', $user->birth_name) }}" required>
                    <div class="text-danger" id="birth_nameError"></div>
                </div>
                <div class="col-md-6">
                    <label for="birth_date">{{ __('labels.user.birth_date') }}</label>
                    <input type="text" name="birth_date" class="form-control date" id="birth_date" data-date="{{ $user->birth_date->toDateString() }}" data-format="d M Y" readonly>
                    <input type="hidden" id="_birth_date" name="_birth_date" value="{{ old('birth_date', $user->birth_date->toDateString()) }}">
                    <div class="text-danger" id="birth_dateError"></div>
                </div>
            </div>
            <div class="row mb-2 d-flex align-items-center justify-content-center">
                <div class="col-md-6">
                    <label for="birth_location">{{ __('labels.user.birth_location') }}</label>
                    <input type="text" name="birth_location" class="form-control" id="birth_location" value="{{ old('birth_location', $user->birth_location) }}" required>
                    <div class="text-danger" id="birth_locationError"></div>
                </div>
                <div class="col-md-6">
                    <label for="citizenship">{{ __('labels.user.citizenship') }}</label>
                    <select name="citizenship" class="form-select" id="citizenship" required>
                        <option value="">{{ __('labels.generic.select_option') }}</option>
                        @foreach ($options['citizenship'] as $option)
                            @php $selected = ($user->citizenship_id == $option['value']) ? 'selected="selected"' : ''; @endphp
                            <option value="{{ $option['value'] }}" {{ $selected }}>{{ $option['text'] }}</option>
                        @endforeach
                    </select>
                    <div class="text-danger" id="citizenshipError"></div>
                </div>
            </div>
            <div class="row mb-2 d-flex align-items-center justify-content-center">
                <div class="col-md-6">
                    <label for="street">{{ __('labels.address.street') }}</label>
                    <input type="text" name="street" class="form-control" id="street" value="{{ old('street', $user->address->street) }}" required>
                    <div class="text-danger" id="streetError"></div>
                </div>
                <div class="col-md-6">
                    <label for="additional_address">{{ __('labels.address.additional_address') }}</label>
                    <input type="text" name="additional_address" class="form-control" id="additional_address" value="{{ old('additional_address', $user->address->additional_address) }}">
                </div>
            </div>
            <div class="row mb-2 d-flex align-items-center justify-content-center">
                <div class="col-md-6">
                    <label for="postcode">{{ __('labels.address.postcode') }}</label>
                    <input type="text" name="postcode" class="form-control" id="postcode" value="{{ old('postcode', $user->address->postcode) }}" required>
                    <div class="text-danger" id="postcodeError"></div>
                </div>
                <div class="col-md-6">
                    <label for="city">{{ __('labels.address.city') }}</label>
                    <input type="text" name="city" class="form-control" id="city" value="{{ old('city', $user->address->city) }}" required>
                    <div class="text-danger" id="cityError"></div>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6">
                    <label for="phone">{{ __('labels.address.phone') }}</label>
                    <input type="text" name="phone" class="form-control" id="phone" value="{{ old('phone', $user->address->phone) }}" required>
                    <div class="text-danger" id="phoneError"></div>
                </div>
            </div>
        @endif

        <div class="row mb-2 mt-4">
            <div class="col-md-6">
                @php $path = (isset($user) && $user->photo) ?  url('/').$user->photo->getThumbnailUrl() : asset('/images/user.png'); @endphp
                <img src="{{ $path }}" id="user-photo" />
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-6">
                <label for="photo">{{ __('labels.generic.photo') }}</label>
                <input id="photo" type="file" class="form-control " name="photo">
            </div>
        </div>

        <div class="row mt-5 d-flex align-items-center justify-content-center">
            <div class="col-md-6 offset-md-4">
                <button class="btn btn-success form-action-btn" data-form="updateProfile" data-route="update" type="button">
                    {{ __('labels.button.update') }}
                </button>
            </div>
        </div>
        <input type="hidden" id="_locale" value="{{ config('app.locale') }}">
    </form>
</div>
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

