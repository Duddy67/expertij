<div id="documentSharing" class="border-top mt-3">
    <h4 class="mt-3">Documents</h4>
    @if (isset($sharing))
        <table class="table table-striped mb-5" id="documentTable">
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
                @foreach ($sharing->documents as $key => $document)
                    @include('admin.partials.sharing.document-row')
                @endforeach
            </tbody>
        </table>

        <div class="col-md-6 form-group">
            <form id="add-document" class="mb-5" action="{{ route('admin.memberships.sharings.document.add') }}" method="post">
                @method('post')
                @csrf

                <input type="file" class="form-control w-75 float-start me-3" name="add_document" id="add-new-document" />
                <button type="button" class="btn btn-primary document-management" data-action="add">{{ __('labels.button.add') }}</button>

                <input type="hidden" name="_sharing_id" value="{{ $sharing->id }}">
            </form>
        </div>
    @else 
        <label for="document-1" class="mt-2 mb-2">{{ __('labels.generic.document_n', ['number' => 1]) }}</label>
        <input type="file" class="form-control w-50" name="document_1" id="document-1" />
        <div class="text-danger" id="document_1Error"></div>
        <label for="document-2" class="mt-3 mb-2">{{ __('labels.generic.document_n', ['number' => 2]) }} ({{ __('labels.generic.optional') }})</label>
        <input type="file" class="form-control w-50" name="document_2" id="document-2" />
        <label for="document-3" class="mt-3 mb-2">{{ __('labels.generic.document_n', ['number' => 3]) }} ({{ __('labels.generic.optional') }})</label>
        <input type="file" class="form-control w-50" name="document_3" id="document-3" />
    @endif
</div>
