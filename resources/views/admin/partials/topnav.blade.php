<header class="{{ $customThemeBackground == 'on' ? 'dash-header transprent-bg' : 'dash-header' }}">
    <div class="header-wrapper">
        <div class="me-auto dash-mob-drp">
            <ul class="list-unstyled">
                <li class="dash-h-item mob-hamburger">
                    <a href="#!" class="dash-head-link" id="mobile-collapse">
                        <div class="hamburger hamburger--arrowturn">
                            <div class="hamburger-box">
                                <div class="hamburger-inner"></div>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="dropdown dash-h-item drp-company">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="theme-avtar">
                            <img src="{{ !empty(Auth::user()->avatar) && checkFile(Auth::user()->avatar) ? getFile(Auth::user()->avatar) : getFile('uploads/users-avatar/avatar.png') . '?' . time() }}"
                                class="img-fluid rounded-circle header-avatar" width="50">

                        </span>
                        <span class="hide-mob ms-2">{{ __('Hi') }}, {{ Auth::user()->name }}</span>
                        <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown">
                        @permission('user profile manage')
                        <a href="{{ route('profile') }}" class="dropdown-item">
                            <i class="ti ti-user text-dark"></i><span>{{ __('Profile') }}</span>
                        </a>
                        @endpermission
                        <a href="#!" class="dropdown-item"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <i class="ti ti-power"></i>
                            <span>{{ __('Logout') }}</span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </div>
        <div class="ms-auto">
            <ul class="list-unstyled">

                <li class="dropdown dash-h-item drp-language">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="ti ti-world nocolor"></i>
                        <span class="drp-text hide-mob">{{ ucFirst($language->fullName) }}</span>
                        <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">
                        <div class="dropdown-menu-inner">
                            @foreach (languages() as $code => $lang)
                                <a href="{{ route('admin.lang.update', $code) }}"
                                    class="dropdown-item {{ $currantLang == $code ? 'text-primary' : '' }}">
                                    <span>{{ ucFirst($lang) }}</span>
                                </a>
                            @endforeach

                            @if (\Auth::user()->parent == 0)
                                @permission('language create')
                                    <a href="#" data-url="{{ route('admin.lang.create') }}" data-size="md"
                                        data-ajax-popup="true" data-title="{{ __('Create New Language') }}"
                                        class="dropdown-item border-top py-2 text-primary">{{ __('Create Language') }}</a>
                                    </a>
                                @endpermission
                                @permission('language manage')
                                    <a href="{{ route('admin.lang.index', [$currantLang]) }}"
                                        class="dropdown-item border-top py-2 text-primary">{{ __('Manage Languages') }}
                                    </a>
                                @endpermission
                            @endif
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>