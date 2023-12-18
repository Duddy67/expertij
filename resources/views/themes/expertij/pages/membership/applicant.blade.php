
            <div class="tab-pane" id="licences">
                <div class="container" id="licence-container" data-latest-index="{{ $membership->licences->count() - 1 }}">
                    @foreach ($membership->licences as $i => $licence)
                        @include('admin.partials.membership.licence', ['licence' => $licence, 'i' => $i])
                    @endforeach
                </div> <!-- licence container -->
            </div>

            <div class="tab-pane" id="profile">
                @include('admin.partials.membership.profile', ['user' => $membership->user])
            </div>
