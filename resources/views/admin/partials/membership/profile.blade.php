
<div class="row">
    <div class="col-6 m-3">
        @php $path = ($user->photo) ?  url('/').$user->photo->getThumbnailUrl() : asset('/images/user.png'); @endphp
        <img src="{{ $path }}" id="user-photo" class="border border-dark" />
    </div>
</div>
<div class="row">
    <div class="col-6">
        <label for="civility">{{ __('labels.user.civility') }}</label>
        <select name="civility" class="form-control cselect" id="civility" disabled>
            <option value="">{{ __('labels.generic.select_option') }}</option>
            @foreach ($options['civility'] as $option)
                @php $selected = ($user->civility == $option['value']) ? 'selected="selected"' : ''; @endphp
                <option value="{{ $option['value'] }}" {{ $selected }}>{{ $option['text'] }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="row">
    <div class="col">
        <label for="last_name">{{ __('labels.user.last_name') }}</label>
        <input id="last_name" disabled="" type="text" class="form-control " name="last_name" value="{{ $user->last_name }}" readonly="">
    </div>
    <div class="col">
        <label for="first_name">{{ __('labels.user.first_name') }}</label>
        <input id="first_name" disabled="" type="text" class="form-control " name="first_name" value="{{ $user->first_name }}" readonly="">
    </div>
</div>
<div class="row">
    <div class="col">
        <label for="birth_name">{{ __('labels.user.birth_name') }}</label>
        <input id="birth_name" disabled="" type="text" class="form-control " name="birth_name" value="{{ $user->birth_name }}" readonly="">
    </div>
    <div class="col">
        <label for="birth_date">{{ __('labels.user.birth_date') }}</label>
        <input type="text" name="birth_date" class="form-control date" id="birth_date" data-date="{{ $user->birth_date->toDateString() }}" data-format="d M Y" disabled>
        <input type="hidden" id="_birth_date" name="_birth_date" value="{{ old('birth_date', $user->birth_date->toDateString()) }}">
    </div>
</div>
<div class="row">
    <div class="col">
        <label for="birth_location">{{ __('labels.user.birth_location') }}</label>
        <input id="birth_location" disabled="" type="text" class="form-control " name="birth_location" value="{{ $user->birth_location}}" readonly="">
    </div>
    <div class="col">
        <label for="citizenship">{{ __('labels.user.citizenship') }}</label>
        <select name="citizenship" class="form-control cselect" id="citizenship" disabled>
            <option value="">{{ __('labels.generic.select_option') }}</option>
            @foreach ($options['citizenship'] as $option)
                @php $selected = ($user->citizenship_id == $option['value']) ? 'selected="selected"' : ''; @endphp
                <option value="{{ $option['value'] }}" {{ $selected }}>{{ $option['text'] }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="row">
    <div class="col">
        <label for="street">{{ __('labels.address.street') }}</label>
        <input id="street" disabled="" type="text" class="form-control " name="street" value="{{ $user->address->street }}" readonly="">
    </div>
    <div class="col">
        <label for="additional_address">{{ __('labels.address.additional_address') }}</label>
        <input id="additional_address" disabled="" type="text" class="form-control " name="additional_address" value="{{ $user->address->additional_address }}" readonly="">
    </div>
</div>
<div class="row">
    <div class="col">
        <label for="postcode">{{ __('labels.address.postcode') }}</label>
        <input id="postcode" disabled="" type="text" class="form-control " name="postcode" value="{{ $user->address->postcode }}" readonly="">
    </div>
    <div class="col">
        <label for="city">{{ __('labels.address.city') }}</label>
        <input id="city" disabled="" type="text" class="form-control " name="city" value="{{ $user->address->city }}" readonly="">
    </div>
</div>
<div class="row">
    <div class="col">
        <label for="phone">{{ __('labels.address.phone') }}</label>
        <input id="phone" disabled="" type="text" class="form-control " name="phone" value="{{ $user->address->phone }}" readonly="">
    </div>
    <div class="col">
        <label for="email">{{ __('labels.user.email') }}</label>
        <input id="email" disabled="" type="email" class="form-control " name="email" value="{{ $user->email }}" readonly="">
    </div>
</div>
