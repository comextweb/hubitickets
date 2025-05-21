<header class="{{ $customThemeBackground == 'on' ? 'dash-header transprent-bg' : 'dash-header' }}">
    <div class="header-wrapper d-flex align-items-center justify-content-between gap-3">
        <div class="header-user-col d-flex align-items-center gap-3">
            <div class="sidebar-toggle">
                <a href="javascript:" class="sidebar-toggle-btn" id="sidebar-toggle-btn">
                    <svg class="sidebar-open" width="1200pt" height="1200pt" version="1.1" viewBox="0 0 1200 1200" xmlns="http://www.w3.org/2000/svg">
                     <path d="m621.98 776.55c0-0.035157 0.023437-0.085938 0.023437-0.12109-0.19141 0.039062-0.25 0.074218-0.023437 0.12109z"/>
                     <path d="m133.66 650.53 311.32 311.33c26.785 26.773 70.223 26.773 96.973 0 26.785-26.762 26.785-70.199 0-96.984l-194.25-194.25h659.04c37.871 0 68.578-30.707 68.578-68.578 0-37.871-30.707-68.578-68.578-68.578h-659.04l194.26-194.26c26.785-26.762 26.785-70.211 0-96.984-13.379-13.379-30.949-20.09-48.492-20.09-17.543 0-35.113 6.707-48.492 20.09l-311.32 311.32c-26.797 26.773-26.797 70.199 0 96.984z"/>
                    </svg>
                    <svg class="sidebar-close" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.6967 12L18.8482 6.84849C19.317 6.37974 19.317 5.62036 18.8482 5.15161C18.3795 4.68286 17.6201 4.68286 17.1514 5.15161L11.9998 10.3032L6.84824 5.15161C6.37949 4.68286 5.62012 4.68286 5.15137 5.15161C4.68262 5.62036 4.68262 6.37974 5.15137 6.84849L10.3029 12L5.15137 17.1516C4.68262 17.6204 4.68262 18.3797 5.15137 18.8485C5.38574 19.0829 5.69277 19.2 5.9998 19.2C6.30684 19.2 6.61387 19.0829 6.84824 18.8485L11.9998 13.6969L17.1514 18.8485C17.3857 19.0829 17.6928 19.2 17.9998 19.2C18.3068 19.2 18.6139 19.0829 18.8482 18.8485C19.317 18.3797 19.317 17.6204 18.8482 17.1516L13.6967 12Z" fill="black" />
                    </svg>

                </a>
            </div>
            <ul class="list-unstyled d-flex align-items-center mb-0 gap-2">
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
                                class="h-100 w-100 rounded-circle header-avatar">

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
        <ul class="list-unstyled mb-0">
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
</header>