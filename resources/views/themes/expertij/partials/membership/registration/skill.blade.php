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
</div>
