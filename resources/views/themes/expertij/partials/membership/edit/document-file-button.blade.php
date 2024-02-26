<div class="col-md-6 form-group">
    <label>{{ __('labels.generic.attestation') }}</label>
    <div>
        <a class="btn btn-primary" href="{{ url('/').$fileUrl }}" download="{{ $fileName }}" target="_blank">{{ $fileName }}</a>
    </div>
</div>
