<div class="row border border-warning rounded p-3 m-3" id="skill-{{ $i }}-{{ $j }}-{{ $k }}">
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="licences.{{ $i }}.attestations.{{ $j }}.skills.{{ $k }}.alpha_3">{{ __('labels.membership.language') }}</label>
            <select name="licences[{{ $i }}][attestations][{{ $j }}][skills][{{ $k }}][alpha_3]" class="form-select" id="licences.{{ $i }}.attestations.{{ $j }}.skills.{{ $k }}.alpha_3" disabled>
                <option value="">{{ __('labels.generic.select_option') }}</option>
                @foreach ($options['language'] as $option)
                    @php $selected = ($option['value'] == $skill->language_id) ? 'selected="selected"' : ''; @endphp
                    <option value="{{ $option['value'] }}" {{ $selected }}>{{ $option['text'] }}</option>
                @endforeach
            </select>
            <div class="text-danger" id="licences.{{ $i }}.attestations.{{ $j }}.skills.{{ $k }}.alpha_3Error"></div>
        </div>
        <div class="col-md-6 form-group">
            <div class="mt-3">
                <div class="form-check form-check-inline">
                    @php $checked = ($skill->interpreter) ? 'checked' : ''; @endphp
                    <input class="form-check-input language-skill" type="checkbox" data-type="interpreter" data-licence-index="{{ $i }}" data-attestation-index="{{ $j }}" data-skill-index="{{ $k }}" name="licences[{{ $i }}][attestations][{{ $j }}][skills][{{ $k }}][interpreter]" id="licences.{{ $i }}.attestations.{{ $j }}.skills.{{ $k }}.interpreter" {{ $checked }} disabled>
                    <label class="form-check-label" for="licences.{{ $i }}.attestations.{{ $j }}.skills.{{ $k }}.interpreter">{{ __('labels.membership.interpreter') }}</label>
                </div>
                <div class="form-check form-check-inline">
                    @php $checked = ($skill->interpreter_cassation) ? 'checked' : ''; @endphp
                    <input class="form-check-input" type="checkbox" name="licences[{{ $i }}][attestations][{{ $j }}][skills][{{ $k }}][interpreter_cassation]" id="licences.{{ $i }}.attestations.{{ $j }}.skills.{{ $k }}.interpreter_cassation" {{ $checked }} disabled>
                    <label class="form-check-label" for="licences.{{ $i }}.attestations.{{ $j }}.skills.{{ $k }}.interpreter_cassation">{{ __('labels.membership.cassation') }}</label>
                </div>
                <div class="text-danger" id="licences.{{ $i }}.attestations.{{ $j }}.skills.{{ $k }}.interpreterError"></div>
            </div>
            <div class="">
                <div class="form-check form-check-inline">
                    @php $checked = ($skill->translator) ? 'checked' : ''; @endphp
                    <input class="form-check-input language-skill" type="checkbox" data-type="translator" data-licence-index="{{ $i }}" data-attestation-index="{{ $j }}" data-skill-index="{{ $k }}" name="licences[{{ $i }}][attestations][{{ $j }}][skills][{{ $k }}][translator]" id="licences.{{ $i }}.attestations.{{ $j }}.skills.{{ $k }}.translator" {{ $checked }} disabled>
                    <label class="form-check-label" for="licences.{{ $i }}.attestations.{{ $j }}.skills.{{ $k }}.translator">{{ __('labels.membership.translator') }}</label>
                    <div class="text-danger" id="licences.{{ $i }}.attestations.{{ $j }}.skills.{{ $k }}.translatorError"></div>
                </div>
                <div class="form-check form-check-inline">
                    @php $checked = ($skill->translator_cassation) ? 'checked' : ''; @endphp
                    <input class="form-check-input" type="checkbox" name="licences[{{ $i }}][attestations][{{ $j }}][skills][{{ $k }}][translator_cassation]" id="licences.{{ $i }}.attestations.{{ $j }}.skills.{{ $k }}.translator_cassation" {{ $checked }} disabled>
                    <label class="form-check-label" for="licences.{{ $i }}.attestations.{{ $j }}.skills.{{ $k }}.translator_cassation">{{ __('labels.membership.cassation') }}</label>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="licences[{{ $i }}][attestations][{{ $j }}][skills][{{ $k }}][_id]" value="{{ $skill->id }}">
</div>
