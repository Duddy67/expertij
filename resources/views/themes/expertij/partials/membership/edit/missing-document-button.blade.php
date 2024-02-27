<div class="col-md-6 form-group">
@php $label = (isset($label) ? $label : 'document')@endphp
    <label>{{ __('labels.generic.'.$label) }}</label>
    <div>
        <button type="button" class="btn btn-danger">{{ __('labels.generic.missing_document') }}</button>
    </div>
</div>
