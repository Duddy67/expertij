<div class="row" id="skill-{{ $i }}-{{ $j }}-{{ $k }}">
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="language_{{ $i }}-{{ $j }}-{{ $k }}">{{ __('labels.generic.language') }}</label>
            <select name="licences[{{ $i }}][attestations][{{ $j }}][skills][{{ $k }}][alpha_3]" class="form-select" id="language_{{ $i }}-{{ $j }}-{{ $k }}" required>
                <option value="">{{ __('labels.generic.select_option') }}</option>
                @foreach ($options['language'] as $option)
                    <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                @endforeach
            </select>
            <div class="text-danger" id="language_{{ $i }}-{{ $j }}-{{ $k }}Error"></div>
        </div>
    </div>

    @if ($k > 0)
        <div class="text-center">
            <button class="btn btn-danger form-action-btn" data-form="registration" data-type="skill" data-index="{{ $i }}-{{ $j }}-{{ $k }}" data-route="deleteItem" type="button">
            {{ __('labels.membership.delete_skill') }}</button>
        </div>
    @endif
</div>
