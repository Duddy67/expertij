<div class="row border border-success rounded p-3 m-3" id="attestation-{{ $i }}-{{ $j }}">
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="attestation_{{ $i }}_{{ $j }}">{{ __('labels.generic.attestation') }}</label>
            <input type="file" name="attestation_{{ $i }}_{{ $j }}" class="form-control" id="attestation_{{ $i }}_{{ $j }}">
            <div class="text-danger" id="attestation_{{ $i }}_{{ $j }}Error"></div>
            <input type="hidden" name="licences[{{ $i }}][attestations][{{ $j }}][_attestation_file_id]" value="attestation_{{ $i }}_{{ $j }}">
        </div>
        <div class="col-md-6 form-group">
            <label for="licences.{{ $i }}.attestations.{{ $j }}.expiry_date">{{ __('labels.membership.expiry_date') }}</label>
            <input type="text" name="licences[{{ $i }}][attestations][{{ $j }}][expiry_date]" class="form-control date" data-options="['startEmpty']" id="licences.{{ $i }}.attestations.{{ $j }}.expiry_date" data-date="0" data-format="d M Y">
            <input type="hidden" id="_licences.{{ $i }}.attestations.{{ $j }}.expiry_date" name="licences[{{ $i }}][attestations][{{ $j }}][_expiry_date]" value="">
            <div class="text-danger" id="licences.{{ $i }}.attestations.{{ $j }}.expiry_dateError"></div>
        </div>
    </div>
    <div class="row" id="attestation-file-button-{{ $i }}-{{ $j }}">
    </div>

    <div class="form-group" id="skill-container-{{ $i }}-{{ $j }}" data-latest-index="{{ $k }}">
        @include('themes.expertij.partials.membership.registration.skill')
    </div> <!-- skill container -->
    <div class="text-end pe-4">
        <button class="btn btn-success form-action-btn" data-form="items" data-type="skill" data-licence-index="{{ $i }}" data-attestation-index="{{ $j }}" data-route="createItem" type="button">
        {{ __('labels.membership.add_skill') }}</button>
    </div>

    @if ($j > 0)
        <div class="text-end pe-4 mt-3">
            <button class="btn btn-danger form-action-btn" data-form="items" data-type="attestation" data-index="{{ $i }}-{{ $j }}" data-route="deleteItem" type="button">
            {{ __('labels.membership.delete_attestation') }}</button>
        </div>
    @endif
</div>

