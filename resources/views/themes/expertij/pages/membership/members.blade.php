
<div class="card">
    <div class="card-body">

    </div>
</div>

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
    <script type="text/javascript" src="{{ $public }}/js/members.js"></script>
@endpush
