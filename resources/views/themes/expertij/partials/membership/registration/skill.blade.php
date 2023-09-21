<div class="row" id="skill-{{ $i }}-{{ $j }}-{{ $k }}">
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="language_{{ $i }}-{{ $j }}-{{ $k }}">{{ __('labels.membership.language') }}</label>
            <select name="licences[{{ $i }}][attestations][{{ $j }}][skills][{{ $k }}][alpha_3]" class="form-select" id="language_{{ $i }}-{{ $j }}-{{ $k }}" required>
                <option value="">{{ __('labels.generic.select_option') }}</option>
                @foreach ($options['language'] as $option)
                    <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                @endforeach
            </select>
            <div class="text-danger" id="language_{{ $i }}-{{ $j }}-{{ $k }}Error"></div>
        </div>
        <div class="col-md-6 form-group">
            <label for="#">&nbsp;</label>
            <div class="">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="licences[{{ $i }}][attestations][{{ $j }}][skills][{{ $k }}][interpreter]" id="interpreter-{{ $i }}-{{ $j }}-{{ $k }}">
                    <label class="form-check-label" for="interpreter-{{ $i }}-{{ $j }}-{{ $k }}">{{ __('labels.membership.interpreter') }}</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="licences[{{ $i }}][attestations][{{ $j }}][skills][{{ $k }}][interpreter_cassation]" id="interpreter-cassation-{{ $i }}-{{ $j }}-{{ $k }}">
                    <label class="form-check-label" for="interpreter-cassation-{{ $i }}-{{ $j }}-{{ $k }}">{{ __('labels.membership.cassation') }}</label>
                </div>
            </div>
            <div class="">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="licences[{{ $i }}][attestations][{{ $j }}][skills][{{ $k }}][translator]" id="translator-{{ $i }}-{{ $j }}-{{ $k }}">
                    <label class="form-check-label" for="translator-{{ $i }}-{{ $j }}-{{ $k }}">{{ __('labels.membership.translator') }}</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="licences[{{ $i }}][attestations][{{ $j }}][skills][{{ $k }}][translator_cassation]" id="translator-cassation-{{ $i }}-{{ $j }}-{{ $k }}">
                    <label class="form-check-label" for="translator-cassation-{{ $i }}-{{ $j }}-{{ $k }}">{{ __('labels.membership.cassation') }}</label>
                </div>
            </div>
        </div>
    </div>

    @if ($k > 0)
        <div class="text-center">
            <button class="btn btn-danger form-action-btn" data-form="items" data-type="skill" data-index="{{ $i }}-{{ $j }}-{{ $k }}" data-route="deleteItem" type="button">
            {{ __('labels.membership.delete_skill') }}</button>
        </div>
    @endif
</div>
