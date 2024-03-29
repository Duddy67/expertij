@extends ('admin.layouts.default')

@section ('header')
    <p class="h3">Menu sharings</p>
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
            No item has been found.
        </div>
    @endif

    <input type="hidden" id="createItem" value="{{ route('admin.memberships.sharings.create', $query) }}">
    <input type="hidden" id="destroyItems" value="{{ route('admin.memberships.sharings.index', $query) }}">
    <input type="hidden" id="checkinItems" value="{{ route('admin.memberships.sharings.massCheckIn', $query) }}">
    <input type="hidden" id="publishItems" value="{{ route('admin.memberships.sharings.massPublish', $query) }}">
    <input type="hidden" id="unpublishItems" value="{{ route('admin.memberships.sharings.massUnpublish', $query) }}">
    <x-js-messages />

    <form id="selectedItems" action="{{ route('admin.memberships.sharings.index', $query) }}" method="post">
        @method('delete')
        @csrf
    </form>
@endsection

@push ('scripts')
    <script src="{{ asset('/js/admin/list.js') }}"></script>
@endpush
