
@if ($membership->votes->count()) 
    <table class="table table-striped mt-4">
        <thead>
            <tr>
                <th scope="col">{{ __('labels.generic.name') }}</th>
                <th scope="col">{{ __('labels.membership.voting') }}</th>
                <th scope="col">{{ __('labels.generic.created_at') }}</th>
                <th scope="col">{{ __('labels.generic.comment') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($membership->votes as $vote)
                <tr> 
                    <td>{{ $vote->user->name  }}</td>
                    <td>{{ __('labels.generic.'.$vote->choice)  }}</td>
                    <td>{{ $vote->created_at }}</td>
                    <td>{{ $vote->comment }}</td>
                </tr> 
            @endforeach
        </tbody>
    </table>
@else
    <div class="alert alert-info mt-4" role="alert">
        {{ __('messages.generic.no_item_found') }}
    </div>
@endif
