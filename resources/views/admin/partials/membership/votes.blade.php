
@if (empty($membership->votes)) 
    <div class="alert alert-info" role="alert">
        {{ __('messages.generic.no_item_found') }}
    </div>
@else
    <table class="table table-striped">
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
@endif
