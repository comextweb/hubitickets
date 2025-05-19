<div class="menu-overlay"></div>

<header class="custom-header">

    <ul class="main-nav d-flex gap-3 list-unstyled mb-0 align-items-center flex-1">
        @if(!moduleIsActive('CustomerLogin'))
            <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('home') }}">{{ __('Create Ticket') }}</a>
            </li>
        @endif

        <li class="nav-item {{ request()->routeIs('search') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('search') }}">{{ __('Search Ticket') }}</a>
        </li>
        <li class="nav-item {{ request()->routeIs('faq') ? 'active' : '' }}">
            @if (isset($settings['faq']) && $settings['faq'] == 'on')
                <a class="nav-link" href="{{ route('faq') }}">{{ __('FAQ') }}</a>
            @endif
        </li>
        <li class="nav-item {{ request()->routeIs('knowledge') ? 'active' : '' }}">
            @if (isset($settings['knowledge_base']) && $settings['knowledge_base'] == 'on')
                <a class="nav-link" href="{{ route('knowledge') }}">{{ __('Knowledge') }}</a>
            @endif
        </li>
    </ul>


    <div class="logo-col">
        <a class="d-block" href="{{ route('home') }}">
            <img src="{{ getFile(getSidebarLogo()) }}{{ '?' . time() }}" alt="logo">
        </a>
    </div>

    <div class="right-nav d-flex gap-3 align-items-center justify-content-end flex-1">
        @yield('language-bar')
        <a class="login-btn" href="{{ route('login') }}">{{ __('Login') }}</a>
        <div class="mobile-menu-btn">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_9608_1017)">
                    <path
                        d="M23 12.9785H1C0.447998 12.9785 0 12.5305 0 11.9785C0 11.4265 0.447998 10.9785 1 10.9785H23C23.552 10.9785 24 11.4265 24 11.9785C24 12.5305 23.552 12.9785 23 12.9785Z"
                        fill="black" />
                    <path
                        d="M23 5.3125H1C0.447998 5.3125 0 4.8645 0 4.3125C0 3.7605 0.447998 3.3125 1 3.3125H23C23.552 3.3125 24 3.7605 24 4.3125C24 4.8645 23.552 5.3125 23 5.3125Z"
                        fill="black" />
                    <path
                        d="M23 20.6465H1C0.447998 20.6465 0 20.1985 0 19.6465C0 19.0945 0.447998 18.6465 1 18.6465H23C23.552 18.6465 24 19.0945 24 19.6465C24 20.1985 23.552 20.6465 23 20.6465Z"
                        fill="black" />
                </g>
                <defs>
                    <clipPath id="clip0_9608_1017">
                        <rect width="24" height="24" fill="white" />
                    </clipPath>
                </defs>
            </svg>
        </div>
    </div>

</header>

<div class="mobile-menu-wrapper">
    <div class="close-menu mb-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M0.417521 14.0519C-0.00552023 14.4751 -0.00545505 15.1609 0.417652 15.584C0.840758 16.007 1.52668 16.007 1.94971 15.5838L8.00039 9.53213L14.0516 15.5833C14.4746 16.0063 15.1606 16.0063 15.5836 15.5833C16.0067 15.1603 16.0067 14.4743 15.5836 14.0513L9.53233 7.99997L15.5832 1.94806C16.0061 1.52495 16.0061 0.839027 15.583 0.415996C15.1599 -0.00704598 14.474 -0.00698131 14.0509 0.416125L8.00017 6.46792L1.94898 0.416646C1.52591 -0.0064178 0.839978 -0.0064178 0.416915 0.416646C-0.00615917 0.83972 -0.00615917 1.52564 0.416915 1.94872L6.46834 8.00008L0.417521 14.0519Z"
                fill="#1D2627"></path>
        </svg>
    </div>
    <ul class="d-flex gap-3 list-unstyled mb-0 flex-column">
        @yield('language-bar')
        @if(!moduleIsActive('CustomerLogin'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('home') }}">{{ __('Create Ticket') }}</a>
            </li>
        @endif

        <li class="nav-item">
            <a class="nav-link" href="{{ route('search') }}">{{ __('Search Ticket') }}</a>
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
    </ul>

</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
        const closeMenuBtn = document.querySelector('.close-menu');
        const mobileMenuWrapper = document.querySelector('.mobile-menu-wrapper');
        const menuOverlay = document.querySelector('.menu-overlay');

        function openMenu() {
            mobileMenuWrapper.classList.add('active_menu');
            menuOverlay.classList.add('active');
            document.body.classList.add('no-scroll'); // prevent background scroll
        }

        function closeMenu() {
            mobileMenuWrapper.classList.remove('active_menu');
            menuOverlay.classList.remove('active');
            document.body.classList.remove('no-scroll'); // allow scroll again
        }

        mobileMenuBtn.addEventListener('click', openMenu);
        closeMenuBtn.addEventListener('click', closeMenu);
        menuOverlay.addEventListener('click', closeMenu);

        window.addEventListener('resize', function () {
            closeMenu();
        });
    });

</script>