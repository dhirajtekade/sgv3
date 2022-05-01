
    <nav class="navbar navbar-expand-md shadow-sm navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else

                        <li class="nav-item">
                            <div class="form-check form-switch nav-link">
                                <input class="form-check-input" type="checkbox" id="displayTodayRecord">
                                <label class="form-check-label" for="displayTodayRecord">Today's Record Display</label>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:void(0)" id="openaddNewModal" class="nav-link scanqr"> <i class="fa-solid fa-qrcode"></i>Add as a Mht</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('users.scanbymachine')}}" class="nav-link scanqr"> <i class="fa-solid fa-qrcode"></i>Scan by Machine</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('users.scanqr3')}}" class="nav-link scanqr"> <i class="fa-solid fa-qrcode"></i>Scan by Camera</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('setting_form') }}">Settings</a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link" href="#">Attendace</a>
                        </li> --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('eveninfo') }}" >Event Info</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('report_page') }}">Reports</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                </li>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

