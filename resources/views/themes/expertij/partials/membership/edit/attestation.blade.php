<div class="row border border-success rounded p-3 m-3" id="attestation-{{ $i }}-{{ $j }}">
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="attestation_{{ $i }}_{{ $j }}">{{ __('labels.generic.attestation') }}</label>
            <input type="file" name="attestation_{{ $i }}_{{ $j }}" class="form-control" id="attestation_{{ $i }}_{{ $j }}">
            <div class="text-danger" id="attestation_{{ $i }}_{{ $j }}Error"></div>
            <input type="hidden" name="_attestation_file_id" value="attestation_{{ $i }}_{{ $j }}">
        </div>
        <div class="col-md-6 form-group">
            <label>{{ __('labels.generic.attestation') }}</label>
            <div id="professional_attestation_document">
                <a class="btn btn-success" href="{{ url('/').$attestation->document->getUrl() }}" target="_blank">{{ $attestation->document->file_name }}</a>
            </div>
        </div>
        <div class="col-md-6 form-group">
            <label for="licences.{{ $i }}.attestations.{{ $j }}.expiry_date">{{ __('labels.membership.expiry_date') }}</label>
            <input type="text" name="licences[{{ $i }}][attestations][{{ $j }}][expiry_date]" class="form-control date" data-options="['startEmpty']" id="licences.{{ $i }}.attestations.{{ $j }}.expiry_date" data-date="{{ $attestation->expiry_date->toDateString() }}" data-format="D MMM YYYY">
            <input type="hidden" id="_licences.{{ $i }}.attestations.{{ $j }}.expiry_date" name="licences[{{ $i }}][attestations][{{ $j }}][_expiry_date]" value="{{ $attestation->expiry_date->toDateString() }}">
            <div class="text-danger" id="licences.{{ $i }}.attestations.{{ $j }}.expiry_dateError"></div>
        </div>
    </div>

    <input type="hidden" name="licences[{{ $i }}][attestations][{{ $j }}][_id]" value="{{ $attestation->id }}">

    <div class="form-group" id="skill-container-{{ $i }}-{{ $j }}" data-latest-index="{{ $attestation->skills->count() - 1 }}">
        @foreach ($attestation->skills as $k => $skill)
            @include('themes.expertij.partials.membership.edit.skill', ['skill' => $skill, 'i' => $i, 'j' => $j, 'k' => $k])
        @endforeach
    </div> <!-- skill container -->
    <div class="text-end pe-4">
        <button class="btn btn-success form-action-btn" data-form="items" data-type="skill" data-licence-index="{{ $i }}" data-attestation-index="{{ $j }}" data-route="createItem" type="button">
        {{ __('labels.membership.add_skill') }}</button>
    </div>

    @if ($j > 0)
        <div class="text-end pe-4 mt-3">
            <button class="btn btn-danger form-action-btn" data-form="items" data-type="attestation" data-item-id="{{ $attestation->id }}" data-index="{{ $i }}-{{ $j }}" data-route="deleteItem" type="button">
            {{ __('labels.membership.delete_attestation') }}</button>
        </div>
    @endif
</div>

