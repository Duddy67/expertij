<div class="row border border-success rounded p-3 m-3" id="attestation-{{ $i }}-{{ $j }}">
    <div class="row w-100">
        <div class="col-md-6 form-group">
            <label for="licences.{{ $i }}.attestations.{{ $j }}.expiry_date">{{ __('labels.membership.expiry_date') }}</label>
            <input type="text" name="licences[{{ $i }}][attestations][{{ $j }}][expiry_date]" class="form-control date" data-options="['startEmpty']" id="licences.{{ $i }}.attestations.{{ $j }}.expiry_date" data-date="{{ $attestation->expiry_date->toDateString() }}" data-format="d M Y" disabled>
            <input type="hidden" id="_licences.{{ $i }}.attestations.{{ $j }}.expiry_date" name="licences[{{ $i }}][attestations][{{ $j }}][_expiry_date]" value="{{ $attestation->expiry_date->toDateString() }}">
        </div>
        <div class="col-md-6" id="attestation-file-button-{{ $i }}-{{ $j }}">
            @if ($attestation->document)
                @include('themes.'.$theme.'.partials.membership.edit.document-file-button', ['fileUrl' => $attestation->document->getUrl(), 'fileName' => $attestation->document->file_name])
            @else
                @include('themes.'.$theme.'.partials.membership.edit.missing-document-button')
            @endif
        </div>
    </div>

    <input type="hidden" name="licences[{{ $i }}][attestations][{{ $j }}][_id]" value="{{ $attestation->id }}">

    <div class="form-group" id="skill-container-{{ $i }}-{{ $j }}" data-latest-index="{{ $attestation->skills->count() - 1 }}">
        @foreach ($attestation->skills as $k => $skill)
            @include('admin.partials.membership.skill', ['skill' => $skill, 'i' => $i, 'j' => $j, 'k' => $k])
        @endforeach
    </div> <!-- skill container -->
</div>

