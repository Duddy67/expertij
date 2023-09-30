
<div class="position-relative">
    @include('themes.expertij.partials.flash-message')
    <form action="#" method="post" enctype="multipart/form-data" id="registration" role="form" class="php-email-form">
        @csrf

        <div class="row mb-2 d-flex align-items-center justify-content-center">
            <div class="col-md-6">
                <label for="last_name">{{ __('labels.user.last_name') }}</label>
                <input type="text" name="last_name" class="form-control" id="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                <div class="text-danger" id="last_nameError"></div>
            </div>
        </div>
        <div class="row mb-2 d-flex align-items-center justify-content-center">
            <div class="col-md-6">
                <label for="first_name">{{ __('labels.user.first_name') }}</label>
                <input type="text" name="first_name" class="form-control" id="first_name" value="{{ old('last_name', $user->first_name) }}" required>
                <div class="text-danger" id="first_nameError"></div>
            </div>
        </div>
        <div class="row mb-2 d-flex align-items-center justify-content-center">
            <div class="col-md-6 mt-3 mt-md-0">
                <label for="email">{{ __('labels.user.email') }}</label>
                <input type="email" class="form-control" name="email" id="email" value="{{ old('last_name', $user->email) }}" required>
                <div class="text-danger" id="emailError"></div>
            </div>
        </div>
        <div class="row mb-2 d-flex align-items-center justify-content-center">
            <div class="col-md-6">
                <label for="password">{{ __('labels.user.password') }}</label>
                <input type="password" autocomplete="false" name="password" class="form-control" id="password" required>
                <div class="text-danger" id="passwordError"></div>
            </div>
        </div>
        <div class="row mb-2 d-flex align-items-center justify-content-center">
            <div class="col-md-6">
                <label for="password_confirmation">{{ __('labels.user.confirm_password') }}</label>
                <input type="password" autocomplete="false" name="password_confirmation" class="form-control" id="password_confirmation" required>
                <div class="text-danger" id="password_confirmationError"></div>
            </div>
        </div>

        @if ($user->civility)
            <div class="row mb-2 d-flex align-items-center justify-content-center">
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


        <div class="row mb-0">
            <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">
                    {{ __('Register') }}
                </button>
            </div>
        </div>
    </form>
</div>
