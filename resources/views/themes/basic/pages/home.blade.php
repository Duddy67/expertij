
@if (isset($category) && $category)
    <ul class="post-list">
	@if (count($posts))
	    @foreach ($posts as $post)
		@include ('themes.basic.partials.post')
	    @endforeach
	@else
	    <div>No post</div>
	@endif
    </ul>
@endif

@push ('scripts')
    <script type="text/javascript" src="{{ $public }}/js/post/category.js"></script>
@endpush
