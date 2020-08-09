<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item mx-md-2">
                    <a class="nav-link active" href="/posts">Posts</a>
                </li>  
                <li class="nav-item mx-md-2">
                    <a class="nav-link" href="/comments">Comments</a>
                </li>  
                <li class="nav-item mx-md-2">
                    <a class="nav-link" href="/users">Users</a>
                </li>  
                <li class="nav-item mx-md-2">
                    <a class="nav-link" href="/chats">chats</a>
                </li>  

                <li class="nav-item mx-md-2">
                    <a class="nav-link" href="/about">About <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item mx-md-2">
                    <a class="nav-link" href="/contact">Contact</a>
                </li>
                <li class="nav-item dropdown mx-md-2">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Manage
                    </a>
                    <div class="dropdown-menu position-absolute bg-white border border-dark" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/posts">General feed</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/posts/create">Create Post</a>
                        <a class="dropdown-item" href="/comments/create">Create Comment</a>
                        <a class="dropdown-item" href="/users/create">Create User</a>
                        <a class="dropdown-item" href="/chats/create">Create Chat</a>

                    </div>
                </li>           
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
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
                @endguest
            </ul>
        </div>
    </div>
</nav>

