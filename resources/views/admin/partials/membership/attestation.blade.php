<div class="row border border-success rounded p-3 m-3" id="attestation-{{ $i }}-{{ $j }}">
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="licences.{{ $i }}.attestations.{{ $j }}.expiry_date">{{ __('labels.membership.expiry_date') }}</label>
            <input type="text" name="licences[{{ $i }}][attestations][{{ $j }}][expiry_date]" class="form-control date" data-options="['startEmpty']" id="licences.{{ $i }}.attestations.{{ $j }}.expiry_date" data-date="{{ $attestation->expiry_date->toDateString() }}" data-format="D MMM YYYY" disabled>
            <input type="hidden" id="_licences.{{ $i }}.attestations.{{ $j }}.expiry_date" name="licences[{{ $i }}][attestations][{{ $j }}][_expiry_date]" value="{{ $attestation->expiry_date->toDateString() }}">
            <div class="text-danger" id="licences.{{ $i }}.attestations.{{ $j }}.expiry_dateError"></div>
        </div>
    </div>
    <div class="row" id="attestation-file-button-{{ $i }}-{{ $j }}">
        @include('themes.expertij.partials.membership.edit.attestation-file-button', ['fileUrl' => $attestation->document->getUrl(), 'fileName' => $attestation->document->file_name])
    </div>

    <input type="hidden" name="licences[{{ $i }}][attestations][{{ $j }}][_id]" value="{{ $attestation->id }}">

    <div class="form-group" id="skill-container-{{ $i }}-{{ $j }}" data-latest-index="{{ $attestation->skills->count() - 1 }}">
        @foreach ($attestation->skills as $k => $skill)
            @include('admin.partials.membership.skill', ['skill' => $skill, 'i' => $i, 'j' => $j, 'k' => $k])
        @endforeach
    </div> <!-- skill container -->
</div>

