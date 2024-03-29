
@include('themes.basic.partials.flash-message')

@if ($membership->hasUserVoted(auth()->user()))
    <div class="alert alert-info" role="alert">
        {{ __('messages.membership.already_voted') }}
    </div>
@else
    <form method="post" action="{{ route('memberships.applicants.vote', $query) }}" id="votingForm" enctype="multipart/form-data">
        @csrf
        @method('put')

        <div class="row mb-3">
            <div class="col-md-3 form-group">
                <input class="form-check-input" type="radio" name="choice" id="yes" value="1">
                <label class="form-check-label" for="yes">{{ __('labels.generic.yes') }}</label>
            </div>
            <div class="col-md-3 form-group">
                <input class="form-check-input" type="radio" name="choice" id="no" value="0">
                <label class="form-check-label" for="no">{{ __('labels.generic.no') }}</label>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6 form-group">
                <label for="comment">{{ __('labels.generic.comment') }}</label>
                <textarea name="comment" rows="5" cols="35" class="form-control" id="comment">{{ $comment }}</textarea>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-2 form-group">
                <button class="btn btn-success form-action-btn" id="voting-btn" type="button">
                    {{ __('labels.membership.vote') }}
                </button>
            </div>
            <div class="col-md-2 form-group">
                <button class="btn btn-secondary form-action-btn" id="back-btn" type="button">
                    {{ __('labels.generic.back') }}
                </button>
            </div>
        </div>
        <input type="hidden" id="backUrl" value="{{ route('memberships.applicants') }}">
        <input type="hidden" id="_isAssociatedMember" value="{{ $membership->associated_member }}">
    </form>
    <x-js-messages />
@endif

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">@php echo __('labels.generic.profile'); @endphp</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="professional_information-tab" data-bs-toggle="tab" data-bs-target="#professional_information" type="button" role="tab" aria-controls="professional_information" aria-selected="true">@php echo __('labels.membership.professional_status'); @endphp</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="licences-tab" data-bs-toggle="tab" data-bs-target="#licences" type="button" role="tab" aria-controls="licences" aria-selected="false">@php echo __('labels.membership.licences'); @endphp</button>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">

            @if ($membership->associated_member)
                <input type="text" class="form-control mt-4" value="{{ __('labels.membership.associated_member') }}" disabled="">
            @endif

                @include('admin.partials.membership.profile', ['user' => $membership->user])
            </div>

            @php $dateFormats = []; @endphp

            <div class="tab-pane" id="professional_information" role="tabpanel" aria-labelledby="professional_information-tab">
            @foreach ($fields as $key => $field)

                @php $value = (isset($membership)) ? old($field->name, $field->value) : old($field->name); @endphp
                <x-input :field="$field" :value="$value" />

                @if ($field->type == 'date' && isset($field->format))
                     @php $dateFormats[$field->name] = $field->format; @endphp
                @endif

                @if (!next($fields) || isset($fields[$key + 1]->tab))
                    @if ($field->name == 'why_expertij')
                        @if ($membership->professionalAttestation)
                            @include('themes.basic.partials.membership.edit.document-file-button', ['fileUrl' => $membership->professionalAttestation->getUrl(), 'fileName' => $membership->professionalAttestation->file_name, 'label' => 'attestation'])
                        @else
                            @include('themes.basic.partials.membership.edit.missing-document-button', ['label' => 'attestation'])
                        @endif

                        @if ($membership->resume)
                            @include('themes.basic.partials.membership.edit.document-file-button', ['fileUrl' => $membership->resume->getUrl(), 'fileName' => $membership->resume->file_name, 'label' => 'resume'])
                        @else
                            @include('themes.basic.partials.membership.edit.missing-document-button', ['label' => 'resume'])
                        @endif
                    @endif
                @endif
            @endforeach
                    </div>

            <div class="tab-pane" id="licences" role="tabpanel" aria-labelledby="licences-tab">
                <div class="container" id="licence-container" data-latest-index="{{ $membership->licences->count() - 1 }}">
                    @foreach ($membership->licences as $i => $licence)
                        @include('admin.partials.membership.licence', ['licence' => $licence, 'i' => $i])
                    @endforeach
                </div> <!-- licence container -->
            </div>
        </div>

@push ('scripts')
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/localeData.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/locale/{{ config('app.locale') }}.js"></script>
    <script type="text/javascript" src="{{ asset('/vendor/codalia/lang/'.config('app.locale').'.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/codalia/c.datepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/datepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vote.js') }}"></script>
@endpush

