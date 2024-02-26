@extends ('admin.layouts.default')

@section ('header')
    <p class="h3">{{ __('labels.title.memberships') }}</p>
@endsection

@section ('main')
    <div class="card shadow-sm">
        <div class="card-body">
            <x-toolbar :items=$actions />
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <x-filters :filters="$filters" :url="$url" />
        </div>
    </div>

    @if (!empty($rows)) 
        <x-item-list :columns="$columns" :rows="$rows" :url="$url" />
    @else
        <div class="alert alert-info" role="alert">
            {{ __('messages.generic.no_item_found') }}
        </div>
    @endif

    <x-pagination :items=$items />

    <input type="hidden" id="destroyItems" value="{{ route('admin.memberships.index', $query) }}">
    <input type="hidden" id="checkinItems" value="{{ route('admin.memberships.massCheckIn', $query) }}">
    <input type="hidden" id="publishItems" value="{{ route('admin.memberships.massPublish', $query) }}">
    <input type="hidden" id="unpublishItems" value="{{ route('admin.memberships.massUnpublish', $query) }}">
    <input type="hidden" id="_checkRenewal" value="{{ route('admin.memberships.checkRenewal', $query) }}">
    <input type="hidden" id="_export" value="{{ route('admin.memberships.export', $query) }}">
    <x-js-messages />

    <form id="selectedItems" action="{{ route('admin.memberships.index', $query) }}" method="post">
        @method('delete')
        @csrf
    </form>
@endsection

@push ('scripts')
    <script src="{{ asset('/js/admin/list.js') }}"></script>
@endpush
