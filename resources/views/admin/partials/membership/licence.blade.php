<div class="row border border-primary rounded p-3 mt-3" id="licence-{{ $i }}">
    <div class="row">
        @foreach ($options['licence_type'] as $option)
            @php $checked = ($option['value'] == $licence->type) ? 'checked' : ''; @endphp
            <div class="col-md-6 form-check">
                <input class="form-check-input licence-type" type="radio" data-type="{{ $option['value'] }}" data-licence-index="{{ $i }}" name="licences[{{ $i }}][type]" {{ ($option['value'] == 'expert') ? 'checked="checked"' : '' }} id="licences.{{ $i }}.{{ $option['value'] }}" value="{{ $option['value'] }}" {{ $checked }} disabled>
                <label class="form-check-label" for="licences.{{ $i }}.{{ $option['value'] }}">
                  {{ $option['text'] }}
                </label>
            </div>
        @endforeach
    </div>
    <div class="row w-100">
        <div class="col-md-6">
            <label for="licences.{{ $i }}.since">{{ __('labels.generic.since') }}</label>
            <select name="licences[{{ $i }}][since]" class="form-control select2" id="licences.{{ $i }}.since" disabled>
                <option value="">{{ __('labels.generic.select_option') }}</option>
                @foreach ($options['since'] as $option)
                    @php $selected = ($option['value'] == $licence->since) ? 'selected="selected"' : ''; @endphp
                    <option value="{{ $option['value'] }}" {{ $selected }}>{{ $option['text'] }}</option>
                @endforeach
            </select>
        </div>
        @if ($licence->type == 'ceseda')
            <div class="col-md-6 form-group">
                <label for="licences.{{ $i }}.court">{{ __('labels.membership.court') }}</label>
                <select name="licences[{{ $i }}][court]" class="form-control form-select" id="licences.{{ $i }}.court" disabled>
                    <option value="">{{ __('labels.generic.select_option') }}</option>
                    @foreach ($options['jurisdictions']['court'] as $option)
                        @php $selected = ($option['value'] == $licence->jurisdiction_id) ? 'selected="selected"' : ''; @endphp
                        <option value="{{ $option['value'] }}" {{ $selected }}>{{ $option['text'] }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        @if ($licence->type == 'expert')
            <div class="col-md-6 form-group">
                <label for="licences.{{ $i }}.appeal_court">{{ __('labels.membership.appeal_court') }}</label>
                <select name="licences[{{ $i }}][appeal_court]" class="form-control form-select" id="licences.{{ $i }}.appeal_court" disabled>
                    <option value="">{{ __('labels.generic.select_option') }}</option>
                    @foreach ($options['jurisdictions']['appeal_court'] as $option)
                        @php $selected = ($option['value'] == $licence->jurisdiction_id) ? 'selected="selected"' : ''; @endphp
                        <option value="{{ $option['value'] }}" {{ $selected }}>{{ $option['text'] }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>

    <input type="hidden" name="licences[{{ $i }}][_id]" value="{{ $licence->id }}">

    <div class="form-group" id="attestation-container-{{ $i }}" data-latest-index="{{ $licence->attestations->count() - 1 }}">
        @foreach ($licence->attestations as $j => $attestation)
            @include('admin.partials.membership.attestation', ['attestation' => $attestation, 'i' => $i, 'j' => $j])
        @endforeach
    </div> <!-- attestation container -->
</div>
