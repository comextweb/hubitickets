@php
use App\Models\Utility;
use App\Models\Languages;
$setting = getCompanyAllSettings();

$color = !empty($setting['color']) ? $setting['color'] : 'theme-3';
if (isset($setting['color_flag']) && $setting['color_flag'] == 'true') {
$themeColor = 'custom-color';
} else {
$themeColor = $color;
}
$currantLang = isset(Auth::user()->lang) ? Auth::user()->lang : 'en';
$language = Languages::where('code', $currantLang)->first();
$SITE_RTL = isset($setting['site_rtl']) ? $setting['site_rtl'] : 'off';
$customThemeBackground = isset($setting['cust_theme_bg']) ? $setting['cust_theme_bg'] : 'off';
@endphp


<!DOCTYPE html>
<html lang="{{ Auth::user()->lang }}" dir="{{ $SITE_RTL == 'on' ? 'rtl' : '' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Dashboard Template Description" />
    <meta name="keywords" content="Dashboard Template" />
    <meta name="author" content="WorkDo" />

    <meta name="title" content="{{ isset($setting['meta_keywords']) ? $setting['meta_keywords'] : 'TicketGo' }}">
    <meta name="description"
        content="{{ isset($setting['meta_description']) ? $setting['meta_description'] : 'TicketGo – The Support Ticket System is an adequately designed ticket-managing PHP system that facilitates a great user experience for your Clients / Customers / End-User.' }}">
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ env('APP_URL') }}">
    <meta property="og:title" content="{{ isset($setting['meta_keywords']) ? $setting['meta_keywords'] : 'TicketGo' }}">
    <meta property="og:description"
        content="{{ isset($setting['meta_description']) ? $setting['meta_description'] : 'TicketGo – The Support Ticket System is an adequately designed ticket-managing PHP system that facilitates a great user experience for your Clients / Customers / End-User.' }}">
    <meta property="og:image"
        content="{{ isset($setting['meta_image']) && checkFile($setting['meta_image']) ? getFile($setting['meta_image']) : getFile('uploads/metaevent/meta.png') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ env('APP_URL') }}">
    <meta property="twitter:title"
        content="{{ isset($setting['meta_keywords']) ? $setting['meta_keywords'] : 'TicketGo' }}">
    <meta property="twitter:description"
        content="{{ isset($setting['meta_description']) ? $setting['meta_description'] : 'TicketGo – The Support Ticket System is an adequately designed ticket-managing PHP system that facilitates a great user experience for your Clients / Customers / End-User.' }}">
    <meta property="twitter:image"
        content="{{ isset($setting['meta_image']) && checkFile($setting['meta_image']) ? getFile($setting['meta_image']) : getFile('uploads/metaevent/meta.png') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>
        @yield('page-title') - {{ isset($setting['app_name']) ? $setting['app_name'] : config('app.name') }}
    </title>

    <link rel="shortcut icon"
        href="{{ isset($setting['favicon']) && checkFile($setting['favicon']) ? getFile($setting['favicon']) : getFile('uploads/logo/favicon.png') }}{{ '?' . time() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/style.css') }}">

    <!-- font css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">
    <style>
        :root {
            --color-customColor: <?= $color ?>;
        }

        :root {
            --support-svg-clr: <?= $color ?>;
        }
    </style>
    <!-- vendor css -->

    <link rel="stylesheet" href="{{ asset('css/custom-color.css') }}">

    @if ($SITE_RTL == 'on' && isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'off')
    <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}" id="main-style-link">

    @elseif (isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on' && $SITE_RTL == 'off')
    <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}" id="main-style-link">
    <style>
        :root {
            --color-customColor: <?= $color ?>;
        }
    </style>
    @elseif (isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on' && $SITE_RTL == 'on')
    <link rel="stylesheet" href="{{ asset('assets/css/style-dark-rtl.css') }}" id="main-style-link">
    @else
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    @endif



    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">

    <!-- switch button -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/bootstrap-switch-button.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/libs/select2/dist/css/select2.min.css') }}">
    @stack('css-page')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

</head>

<body class="{{ $themeColor }}">

    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    @include('admin.partials.sidebar')


    @include('admin.partials.topnav')

    <div class="modal fade" id="commonModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commonModal"></h5>
                    <a type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></a>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="commonModalOver" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commonModal"></h5>
                    <a type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></a>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>



    <div class="dash-container">
        <div class="dash-content">
            <div class="page-header">
                <div class="page-block d-flex align-items-center justify-content-between gap-3 flex-wrap">
                    <div class="page-header-wrp">
                        <div class="page-header-title">
                            @if (trim($__env->yieldContent('page-title')))
                            <h4 class="mb-0 h4 f-w-500">@yield('page-title')</h4>
                            @endif
                        </div>
                        <ul class="breadcrumb">
                            @yield('breadcrumb')
                        </ul>
                    </div>
                    <div class="page-header-icon d-flex flex-wrap gap-2">
                        @if (trim($__env->yieldContent('action-button')))
                        @yield('action-button')
                        @elseif(trim($__env->yieldContent('multiple-action-button')))
                        @yield('multiple-action-button')
                        @endif
                    </div>
                </div>
            </div>

            @yield('content')
            {{-- <div class="loader-wrapper d-none">
                <span class="site-loader"> </span>
            </div> --}}

        </div>
    </div>

    @include('admin.partials.footer')
    <script src="{{ asset('js/letter.avatar.js') }}"></script>
    <script src="{{ asset('js/moment.js') }}"></script>
    <script src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/dash.js') }}"></script>
    <script src="{{ asset('assets/js/sidebar.js') }}"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('public/libs/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="https://js.pusher.com/5.0/pusher.min.js"></script>
    <script src="{{ asset('js/jquery.form.js') }}"></script>




    <script src="{{ asset('assets/js/plugins/sweetalert2.all.min.js') }}"></script>

    <script src="{{ asset('js/fire.modal.js') }}"></script>

    <script src="{{ asset('assets/js/plugins/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>

    <script>
        if ($('#pc-dt-simple').length) {
            const dataTable = new simpleDatatables.DataTable("#pc-dt-simple");
        }
    </script>

    <script src="{{ asset('public/libs/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <!-- switch button -->
    <script src="{{ asset('assets/js/plugins/bootstrap-switch-button.min.js') }}"></script>



    <script>
        var date_picker_locale = {
            format: 'YYYY-MM-DD',
            daysOfWeek: [
                "{{ __('Sun') }}",
                "{{ __('Mon') }}",
                "{{ __('Tue') }}",
                "{{ __('Wed') }}",
                "{{ __('Thu') }}",
                "{{ __('Fri') }}",
                "{{ __('Sat') }}"
            ],
            monthNames: [
                "{{ __('January') }}",
                "{{ __('February') }}",
                "{{ __('March') }}",
                "{{ __('April') }}",
                "{{ __('May') }}",
                "{{ __('June') }}",
                "{{ __('July') }}",
                "{{ __('August') }}",
                "{{ __('September') }}",
                "{{ __('October') }}",
                "{{ __('November') }}",
                "{{ __('December') }}"
            ],
        };
        var calender_header = {
            today: "{{ __('today') }}",
            month: "{{ __(' month ') }}",
            week: "{{ __('week ') }}",
            day: "{{ __('day ') }}",
            list: "{{ __('list ') }}"
        };
    </script>

    <script>
        var dataTableLang = {
            paginate: {
                previous: "<i class='fas fa-angle-left'>",
                next: "<i class='fas fa-angle-right'>"
            },
            lengthMenu: "{{ __('Show') }} _MENU_ {{ __('entries') }}",
            zeroRecords: "{{ __('No data available in table.') }}",
            info: "{{ __('Showing') }} _START_ {{ __('to') }} _END_ {{ __('of') }} _TOTAL_ {{ __('entries') }}",
            infoEmpty: "{{ __('Showing 0 to 0 of 0 entries') }}",
            infoFiltered: "{{ __('(filtered from _MAX_ total entries)') }}",
            search: "{{ __('Search:') }}",
            thousands: ",",
            loadingRecords: "{{ __('Loading...') }}",
            processing: "{{ __('Processing...') }}"
        }
    </script>


    @if (isset($setting['gdpr_cookie']) && $setting['gdpr_cookie'] == 'on')
    <script type="text/javascript">
        var defaults = {
            'messageLocales': {
                /*'en': 'We use cookies to make sure you can have the best experience on our website. If you continue to use this site we assume that you will be happy with it.'*/
                'en': "{{ $setting['cookie_text'] }}"
            },
            'buttonLocales': {
                'en': 'Ok'
            },
            'cookieNoticePosition': 'bottom',
            'learnMoreLinkEnabled': false,
            'learnMoreLinkHref': '/cookie-banner-information.html',
            'learnMoreLinkText': {
                'it': 'Saperne di più',
                'en': 'Learn more',
                'de': 'Mehr erfahren',
                'fr': 'En savoir plus'
            },
            'buttonLocales': {
                'en': 'Ok'
            },
            'expiresIn': 30,
            'buttonBgColor': '#d35400',
            'buttonTextColor': '#fff',
            'noticeBgColor': '#000000',
            'noticeTextColor': '#fff',
            'linkColor': '#009fdd'
        };
    </script>
    <script src="{{ asset('js/cookie.notice.js') }}"></script>
    @endif

    <script>

    </script>

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


    @stack('scripts')
    @if (Session::has('success'))
        <script>
            show_toastr('{{ __('Success') }}', '{!! session('success') !!}', 'success');
        </script>
    @endif
    @if (Session::has('error'))
        <script>
            show_toastr('{{ __('Error') }}', '{!! session('error') !!}', 'error');
        </script>
    @endif
</body>
@include('layouts.cookie_consent')

</html>