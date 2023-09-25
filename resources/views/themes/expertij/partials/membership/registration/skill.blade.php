<div class="row border border-warning rounded p-3 m-3" id="skill-{{ $i }}-{{ $j }}-{{ $k }}">
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="language_{{ $i }}-{{ $j }}-{{ $k }}">{{ __('labels.membership.language') }}</label>
            <select name="licences[{{ $i }}][attestations][{{ $j }}][skills][{{ $k }}][alpha_3]" class="form-select" id="licences.{{ $i }}.attestations.{{ $j }}.skills.{{ $k }}.alpha_3" required>
                <option value="">{{ __('labels.generic.select_option') }}</option>
                @foreach ($options['language'] as $option)
                    <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                @endforeach
            </select>
            <div class="text-danger" id="licences.{{ $i }}.attestations.{{ $j }}.skills.{{ $k }}.alpha_3Error"></div>
        </div>
        <div class="col-md-6 form-group">
            <label for="#">&nbsp;</label>
            <div class="">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="licences[{{ $i }}][attestations][{{ $j }}][skills][{{ $k }}][interpreter]" id="licences.{{ $i }}.attestations.{{ $j }}.skills.{{ $k }}.interpreter">
                    <label class="form-check-label" for="licences.{{ $i }}.attestations.{{ $j }}.skills.{{ $k }}.interpreter">{{ __('labels.membership.interpreter') }}</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="licences[{{ $i }}][attestations][{{ $j }}][skills][{{ $k }}][interpreter_cassation]" id="interpreter-cassation-{{ $i }}-{{ $j }}-{{ $k }}">
                    <label class="form-check-label" for="interpreter-cassation-{{ $i }}-{{ $j }}-{{ $k }}">{{ __('labels.membership.cassation') }}</label>
                </div>
                <div class="text-danger" id="licences.{{ $i }}.attestations.{{ $j }}.skills.{{ $k }}.interpreterError"></div>
            </div>
            <div class="">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="licences[{{ $i }}][attestations][{{ $j }}][skills][{{ $k }}][translator]" id="licences.{{ $i }}.attestations.{{ $j }}.skills.{{ $k }}.translator">
                    <label class="form-check-label" for="licences.{{ $i }}.attestations.{{ $j }}.skills.{{ $k }}.translator">{{ __('labels.membership.translator') }}</label>
                    <div class="text-danger" id="licences.{{ $i }}.attestations.{{ $j }}.skills.{{ $k }}.translatorError"></div>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="licences[{{ $i }}][attestations][{{ $j }}][skills][{{ $k }}][translator_cassation]" id="translator-cassation-{{ $i }}-{{ $j }}-{{ $k }}">
                    <label class="form-check-label" for="translator-cassation-{{ $i }}-{{ $j }}-{{ $k }}">{{ __('labels.membership.cassation') }}</label>
                </div>
            </div>
        </div>
    </div>

    @if ($k > 0)
        <div class="text-end pe-4 mt-3">
            <button class="btn btn-danger form-action-btn" data-form="items" data-type="skill" data-index="{{ $i }}-{{ $j }}-{{ $k }}" data-route="deleteItem" type="button">
            {{ __('labels.membership.delete_skill') }}</button>
        </div>
    @endif
</div>
