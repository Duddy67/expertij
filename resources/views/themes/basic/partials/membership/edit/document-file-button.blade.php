<div class="col-md-6 form-group">
@php $label = (isset($label) ? $label : 'document')@endphp
    <label>{{ __('labels.generic.'.$label) }}</label>
    <div>
        <a class="btn btn-primary" href="{{ url('/').$fileUrl }}" download="{{ $fileName }}" target="_blank">{{ $fileName }}</a>
    </div>
</div>
