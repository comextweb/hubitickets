@php
    $color = !empty($settings['color']) ? $settings['color'] : 'theme-3';
    if (isset($settings['color_flag']) && $settings['color_flag'] == 'true') {
        $themeColor = 'custom-color';
    } else {
        $themeColor = $color;
    }

    $lang = app()->getLocale();
    if ($lang == 'ar' || $lang == 'he') {
        $site_rtl = 'on';
    } else {
        $site_rtl = isset($settings['site_rtl']) ? $settings['site_rtl'] : 'off';
    }

@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $site_rtl == 'on' ? 'rtl' : '' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Dashboard Template Description" />
    <meta name="keywords" content="Dashboard Template" />
    <meta name="author" content="WorkDo" />

    <meta name="title" content="{{ isset($settings['meta_keywords']) ? $settings['meta_keywords'] : 'TicketGo' }}">
    <meta name="description"
        content="{{ isset($settings['meta_description']) ? $settings['meta_description'] : 'TicketGo – The Support Ticket System is an adequately designed ticket-managing PHP system that facilitates a great user experience for your Clients / Customers / End-User.' }}">
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ env('APP_URL') }}">
    <meta property="og:title"
        content="{{ isset($settings['meta_keywords']) ? $settings['meta_keywords'] : 'TicketGo' }}">
    <meta property="og:description"
        content="{{ isset($settings['meta_description']) ? $settings['meta_description'] : 'TicketGo – The Support Ticket System is an adequately designed ticket-managing PHP system that facilitates a great user experience for your Clients / Customers / End-User.' }}">
    <meta property="og:image"
        content="{{ isset($settings['meta_image']) && checkFile($settings['meta_image']) ? getFile($settings['meta_image']) : getFile('uploads/metaevent/meta.png') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ env('APP_URL') }}">
    <meta property="twitter:title"
        content="{{ isset($settings['meta_keywords']) ? $settings['meta_keywords'] : 'TicketGo' }}">
    <meta property="twitter:description"
        content="{{ isset($settings['meta_description']) ? $settings['meta_description'] : 'TicketGo – The Support Ticket System is an adequately designed ticket-managing PHP system that facilitates a great user experience for your Clients / Customers / End-User.' }}">
    <meta property="twitter:image"
        content="{{ isset($settings['meta_image']) && checkFile($settings['meta_image']) ? getFile($settings['meta_image']) : getFile('uploads/metaevent/meta.png') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('page-title') - {{ isset($settings['app_name']) ? $settings['app_name'] : config('app.name') }}
    </title>
    <!-- Favicon -->
    <link rel="icon"
        href="{{ !empty($settings['favicon']) && checkFile($settings['favicon']) ? getFile($settings['favicon']) : getFile('uploads/logo/favicon.png') }}{{ '?' . time() }}"
        type="image/x-icon" />

    <!-- font css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">
    <style>
        :root {
            --color-customColor: <?=$color ?>;
        }

        :root {
            --support-svg-clr: <?=$color ?>;
        }
    </style>
    <!-- vendor css -->

    <link rel="stylesheet" href="{{ asset('css/custom-color.css') }}">


    @if (isset($settings['cust_darklayout']) && $settings['cust_darklayout'] == 'on')
        @if ($site_rtl == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}" id="main-style-link">
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark-rtl.css') }}" id="main-style-link"> 
        @else
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
        @endif
    @else
        @if ($site_rtl == 'on')
            <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}" id="main-style-link">
        @else
            <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
        @endif
    @endif


    @if ($site_rtl == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/custom-auth-rtl.css') }}" id="main-style-link">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/custom-auth.css') }}" id="main-style-link">
    @endif
    @if (isset($settings['cust_darklayout']) && $settings['cust_darklayout'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/custom-dark.css') }}" id="main-style-link">
    @endif

    @if (isset($settings['cust_darklayout']) && $settings['cust_darklayout'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/custom-dark.css') }}" id="main-style-link">
        <script>
            document.addEventListener('DOMContentLoaded', (event) => {
                const recaptcha = document.querySelector('.g-recaptcha');
                recaptcha.setAttribute("data-theme", "dark");
            });
        </script>
    @endif
    @stack('css-page')

    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <style type="text/css">
        img.navbar-brand-img {
            width: 245px;
            height: 61px;
        }
    </style>
</head>




<body class="{{ $themeColor }} ticket-page-sec">

    @yield('content')

    <!-- Required Js -->
    <script src="{{ asset('assets/js/vendor-all.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="https://js.pusher.com/5.0/pusher.min.js"></script>


    <script>
        feather.replace();
        var pctoggle = document.querySelector("#pct-toggler");
        if (pctoggle) {
            pctoggle.addEventListener("click", function() {
                if (
                    !document.querySelector(".pct-customizer").classList.contains("active")
                ) {
                    document.querySelector(".pct-customizer").classList.add("active");
                } else {
                    document.querySelector(".pct-customizer").classList.remove("active");
                }
            });
        }
        var themescolors = document.querySelectorAll(".themes-color > a");
        for (var h = 0; h < themescolors.length; h++) {
            var c = themescolors[h];

            c.addEventListener("click", function(event) {
                var targetElement = event.target;
                if (targetElement.tagName == "SPAN") {
                    targetElement = targetElement.parentNode;
                }
                var temp = targetElement.getAttribute("data-value");
                removeClassByPrefix(document.querySelector("body"), "theme-");
                document.querySelector("body").classList.add(temp);
            });
        }

        function removeClassByPrefix(node, prefix) {
            for (let i = 0; i < node.classList.length; i++) {
                let value = node.classList[i];
                if (value.startsWith(prefix)) {
                    node.classList.remove(value);
                }
            }
        }
    </script>


    <script>
        function show_toastr(title, message, type) {

            var f = document.getElementById('liveToast');
            var a = new bootstrap.Toast(f).show();

            if (type == 'success') {
                $('#liveToast').addClass('bg-primary');
            } else {
                $('#liveToast').addClass('bg-danger');
            }
            $('#liveToast .toast-body').html(message);
        }
    </script>



    @stack('scripts')

    {{-- Toaster Checker --}}
    @if ($message = Session::get('success'))
        <script>
            show_toastr('Success', '{!! $message !!}', 'success');
        </script>
    @endif
    @if ($message = Session::get('error'))
        <script>
            show_toastr('Error', '{!! $message !!}', 'error');
        </script>
    @endif

</body>
@if (isset($settings['enable_cookie']) && $settings['enable_cookie'] == 'on')
    @extends('layouts.cookie_consent')
@endif

</html>
