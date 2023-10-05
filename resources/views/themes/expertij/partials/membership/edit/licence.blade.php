<div class="row border border-primary rounded p-3 mt-3" id="licence-{{ $i }}">
    <div class="row">
        <div class="col-md-6 form-group">
            @foreach ($options['licence_type'] as $option)
                @php $checked = ($option['value'] == $licence->type) ? 'checked' : ''; @endphp
                <input class="form-check-input licence-type" type="radio" data-type="{{ $option['value'] }}" data-licence-index="{{ $i }}" name="licences[{{ $i }}][type]" {{ ($option['value'] == 'expert') ? 'checked="checked"' : '' }} id="licences.{{ $i }}.{{ $option['value'] }}" value="{{ $option['value'] }}" {{ $checked }}>
                <label class="form-check-label" for="licences.{{ $i }}.{{ $option['value'] }}">
                  {{ $option['text'] }}
                </label>
            @endforeach
        </div>
        <div class="col-md-6 form-group">
            <label for="licences.{{ $i }}.since">{{ __('labels.generic.since') }}</label>
            <select name="licences[{{ $i }}][since]" class="form-select" id="licences.{{ $i }}.since" required>
                <option value="">{{ __('labels.generic.select_option') }}</option>
                @foreach ($options['since'] as $option)
                    @php $selected = ($option['value'] == $licence->since) ? 'selected="selected"' : ''; @endphp
                    <option value="{{ $option['value'] }}" {{ $selected }}>{{ $option['text'] }}</option>
                @endforeach
            </select>
            <div class="text-danger" id="licences.{{ $i }}.sinceError"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="licences.{{ $i }}.court">{{ __('labels.membership.court') }}</label>
            <select name="licences[{{ $i }}][court]" class="form-select" id="licences.{{ $i }}.court" disabled required>
                <option value="">{{ __('labels.generic.select_option') }}</option>
                @foreach ($options['jurisdictions']['court'] as $option)
                    @php $selected = ($option['value'] == $licence->jurisdiction_id) ? 'selected="selected"' : ''; @endphp
                    <option value="{{ $option['value'] }}" {{ $selected }}>{{ $option['text'] }}</option>
                @endforeach
            </select>
            <div class="text-danger" id="licences.{{ $i }}.courtError"></div>
        </div>
        <div class="col-md-6 form-group">
            <label for="licences.{{ $i }}.appeal_court">{{ __('labels.membership.appeal_court') }}</label>
            <select name="licences[{{ $i }}][appeal_court]" class="form-select" id="licences.{{ $i }}.appeal_court" required>
                <option value="">{{ __('labels.generic.select_option') }}</option>
                @foreach ($options['jurisdictions']['appeal_court'] as $option)
                    @php $selected = ($option['value'] == $licence->jurisdiction_id) ? 'selected="selected"' : ''; @endphp
                    <option value="{{ $option['value'] }}" {{ $selected }}>{{ $option['text'] }}</option>
                @endforeach
            </select>
            <div class="text-danger" id="licences.{{ $i }}.appeal_courtError"></div>
        </div>
    </div>

    <div class="form-group" id="attestation-container-{{ $i }}" data-latest-index="{{ $licence->attestations->count() - 1 }}">
        @foreach ($licence->attestations as $j => $attestation)
            @include('themes.expertij.partials.membership.edit.attestation', ['attestation' => $attestation, 'i' => $i, 'j' => $j])
        @endforeach
    </div> <!-- attestation container -->
    <div class="text-end pe-4">
        <button class="btn btn-success form-action-btn" data-form="items" data-type="attestation" data-licence-index="{{ $i }}" data-route="createItem" type="button">
            {{ __('labels.membership.add_attestation') }}
        </button>
    </div>

    @if ($i > 0)
        <div class="text-end pe-4 mt-3">
            <button class="btn btn-danger form-action-btn" data-form="items" data-type="licence" data-index="{{ $i }}" data-route="deleteItem" type="button">{{ __('labels.membership.delete_licence') }}</button>
        </div>
    @endif
</div>
