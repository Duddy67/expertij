@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
@endpush

@if (!$associated)
    <div class="card">
        <div class="card-body">
            <form id="member-filters" action="{{ route('memberships.members', $query) }}" method="get">
                <div class="row">
                    <div class="col">
                        <label for="languages">@lang ('labels.membership.languages')</label>
                        <select name="languages[]" multiple id="languages" class="form-select select2">
                            @foreach ($options['language'] as $option)
                                <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col" id="skill-col">
                        <label for="skill">@lang ('labels.membership.skill')</label>
                        <select name="skill" id="skill" class="form-select select2">
                            <option value="">- {{ __('labels.generic.all') }} -</option>
                            <option value="interpreter">{{ __('labels.membership.interpreter') }}</option>
                            <option value="translator">{{ __('labels.membership.translator') }}</option>
                        </select>
                    </div>

                    <div class="col">
                        <label for="licence">@lang ('labels.membership.licence')</label>
                        <select name="licence" id="licence" class="form-select select2">
                            <option value="">- {{ __('labels.generic.all') }} -</option>
                            @foreach ($options['licence_type'] as $option)
                                <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col" id="appeal_courts-col">
                        <label for="appeal_courts">@lang ('labels.membership.appeal_courts')</label>
                        <select name="appeal_courts[]" multiple id="appeal_courts" class="form-select select2">
                            @foreach ($options['jurisdictions']['appeal_court'] as $option)
                                <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col" id="courts-col">
                        <label for="courts">@lang ('labels.membership.courts')</label>
                        <select name="courts[]" multiple id="courts" class="form-select select2">
                            @foreach ($options['jurisdictions']['court'] as $option)
                                <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 mt-4">
                        <button type="button" id="search-btn" class="btn btn-space btn-secondary">@lang ('labels.button.search')</button>
                    </div>
                    <div class="col-6 mt-4">
                        <button type="button" id="clear-btn" class="btn btn-space btn-secondary">@lang ('labels.button.clear')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endif

<ul class="list-group pt-4">
    @if (count($members))
	@foreach ($members as $member)
            <li class="list-group-item">
                <div class="row">
                    <div class="col-sm-4">
                        <p class="h4">{{ $member->first_name }} {{ $member->last_name }}</p>
                        <div>
                            <img src="{{ url('/').$member->user->photo->getThumbnailUrl() }}" width="100" height="100" />
                        </div>
                        <p class="mt-4">
                            {{ $member->user->address->street }}<br />
                            {{ $member->user->address->postcode }} {{ $member->user->address->city }}<br />
                            {{ $member->email }}
                        </p>
                    </div>
                    <div class="col-sm">
                        @foreach ($member->licences as $licence)
                            <p>{{ __('labels.membership.'.$licence->type) }}</p> 
                            @php $jurisdictionType = ($licence->type == 'expert') ? __('labels.membership.appeal_court') : __('labels.membership.court'); @endphp
                            <p>{{ $jurisdictionType }}: {{ $licence->jurisdiction->name }}</p> 
                            <hr>
                            <p>{{ __('labels.membership.language') }}(s)</p>
                            @foreach ($licence->attestations as $attestation)
                                @foreach ($attestation->skills as $skill)
                                    <div>
                                        <p class="d-inline">{{ $languages[$skill->language_id] }}</p>
                                        @if ($skill->interpreter)
                                            <p class="d-inline">{{ __('labels.membership.interpreter') }}</p>
                                            @if ($skill->interpreter_cassation)
                                                <small>(Cassation)</small>
                                            @endif
                                        @endif

                                        @if ($skill->translator)
                                            <p class="d-inline">{{ __('labels.membership.translator') }}</p>
                                            @if ($skill->translator_cassation)
                                                <small>(Cassation)</small>
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </li>
	@endforeach
    @else
	<div class="alert alert-info">{{ __('messages.generic.no_item_found') }}</div>
    @endif
</ul>

<x-pagination :items=$members />

@push ('scripts')
    <!-- Select2 Plugin -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="{{ $public }}/js/members.js"></script>
@endpush
