
<div id="documentSharing">
@if (isset($sharing))
  <table class="table table-striped" id="documentTable">
    <thead>
      <tr>
        <th scope="col">{{ __('labels.generic.name') }}</th>
        <th scope="col">{{ __('labels.generic.type') }}</th>
        <th scope="col">{{ __('labels.generic.size') }}</th>
        <th scope="col"></th>
        <th scope="col"></th>
      </tr>
    </thead>
    <tbody>
        @foreach ($sharing->documents as $document)
            @include('admin.partials.sharing.document-row')
        @endforeach
    </tbody>
  </table>
@endif

<div class="col-md-6 form-group">

    <form id="add-document" action="{{ route('admin.memberships.sharings.document.add') }}" method="post">
        @method('post')
        @csrf

        <input type="file" class="form-control" name="add_document" id="document-0" />
        <button type="button" class="btn btn-primary document-management" data-action="add">Add</button>
        <div class="text-danger" id="document_0Error"></div>
        @if (isset($sharing))
            <input type="hidden" name="_sharing_id" value="{{ $sharing->id }}">
        @endif
    </form>
</div>
</div>
