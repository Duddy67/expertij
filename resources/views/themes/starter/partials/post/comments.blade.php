<h3>{{ __('labels.post.comments') }}</h3>

@include('themes.starter.layouts.flash-message')

@guest
    <div class="alert alert-info" role="alert">
        {{ __('messages.post.comments_authentication_required') }}
    </div>
@endguest

@auth
    <form method="post" action="{{ route('post.comment', $query) }}">
        @csrf

        <textarea name="comment" id="tiny-comment-0" data-comment-id="0" class="tinymce-texteditor"></textarea>
        @error('comment')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <input type="submit" value="{{ __('labels.generic.submit') }}" class="btn btn-success mt-2 mb-4">
    </form>
@endauth

@if ($post->comments()->exists())
    @foreach ($post->comments as $comment)
        <div class="card mb-4">
            <div class="card-header">
                {{ __('labels.post.posted_by', ['author' => $comment->author]) }} at @date ($comment->created_at->tz($timezone))

                @if (auth()->check() && Auth::user()->id == $comment->owned_by)
                    <button type="button" id="delete-btn-{{ $comment->id }}" data-comment-id="{{ $comment->id }}" class="btn btn-space btn-danger float-end">@lang ('labels.button.delete')</button>
                    <span class="float-end">&nbsp;</span>
                    <button type="button" id="edit-btn-{{ $comment->id }}" data-comment-id="{{ $comment->id }}" class="btn btn-space btn-primary float-end">@lang ('labels.button.edit')</button>

                    <div class="alert alert-success alert-block flash-message d-none" id="ajax-message-alert-{{ $comment->id }}">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <strong id="ajax-message-{{ $comment->id }}"></strong>
                    </div>

                    <form id="updateComment-{{ $comment->id }}" action="{{ route('post.comment.update', ['comment' => $comment]) }}" style="display:none;" method="post">
                        @method('put')
                        @csrf

                        <textarea name="comment-{{ $comment->id }}" id="tiny-comment-{{ $comment->id }}" data-comment-id="{{ $comment->id }}" class="tinymce-texteditor">{{ $comment->text }}</textarea>
                        <button type="button" id="update-btn-{{ $comment->id }}" data-comment-id="{{ $comment->id }}" class="btn btn-space btn-success mt-2">@lang ('labels.button.update')</button>
                        <button type="button" id="cancel-btn-{{ $comment->id }}" data-comment-id="{{ $comment->id }}" class="btn btn-space btn-info mt-2">@lang ('labels.button.cancel')</button>
                        <div class="text-danger mt-2" id="comment-{{ $comment->id }}Error"></div>
                    </form>

                    <form id="deleteComment-{{ $comment->id }}" action="{{ route('post.comment.delete', ['id' => $comment->id]) }}" method="post">
                        @method('delete')
                        @csrf
                    </form>
                @endif
            </div>
            <div id="comment-{{ $comment->id }}" class="card-body">
                {!! $comment->text !!}
            </div>
        </div>
    @endforeach
@else
    <div class="alert alert-info" role="alert">
        {{ __('messages.generic.no_item_found') }}
    </div>
@endif

@push('scripts')
    <script type="text/javascript" src="{{ asset('/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('/vendor/codalia/c.ajax.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/post/comment.js') }}"></script>
@endpush

