<table class="table table-striped mt-4">
    <thead>
        <tr>
            <th scope="col">{{ __('labels.generic.name') }}</th>
            <th scope="col">{{ __('labels.generic.type') }}</th>
            <th scope="col">{{ __('labels.generic.size') }}</th>
            <th scope="col">{{ __('labels.generic.date') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($invoices as $invoice)
            <tr>
                <td>
                    <a href="{{ url('/').$invoice->getUrl() }}" target="_blank" download="{{ $invoice->file_name }}">{{ $invoice->file_name }}</a>
                </td>
                <td>
                    {{ __('labels.generic.invoice') }}
                </td>
                <td>
                    {{ $invoice->getSize() }}
                </td>
                <td>
                    {{ $invoice->created_at->format('d/m/Y') }}
                </td>
            </tr>
        @endforeach

        @foreach ($documents as $document)
            <tr>
                <td>
                    <a href="{{ url('/').$document->getUrl() }}" target="_blank" download="{{ $document->file_name }}">{{ $document->file_name }}</a>
                </td>
                <td>
                    {{ __('labels.generic.document') }}
                </td>
                <td>
                    {{ $document->getSize() }}
                </td>
                <td>
                    {{ $document->created_at->format('d/m/Y') }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
