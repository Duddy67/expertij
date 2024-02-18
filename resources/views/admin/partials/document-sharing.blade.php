
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
            <tr class="align-middle" id="row-document-{{ $document->id }}">
              <td><a href="{{ url('/').$document->getUrl() }}" target="_blank" download="{{ $document->file_name }}">{{ $document->file_name }}</a></td>
              <td>{{ $document->content_type }}</td>
              <td>{{ $document->getSize() }}</td>
              <td>
                  <input type="file" class="form-control w-50 float-start me-4" name="replace_document_0" id="document-0" />
                  <button type="button" class="btn btn-success">Remplacer</button>
              </td>
              <td><button type="button" class="btn btn-danger delete-document" data-document-id="{{ $document->id }}">delete</button></td>
            </tr>
        @endforeach
    </tbody>
  </table>
@endif

<div class="col-md-6 form-group">
    @if (!isset($sharing))
        <input type="file" class="form-control" name="document_0" id="document-0" />
        <div class="text-danger" id="document_0Error"></div>
    @endif

    <div id="document">
    </div>
</div>
