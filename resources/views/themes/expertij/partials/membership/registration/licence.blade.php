<div class="row border border-primary rounded p-3 mt-3" id="licence-{{ $i }}">
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
            <select name="licences[{{ $i }}][since]" class="form-select" id="licences.{{ $i }}.since" required>
                <option value="">{{ __('labels.generic.select_option') }}</option>
                @foreach ($options['since'] as $option)
                    <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                @endforeach
            </select>
            <div class="text-danger" id="licences.{{ $i }}.sinceError"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="court_{{ $i }}">{{ __('labels.membership.court') }}</label>
            <select name="licences[{{ $i }}][court]" class="form-select" id="licences.{{ $i }}.court_{{ $i }}" required>
                <option value="">{{ __('labels.generic.select_option') }}</option>
                @foreach ($options['jurisdictions']['court'] as $option)
                    <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                @endforeach
            </select>
            <div class="text-danger" id="licences.{{ $i }}.courtError"></div>
        </div>
        <div class="col-md-6 form-group">
            <label for="appeal_court_{{ $i }}">{{ __('labels.membership.appeal_court') }}</label>
            <select name="licences[{{ $i }}][appeal_court]" class="form-select" id="licences.{{ $i }}.appeal_court" required>
                <option value="">{{ __('labels.generic.select_option') }}</option>
                @foreach ($options['jurisdictions']['appeal_court'] as $option)
                    <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                @endforeach
            </select>
            <div class="text-danger" id="licences.{{ $i }}.appeal_courtError"></div>
        </div>
    </div>

    <div class="form-group" id="attestation-container-{{ $i }}">
        @include('themes.expertij.partials.membership.registration.attestation')
    </div> <!-- attestation container -->
    <div class="text-end pe-4">
        <button class="btn btn-success form-action-btn" data-form="items" data-type="attestation" data-licence-index="{{ $i }}" data-route="addItem" type="button">
            {{ __('labels.membership.add_attestation') }}
        </button>
    </div>

    @if ($i > 0)
        <div class="text-end pe-4 mt-3">
            <button class="btn btn-danger form-action-btn" data-form="items" data-type="licence" data-index="{{ $i }}" data-route="deleteItem" type="button">{{ __('labels.membership.delete_licence') }}</button>
        </div>
    @endif
</div>
