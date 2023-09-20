<div class="row" id="attestation-{{ $i }}-{{ $j }}">
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="attestation_{{ $i }}_{{ $j }}">{{ __('labels.generic.attestation') }}</label>
            <input type="file" name="attestation__file_{{ $i }}_{{ $j }}" class="form-control" id="attestation--file-{{ $i }}-{{ $j }}">
            <div class="text-danger" id="attestation_{{ $i }}_{{ $j }}Error"></div>
        </div>
        <div class="col-md-6 form-group">
            <label for="expiry_date">{{ __('labels.membership.expiry_date') }}</label>
            <input type="text" name="licences[{{ $i }}][attestations][{{ $j }}][expiry_date]" class="form-control date" id="expiry_date_{{ $i }}_{{ $j }}" data-date="0" data-format="D MMM YYYY">
            <input type="hidden" id="_expiry_date_{{ $i }}_{{ $j }}" name="licences[{{ $i }}][attestations][{{ $j }}][_expiry_date]" value="">
            <div class="text-danger" id="expiry_dateError"></div>
        </div>
    </div>

    <div class="form-group" id="skill-container-{{ $i }}-{{ $j }}">
        @include('themes.expertij.partials.membership.registration.skill')
    </div> <!-- skill container -->
    <div class="text-center">
        <button class="btn btn-success form-action-btn" data-form="registration" data-type="skill" data-licence-index="{{ $i }}" data-attestation-index="{{ $j }}" data-route="addItem" type="button">
        {{ __('labels.membership.add_skill') }}</button>
    </div>

    @if ($j > 0)
        <div class="text-center">
            <button class="btn btn-danger form-action-btn" data-form="registration" data-type="attestation" data-index="{{ $i }}-{{ $j }}" data-route="deleteItem" type="button">
            {{ __('labels.membership.delete_attestation') }}</button>
        </div>
    @endif
</div>

