<div class="row licence-row" id="licence-{{ $i }}">
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="licence_type_{{ $i }}">{{ __('labels.generic.type') }}</label>
            <select name="licences[{{ $i }}][type]" class="form-select" id="licence_type_{{ $i }}">
                @foreach ($options['licence_type'] as $option)
                    <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 form-group">
            <label for="since_{{ $i }}">{{ __('labels.generic.since') }}</label>
            <select name="licences[{{ $i }}][since]" class="form-select" id="since_{{ $i }}" required>
                @foreach ($options['since'] as $option)
                    <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                @endforeach
            </select>
            <div class="text-danger" id="since_{{ $i }}Error"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="court_{{ $i }}">{{ __('labels.membership.court') }}</label>
            <select name="licences[{{ $i }}][court]" class="form-select" id="court_{{ $i }}" required>
                <option value="">{{ __('labels.generic.select_option') }}</option>
                @foreach ($options['jurisdictions']['court'] as $option)
                    <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                @endforeach
            </select>
            <div class="text-danger" id="court_{{ $i }}Error"></div>
        </div>
        <div class="col-md-6 form-group">
            <label for="appeal_court_{{ $i }}">{{ __('labels.membership.appeal_court') }}</label>
            <select name="licences[{{ $i }}][appeal_court]" class="form-select" id="appeal_court_{{ $i }}" required>
                <option value="">{{ __('labels.generic.select_option') }}</option>
                @foreach ($options['jurisdictions']['appeal_court'] as $option)
                    <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                @endforeach
            </select>
            <div class="text-danger" id="appeal_court_{{ $i }}Error"></div>
        </div>
    </div>
</div>