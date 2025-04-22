<nav class="navbar navbar-expand-md navbar-dark default">
    <div class="container-fluid pe-2">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ getFile(getSidebarLogo()) }}{{ '?' . time() }}" alt="logo" style="width:150px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01"
            aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <ul class="navbar-nav align-items-center ms-auto mb-2 mb-lg-0">
                @if(!moduleIsActive('CustomerLogin'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">{{ __('Create Ticket') }}</a>
                </li>
                @endif

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('search') }}">{{ __('Search Ticket') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                <li class="nav-item">
                    @if (isset($settings['faq']) && $settings['faq'] == 'on')
                        <a class="nav-link" href="{{ route('faq') }}">{{ __('FAQ') }}</a>
                    @endif
                </li>
                <li class="nav-item">
                    @if (isset($settings['knowledge_base']) && $settings['knowledge_base'] == 'on')
                        <a class="nav-link" href="{{ route('knowledge') }}">{{ __('Knowledge') }}</a>
                    @endif
                </li>
                @yield('language-bar')
            </ul>
        </div>
    </div>
</nav>
