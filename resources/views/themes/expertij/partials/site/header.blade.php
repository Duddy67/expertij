<nav class="navbar navbar-expand-md navbar-light bg-light">
    <button class="navbar-toggler ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

    @if ($page['menu'])
        <div class="collapse navbar-collapse" id="navbarCollapse">
              <ul class="navbar-nav mr-auto">
                  @foreach ($page['menu']->getMenuItems() as $item)
                      @if ($item->url == '/memberships/create' && Auth::check() && Auth::user()->membership()->exists())
                          @continue
                      @endif

                      @include ('themes.expertij.partials.menu.items')
                  @endforeach
              </ul>
        </div>
    @endif

    @if (Route::has('login'))
        <div class="hidden fixed me-2 px-6 py-4 sm:block">
            @auth
                <ul class="navbar-nav ml-auto">
                    <!--<a href="{{ url('/profile') }}" class="text-sm text-gray-700 underline">Profile</a>-->
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="{{ route('profile.edit') }}" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            @if (Auth::user()->membership()->exists()) 
                                <a class="dropdown-item" href="{{ route('memberships.edit') }}">{{ __('labels.title.membership') }}</a>
                            @endif

                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                    @accessadmin ()
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin') }}">{{ __('Admin') }}</a>
                        </li>
                    @endaccessadmin
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('memberships.applicants') }}">{{ __('labels.membership.applicants') }}</a>
                        </li>
                </ul>
            @else
                <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Log in</a>

                @if (Route::has('register') && $page['allow_registering'])
                    <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Register</a>
                @endif
            @endauth
        </div>
     @endif
</nav>
