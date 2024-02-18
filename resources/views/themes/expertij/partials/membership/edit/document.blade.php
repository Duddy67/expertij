<table class="table table-striped mt-4">
    <thead>
        <tr>
            <th scope="col">{{ __('labels.generic.name') }}</th>
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
                    {{ $invoice->getSize() }}
                </td>
                <td>
                    {{ $invoice->created_at->format('d/m/Y') }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
