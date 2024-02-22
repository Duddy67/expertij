<tr class="align-middle" id="document-row-{{ $document->id }}">
  <td><a href="{{ url('/').$document->getUrl() }}" target="_blank" download="{{ $document->file_name }}">{{ $document->file_name }}</a></td>
  <td>{{ $document->content_type }}</td>
  <td>{{ $document->getSize() }}</td>
  <td>
      <form id="replace-document-{{ $document->id }}" action="{{ route('admin.memberships.sharings.document.replace', $document->id ) }}" method="post">
          @method('put')
          @csrf
          <input type="file" class="form-control w-50 float-start me-4" name="replace_document_{{ $document->id }}" id="document-{{ $document->id }}" />
          <input type="hidden" name="_sharing_id" value="{{ $sharing->id }}">
          <input type="hidden" name="_field_name" value="replace_document_{{ $document->id }}">
      </form>
      <button type="button" class="btn btn-success document-management" data-action="replace" data-document-id="{{ $document->id }}">{{ __('labels.button.replace') }}</button>
  </td>
  <td>
      <form id="delete-document-{{ $document->id }}" action="{{ route('admin.memberships.sharings.document.delete', $document->id) }}" method="post">
          @method('delete')
          @csrf
          <input type="hidden" name="_sharing_id" value="{{ $sharing->id }}">
      </form>
      <button type="button" class="btn btn-danger document-management" data-action="delete" data-document-id="{{ $document->id }}">{{ __('labels.button.delete') }}</button>
  </td>
</tr>

