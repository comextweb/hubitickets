@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Conversations') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item mt-1"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item mt-1" style="color: #293240">{{ __('Conversations') }}</li>
@endsection
@php
    $setting = getCompanyAllSettings();
    $SITE_RTL = isset($setting['site_rtl']) ? $setting['site_rtl'] : 'off';
@endphp
@push('css-page')
    @if ($SITE_RTL == 'on')
        <link rel="stylesheet" href="{{ asset('css/rtl-main-style.css') }}">
        <link rel="stylesheet" href="{{ asset('css/rtl-responsive.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('css/main-style.css') }}">
        <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    @endif
    @if (isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on')
        <link rel="stylesheet" href="{{ asset('css/main-style-dark.css') }}">
    @endif
    <link rel="stylesheet" href="{{ asset('css/custom-color.css') }}">
    <link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
@endpush

@section('multiple-action-button')
    {{-- Add Button Hook --}}
    @stack('addButtonHook')
    <button id="filterTickets" class="btn btn-sm btn-primary me-2" data-bs-toggle="tooltip" title="{{ __('Filter') }}"><i
            class="ti ti-filter"></i></button>
    @permission('ticket export')
    <div class="btn btn-sm btn-primary btn-icon me-2" data-bs-toggle="tooltip" data-bs-placement="top"
        title="{{ __('Export Tickets CSV file') }}">
        <a href="{{ route('tickets.export') }}" class=""><i class="ti ti-file-export text-white"></i></a>
    </div>
    @endpermission
    @if (!Auth::user()->hasRole('customer'))
        @permission('ticket create')
        <div class="btn btn-sm btn-primary btn-icon float-end " data-bs-toggle="tooltip" data-bs-placement="top"
            title="{{ __('Create Ticket') }}">
            <a href="{{ route('admin.tickets.create') }}" class=""><i class="ti ti-plus text-white"></i></a>
        </div>
        @endpermission
    @endif
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12" id="showTicketFilter" style="display:none;">
            <div class="mt-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.new.chat') }}" id="filter_ticket" method="GET">
                            <div class="row align-items-center justify-content-end">
                                <div class="col-xl-10">
                                    <div class="row row-gap justify-content-end">
                                        @stack('filter_tags')
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="btn-box">
                                                <label class="form-label text-dark">{{ __('Priority') }}</label>
                                                <select class="form-control" name="priority">
                                                    <option value="">{{ __('Select Priority') }}</option>
                                                    @foreach ($priorities as $priority)
                                                        <option value="{{ $priority->id }}" {{ request('priority') == $priority->id ? 'selected' : '' }}>
                                                            {{ $priority->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="btn-box">
                                                <label class="form-label text-dark">{{ __('Status') }}</label>
                                                <select class="form-control" name="status">
                                                    <option value="">{{ __('Select Status') }}</option>
                                                    <option value="New Ticket" {{ request('status') === 'New Ticket' ? 'selected' : '' }}>
                                                        {{ __('New Ticket') }}
                                                    </option>
                                                    <option value="In Progress" {{ request('status') === 'In Progress' ? 'selected' : '' }}>
                                                        {{ __('In Progress') }}
                                                    </option>
                                                    <option value="On Hold" {{ request('status') === 'On Hold' ? 'selected' : '' }}>
                                                        {{ __('On Hold') }}
                                                    </option>
                                                    <option value="Closed" {{ request('status') === 'Closed' ? 'selected' : '' }}>
                                                        {{ __('Closed') }}
                                                    </option>
                                                    <option value="Resolved" {{ request('status') === 'Resolved' ? 'selected' : '' }}>
                                                        {{ __('Resolved') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="row">
                                        <div class="col-auto mt-4 d-flex gap-2">
                                            <a href="#" class="btn btn-sm btn-primary"
                                                onclick="document.getElementById('filter_ticket').submit(); return false;"
                                                data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                                data-original-title="{{ __('apply') }}">
                                                <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                            </a>
                                            <a href="{{ route('admin.new.chat') }}" class="btn btn-sm btn-danger "
                                                data-bs-toggle="tooltip" title="{{ __('Reset') }}"
                                                data-original-title="{{ __('Reset') }}">
                                                <span class="btn-inner--icon"><i
                                                        class="ti ti-refresh text-white-off "></i></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    @if (Auth::user()->hasRole('admin') && isset($settings['CHAT_MODULE']) && $settings['CHAT_MODULE'] == 'no')
        <div class="alert alert-group alert-danger fade show alert-icon mt-3 gap-2" role="alert">
            <div class="alert-content">
                <p>{{ __('For real time chatting add your pusher key. Click here to Add your pusher key ') }} <span> <a
                            href="{{ url('admin/settings#pusher-settings') }}"
                            class="text-danger"><strong>{{ __(' click here !') }}</strong></a></span></p>


            </div>
            <div class="close-alert" style="cursor: pointer">
                <i class="fas fa-times"></i>
            </div>
        </div>
    @endif
    <div class="chat-main-wrapper d-flex flex-column flex-md-row justify-content-center">
        <div class="chat-wrapper-left">
            <div class="chat-header-left">
                <div class="chat-header-left-wrp d-flex align-items-center justify-content-between">
                    <div class="select-wrp">
                        <select name="type" id="tikcettype">
                            <option value="">{{ __('All Tickets') }}</option>
                            @foreach ($tikcettype as $item)
                                <option {{ isset($_GET['type']) && $_GET['type'] == $item ? 'selected' : '' }}
                                    value="{{ $item }}">{{ $item }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-wrp">
                        <input type="text" id="myInput" class="form-control" onkeyup="myFunction()"
                            placeholder="Search Ticket Number" title="Type in a name">
                        <button class="search-btn" id="searchToggle">
                            <svg width="28" height="26" viewBox="0 0 28 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_812_12464)">
                                    <path
                                        d="M27.0865 23.9886L20.2816 17.5434C22.0636 15.68 23.1585 13.2155 23.1585 10.5035C23.1576 4.70222 18.1931 0 12.0686 0C5.94403 0 0.979492 4.70222 0.979492 10.5035C0.979492 16.3048 5.94403 21.0071 12.0686 21.0071C14.7148 21.0071 17.1418 20.1261 19.0483 18.6615L25.8797 25.132C26.2125 25.4476 26.7529 25.4476 27.0858 25.132C27.4194 24.8165 27.4194 24.3042 27.0865 23.9886ZM12.0686 19.391C6.88654 19.391 2.6857 15.412 2.6857 10.5035C2.6857 5.59509 6.88654 1.61603 12.0686 1.61603C17.2506 1.61603 21.4514 5.59509 21.4514 10.5035C21.4514 15.412 17.2506 19.391 12.0686 19.391Z"
                                        fill="#060606"></path>
                                </g>
                                <defs>
                                    <clipPath id="clip0_812_12464">
                                        <rect width="26.3571" height="25.3687" fill="white" transform="translate(0.979004)">
                                        </rect>
                                    </clipPath>
                                </defs>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            {{-- <div class="chat-left-body"> --}}
                {{-- Chat Pin addon --}}
                @php
                    if (isset($isChatPinEnabled) && $isChatPinEnabled) {
                        $pinnedTickets = $tickets->filter(function ($ticket) {
                            return $ticket->is_pin == 1;
                        });
                        $otherTickets = $tickets->filter(function ($ticket) {
                            return $ticket->is_pin == 0;
                        });
                        $sortedTickets = $pinnedTickets->merge($otherTickets)->slice(0, 10);
                    } else {
                        $sortedTickets = $tickets->slice(0, 10);
                    }
                @endphp
                <ul class="user" id="myUL">
                    @foreach ($sortedTickets as $ticket)
                        <li class="nav-item user_chat" id="{{ $ticket->id }}">
                            <div class="social-chat">
                                <div class="social-chat-img chat_users_img ">
                                    @if ($ticket->type == 'Instagram' && !empty($isInstagramChat))
                                        @include('instagram-chat::instagram.profile')
                                    @elseif($ticket->type == 'Facebook' && !empty($isFacebookChat))
                                        @include('facebook-chat::facebook.profile')
                                    @endif
                                    <img alt="{{ $ticket->name }}" class="img-fluid " avatar="{{ $ticket->name }}">
                                </div>
                                @php
                                    $messege = $ticket->unreadMessge($ticket->id)->count();
                                @endphp
                                <div class="user-info flex-1">
                                    <span
                                        class="user-name chat_users_{{ $ticket->id }} {{ isset($ticket->is_mark) && $ticket->is_mark == 1 ? 'ticket-danger' : '' }}">{{ $ticket->name }}</span>
                                    <p class="chat-user {{ $messege > 0 ? 'not-read' : '' }}" id="not_read_{{ $ticket->id }}">
                                        {{ $ticket->latestMessages($ticket->id) }}
                                    </p>
                                </div>
                                <input type="hidden" class="ticket_subject" value="{{ $ticket->subject }}">
                                <input type="hidden" class="ticket_category"
                                    value="{{ isset($ticket->getCategory) ? $ticket->getCategory->name : '---' }}">
                                <input type="hidden" class="ticket_priority"
                                    value="{{ isset($ticket->getPriority) ? $ticket->getPriority->name : '---' }}">
                                <input type="hidden" class="ticket_category_color"
                                    value="{{ isset($ticket->getCategory) ? $ticket->getCategory->color : '---' }}">
                                <input type="hidden" class="ticket_priority_color"
                                    value="{{ isset($ticket->getPriority) ? $ticket->getPriority->color : '---' }}">
                                <input type="hidden" class="ticket_status"
                                    value="{{ isset($ticket->status) ? $ticket->status : '---' }}">
                                @if (isset($isTags) && $isTags)
                                    @foreach ($ticket->getTagsAttribute() as $tag)
                                        <input type="hidden" class="ticket_tag_color"
                                            value="{{ isset($tag->color) ? $tag->color : '---' }}">
                                    @endforeach
                                @endif
                                @if (isset($isMarkAsImportant) && $isMarkAsImportant)
                                    <input type="hidden" class="ticket_mark_important"
                                        value="{{ isset($ticket->is_mark) ? $ticket->is_mark : '---' }}">
                                @endif
                                @if (isset($isChatPinEnabled) && $isChatPinEnabled)
                                    <input type="hidden" class="ticket_chat_pin"
                                        value="{{ isset($ticket->is_pin) ? $ticket->is_pin : '---' }}">
                                @endif
                                <div class="chat-pin-icon">
                                    @if (isset($isChatPinEnabled) && $isChatPinEnabled)
                                        @if (isset($ticket) && $ticket->is_pin == 1)
                                            <svg id="chatPin" class="unpin-svg" width="16" height="16" viewBox="0 0 16 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <g clip-path="url(#clip0_1447_2876)">
                                                    <path
                                                        d="M11.1096 2.58333L4.88706 2.58333C4.60422 2.58333 4.39209 2.79546 4.39209 3.07831L4.39209 5.34105C4.39209 5.6946 4.67493 5.83602 4.88706 5.83602L5.38204 5.83602L5.38204 7.95734C4.74564 8.38161 3.04859 9.58369 3.04859 11.1393C3.04859 11.4222 3.26072 11.6343 3.54356 11.6343L7.57407 11.6343L7.57407 16.0891C7.57407 16.3719 7.7862 16.584 8.06905 16.584C8.35189 16.584 8.56402 16.3012 8.56402 16.0891L8.56402 11.6343L12.5945 11.6343C12.9834 11.5989 13.1249 11.3161 13.0895 11.1393C13.0895 9.58369 11.3924 8.38161 10.7561 7.95734V5.83602H11.251C11.5339 5.83602 11.746 5.62389 11.746 5.34105L11.746 3.07831C11.6046 2.79546 11.3924 2.58333 11.1096 2.58333ZM10.6146 4.84607L10.1197 4.84607C9.83681 4.84607 9.62468 5.0582 9.62468 5.34105L9.66004 8.27554C9.66004 8.48767 9.73075 8.62909 9.90752 8.73516C10.4025 9.018 11.5339 9.79582 11.8874 10.7151L4.10925 10.7151C4.42744 9.83118 5.59417 9.018 6.08915 8.73516C6.23057 8.66445 6.33663 8.48767 6.33663 8.27554L6.33663 5.3764C6.33663 4.95214 6.05379 4.88143 5.84166 4.88143L5.34668 4.88143L5.38204 3.57328L10.6146 3.57328L10.6146 4.84607Z"
                                                        fill="black" />
                                                    <rect y="4" width="1" height="17.7814" rx="0.5" transform="rotate(-60 0 4)"
                                                        fill="black" />
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_1447_2876">
                                                        <rect width="16" height="16" fill="white" />
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        @endif
                                    @endif
                                </div>
                                <div class="social-icon-wrp">
                                    @if ($ticket->type == 'Whatsapp')
                                        <a href="javascript:;">
                                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <g clip-path="url(#clip0_51_375)">
                                                    <path
                                                        d="M6 12C9.31371 12 12 9.31371 12 6C12 2.68629 9.31371 0 6 0C2.68629 0 0 2.68629 0 6C0 9.31371 2.68629 12 6 12Z"
                                                        fill="#29A71A" />
                                                    <path
                                                        d="M8.6454 3.35454C8.02115 2.72406 7.19214 2.33741 6.30792 2.26433C5.4237 2.19125 4.54247 2.43654 3.82318 2.95598C3.10388 3.47541 2.59389 4.23478 2.38518 5.09712C2.17647 5.95946 2.28278 6.868 2.68494 7.65886L2.29017 9.57545C2.28607 9.59452 2.28596 9.61424 2.28983 9.63336C2.2937 9.65249 2.30148 9.67061 2.31267 9.68659C2.32907 9.71084 2.35247 9.72951 2.37976 9.74011C2.40705 9.75071 2.43693 9.75273 2.4654 9.7459L4.34381 9.30068C5.13244 9.69266 6.03456 9.79214 6.88966 9.58141C7.74475 9.37068 8.49735 8.86342 9.01355 8.14988C9.52974 7.43634 9.77604 6.56281 9.70863 5.68471C9.64122 4.80662 9.26446 3.98092 8.6454 3.35454ZM8.05971 8.02909C7.6278 8.45979 7.07162 8.7441 6.46955 8.84196C5.86749 8.93981 5.24989 8.84628 4.70381 8.57454L4.44199 8.44499L3.2904 8.71772L3.29381 8.7034L3.53244 7.54431L3.40426 7.29136C3.12523 6.74336 3.0268 6.12111 3.12307 5.51375C3.21934 4.90639 3.50536 4.34508 3.94017 3.91022C4.48651 3.36405 5.22741 3.05722 5.99994 3.05722C6.77247 3.05722 7.51337 3.36405 8.05971 3.91022C8.06437 3.91556 8.06938 3.92057 8.07471 3.92522C8.61429 4.4728 8.9155 5.21149 8.91269 5.98024C8.90988 6.74899 8.60327 7.48546 8.05971 8.02909Z"
                                                        fill="white" />
                                                    <path
                                                        d="M7.95745 7.17885C7.81632 7.40112 7.59336 7.67317 7.31314 7.74067C6.82223 7.85931 6.06882 7.74476 5.13132 6.87067L5.11973 6.86044C4.29541 6.09613 4.08132 5.45999 4.13314 4.95544C4.16177 4.66908 4.40041 4.40999 4.60155 4.2409C4.63334 4.21376 4.67105 4.19443 4.71166 4.18447C4.75226 4.17451 4.79463 4.17419 4.83538 4.18353C4.87613 4.19288 4.91412 4.21162 4.94633 4.23828C4.97854 4.26493 5.00406 4.29875 5.02086 4.33703L5.32427 5.01885C5.34399 5.06306 5.3513 5.1118 5.34541 5.15985C5.33952 5.2079 5.32067 5.25344 5.29086 5.29158L5.13745 5.49067C5.10454 5.53178 5.08467 5.5818 5.08042 5.63429C5.07617 5.68678 5.08772 5.73934 5.11359 5.78522C5.1995 5.9359 5.40541 6.15749 5.63382 6.36272C5.89018 6.59453 6.1745 6.80658 6.3545 6.87885C6.40266 6.89853 6.45562 6.90333 6.50654 6.89264C6.55745 6.88194 6.604 6.85624 6.64018 6.81885L6.81814 6.63953C6.85247 6.60567 6.89517 6.58153 6.94189 6.56955C6.9886 6.55757 7.03765 6.55819 7.08405 6.57135L7.80473 6.7759C7.84448 6.78809 7.88092 6.80922 7.91126 6.83765C7.9416 6.86609 7.96503 6.90109 7.97977 6.93997C7.99451 6.97886 8.00016 7.0206 7.99629 7.062C7.99242 7.1034 7.97914 7.14337 7.95745 7.17885Z"
                                                        fill="white" />
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_51_375">
                                                        <rect width="12" height="12" fill="white" />
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        </a>
                                    @elseif($ticket->type == 'Instagram')
                                        <a href="javascript:;">
                                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <g clip-path="url(#clip0_51_298)">
                                                    <path
                                                        d="M9.23313 0H2.76687C1.23877 0 0 1.23877 0 2.76687V9.23313C0 10.7612 1.23877 12 2.76687 12H9.23313C10.7612 12 12 10.7612 12 9.23313V2.76687C12 1.23877 10.7612 0 9.23313 0Z"
                                                        fill="url(#paint0_linear_51_298)" />
                                                    <path
                                                        d="M7.93435 2.36992C8.38969 2.37175 8.82585 2.55341 9.14783 2.87531C9.46981 3.19722 9.6515 3.63329 9.65334 4.08852V7.91148C9.6515 8.36671 9.46981 8.80278 9.14783 9.12469C8.82585 9.44659 8.38969 9.62825 7.93435 9.63008H4.06565C3.61031 9.62825 3.17415 9.44659 2.85217 9.12469C2.53019 8.80278 2.3485 8.36671 2.34666 7.91148V4.08852C2.3485 3.63329 2.53019 3.19722 2.85217 2.87531C3.17415 2.55341 3.61031 2.37175 4.06565 2.36992H7.93435ZM7.93435 1.57057H4.06565C2.6804 1.57057 1.54688 2.70513 1.54688 4.08878V7.91148C1.54688 9.29642 2.68169 10.4297 4.06565 10.4297H7.93435C9.3196 10.4297 10.4531 9.29513 10.4531 7.91148V4.08852C10.4531 2.70358 9.3196 1.57031 7.93435 1.57031V1.57057Z"
                                                        fill="white" />
                                                    <path
                                                        d="M6 4.50433C6.29582 4.50433 6.58499 4.59205 6.83095 4.7564C7.07691 4.92074 7.26862 5.15433 7.38182 5.42763C7.49502 5.70093 7.52464 6.00166 7.46693 6.29179C7.40922 6.58192 7.26677 6.84842 7.0576 7.0576C6.84842 7.26677 6.58192 7.40922 6.29179 7.46693C6.00166 7.52464 5.70093 7.49502 5.42763 7.38182C5.15433 7.26861 4.92074 7.07691 4.7564 6.83095C4.59205 6.58499 4.50433 6.29581 4.50433 6C4.50481 5.60347 4.66254 5.22332 4.94293 4.94293C5.22332 4.66254 5.60347 4.50481 6 4.50433ZM6 3.70313C5.54572 3.70312 5.10164 3.83783 4.72393 4.09022C4.34621 4.3426 4.05181 4.70132 3.87797 5.12102C3.70412 5.54072 3.65863 6.00255 3.74726 6.4481C3.83589 6.89365 4.05464 7.30291 4.37587 7.62413C4.69709 7.94536 5.10635 8.16411 5.5519 8.25274C5.99745 8.34137 6.45928 8.29588 6.87898 8.12203C7.29868 7.94819 7.6574 7.65379 7.90978 7.27607C8.16217 6.89836 8.29688 6.45428 8.29688 6C8.29688 5.39083 8.05488 4.80661 7.62414 4.37586C7.19339 3.94512 6.60917 3.70313 6 3.70313Z"
                                                        fill="white" />
                                                    <path
                                                        d="M8.34375 4.14844C8.64147 4.14844 8.88281 3.90709 8.88281 3.60937C8.88281 3.31166 8.64147 3.07031 8.34375 3.07031C8.04603 3.07031 7.80469 3.31166 7.80469 3.60937C7.80469 3.90709 8.04603 4.14844 8.34375 4.14844Z"
                                                        fill="white" />
                                                </g>
                                                <defs>
                                                    <linearGradient id="paint0_linear_51_298" x1="7.86479" y1="12.5037" x2="4.13521"
                                                        y2="-0.503678" gradientUnits="userSpaceOnUse">
                                                        <stop stop-color="#FFDB73" />
                                                        <stop offset="0.08" stop-color="#FDAD4E" />
                                                        <stop offset="0.15" stop-color="#FB832E" />
                                                        <stop offset="0.19" stop-color="#FA7321" />
                                                        <stop offset="0.23" stop-color="#F6692F" />
                                                        <stop offset="0.37" stop-color="#E84A5A" />
                                                        <stop offset="0.48" stop-color="#E03675" />
                                                        <stop offset="0.55" stop-color="#DD2F7F" />
                                                        <stop offset="0.68" stop-color="#B43D97" />
                                                        <stop offset="0.97" stop-color="#4D60D4" />
                                                        <stop offset="1" stop-color="#4264DB" />
                                                    </linearGradient>
                                                    <clipPath id="clip0_51_298">
                                                        <rect width="12" height="12" fill="white" />
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        </a>
                                    @elseif($ticket->type == 'Facebook')
                                        <a href="javascript:;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 15 16"
                                                fill="url(#Ld6sqrtcxMyckEl6xeDdMa)">
                                                <g clip-path="url(#clip0_17_3195)">
                                                    <path
                                                        d="M14.8586 7.95076C14.8586 3.89299 11.5691 0.603516 7.51131 0.603516C3.45353 0.603516 0.164062 3.89299 0.164062 7.95076C0.164062 11.6179 2.85083 14.6576 6.3633 15.2088V10.0746H4.49779V7.95076H6.3633V6.33207C6.3633 4.49067 7.46022 3.47353 9.13847 3.47353C9.94207 3.47353 10.7831 3.61703 10.7831 3.61703V5.42515H9.85669C8.94402 5.42515 8.65932 5.99154 8.65932 6.57315V7.95076H10.697L10.3713 10.0746H8.65932V15.2088C12.1718 14.6576 14.8586 11.6179 14.8586 7.95076Z"
                                                        fill="#0017A8"></path>
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_17_3195">
                                                        <rect width="14.6945" height="14.6945" fill="white"
                                                            transform="translate(0.164062 0.603516)"></rect>
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        </a>
                                    @elseif($ticket->type == 'Unassigned' || $ticket->type == 'Assigned')
                                        <a href="javascript:;">
                                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <g clip-path="url(#clip0_5_426)">
                                                    <path
                                                        d="M9.59978 0.00012207H2.40013C1.76364 0.00012207 1.15322 0.252966 0.703154 0.703032C0.253088 1.1531 0.000244141 1.76352 0.000244141 2.40001V7.19977C0.000244141 7.83626 0.253088 8.44668 0.703154 8.89675C1.15322 9.34681 1.76364 9.59966 2.40013 9.59966V11.3996C2.39943 11.5187 2.43425 11.6354 2.50012 11.7347C2.566 11.834 2.65996 11.9115 2.77002 11.9572C2.88008 12.0029 3.00126 12.0148 3.1181 11.9913C3.23494 11.9679 3.34216 11.9102 3.42608 11.8255L5.64597 9.59966H9.59978C10.2363 9.59966 10.8467 9.34681 11.2968 8.89675C11.7468 8.44668 11.9997 7.83626 11.9997 7.19977V2.40001C11.9997 1.76352 11.7468 1.1531 11.2968 0.703032C10.8467 0.252966 10.2363 0.00012207 9.59978 0.00012207Z"
                                                        fill="#2675E2" />
                                                    <path
                                                        d="M3.3001 5.69985C3.79714 5.69985 4.20006 5.29692 4.20006 4.79989C4.20006 4.30286 3.79714 3.89993 3.3001 3.89993C2.80307 3.89993 2.40015 4.30286 2.40015 4.79989C2.40015 5.29692 2.80307 5.69985 3.3001 5.69985Z"
                                                        fill="#EDEBEA" />
                                                    <path
                                                        d="M6.00005 5.69985C6.49709 5.69985 6.90001 5.29692 6.90001 4.79989C6.90001 4.30286 6.49709 3.89993 6.00005 3.89993C5.50302 3.89993 5.1001 4.30286 5.1001 4.79989C5.1001 5.29692 5.50302 5.69985 6.00005 5.69985Z"
                                                        fill="#EDEBEA" />
                                                    <path
                                                        d="M8.69988 5.69985C9.19692 5.69985 9.59984 5.29692 9.59984 4.79989C9.59984 4.30286 9.19692 3.89993 8.69988 3.89993C8.20285 3.89993 7.79993 4.30286 7.79993 4.79989C7.79993 5.29692 8.20285 5.69985 8.69988 5.69985Z"
                                                        fill="#EDEBEA" />
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_5_426">
                                                        <rect width="12" height="12" fill="white" />
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        </a>
                                    @endif
                                    <span class="chat-time">{{ $ticket->created_at->diffForHumans() }}</span>
                                    {{-- @if ($ticket->unreadMessge($ticket->id)->count() > 0)
                                    <span class="notification" id="unread_notification_{{ $ticket->id }}">
                                        {{ $ticket->unreadMessge($ticket->id)->count() }}
                                    </span>
                                    @endif --}}
                                    <span
                                        class="notification {{ $ticket->unreadMessge($ticket->id)->count() == 0 ? 'd-none' : '' }}"
                                        id="unread_notification_{{ $ticket->id }}">
                                        {{ $ticket->unreadMessge($ticket->id)->count() }}
                                    </span>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
                @if ($totalticket > 10)
                    <div id="load-btn">
                        <button class="load-more-btn" id="load_more">
                            <i class="fa fa-spinner"></i>
                            <span>{{ __('Load More Conversations') }}</span>
                        </button>
                    </div>
                @endif
                {{--
            </div> --}}
        </div>
        <div class="chat-wrapper-right">
            <div class="chat-header">
                <div class="chat-header-inner">
                    <div class="header-left-col">
                        <div class="header-left-col-wrp">
                            <div class="chat-info d-flex align-items-center gap-3 mb-1">
                                <h3 class="user-name chat_head"></h3>
                                <span class="ticket-number"></span>
                            </div>
                            <span class="user-info chat_subject"></span>
                        </div>
                    </div>
                    <div class="header-right-col d-flex flex-wrap align-items-center gap-2">
                        <div class="right-select-wrp d-flex flex-wrap align-items-center gap-2">
                            {{-- ticket tags --}}
                            @stack('ticket-tags')
                            {{-- end --}}
                            {{-- mark as Important button --}}
                            @if (!Auth::user()->hasRole('customer'))
                                @stack('is_mark_as_important')
                            @endif
                            {{-- end --}}
                        </div>
                        {{-- tag button --}}
                        @if (!Auth::user()->hasRole('customer'))
                            @stack('is_chat_pin')
                            {{-- end --}}
                            <div class="right-select-wrp">
                                <select class="chat_status status_change"
                                    data-url="{{ route('admin.ticket.status.change', ['id' => isset($ticket) ? $ticket->id : '0']) }}">
                                    <option value="New Ticket">{{ __('New Ticket') }}</option>
                                    <option value="In Progress"> {{ __('In Progress') }}</option>
                                    <option value="On Hold">{{ __('On Hold') }}</option>
                                    <option value="Closed">{{ __('Closed') }}</option>
                                    <option value="Resolved">{{ __('Resolved') }}</option>
                                </select>
                            </div>
                        @endif
                        <!-- ExportConversations module start -->
                        @if (isset($isExportConversations) && $isExportConversations)
                            <div class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="{{ __('Export conversations') }}">
                                <a id="exportTicketConverstationBtn" href="#" target="_blank" class=""> <svg width="15"
                                        height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M5.025 4.26158L6.75 2.53442V9.74343C6.75 10.194 7.05 10.4944 7.5 10.4944C7.95 10.4944 8.25 10.194 8.25 9.74343V2.53442L9.975 4.26158C10.275 4.56195 10.725 4.56195 11.025 4.26158C11.325 3.9612 11.325 3.51064 11.025 3.21026L8.025 0.206508C7.95 0.131414 7.875 0.0563204 7.8 0.0563204C7.65 -0.0187735 7.425 -0.0187735 7.2 0.0563204C7.125 0.0563204 7.05 0.131414 6.975 0.206508L3.975 3.21026C3.675 3.51064 3.675 3.9612 3.975 4.26158C4.275 4.56195 4.725 4.56195 5.025 4.26158ZM14.25 8.99249C13.8 8.99249 13.5 9.29287 13.5 9.74343V12.7472C13.5 13.1977 13.2 13.4981 12.75 13.4981H2.25C1.8 13.4981 1.5 13.1977 1.5 12.7472V9.74343C1.5 9.29287 1.2 8.99249 0.75 8.99249C0.3 8.99249 0 9.29287 0 9.74343V12.7472C0 14.0238 0.975 15 2.25 15H12.75C14.025 15 15 14.0238 15 12.7472V9.74343C15 9.29287 14.7 8.99249 14.25 8.99249Z"
                                            fill="white" />
                                    </svg></a>
                            </div>
                        @endif
                        <a href="#" id="copyTicketLink" class="btn px-2 btn-sm btn-primary btn-icon cp_link" data-link="#"
                            data-toggle="tooltip" data-original-title="{{ __('Click To Copy Support Ticket Url') }}"
                            title="{{ __('Click To Copy Support Ticket Url') }}" data-bs-toggle="tooltip"
                            data-bs-placement="top">
                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M5.47974 0.62561C4.80918 0.62561 4.22266 1.0171 3.93616 1.58142C3.75676 1.88388 3.86223 2.27432 4.16931 2.44568C4.47016 2.61351 4.85018 2.50675 5.01892 2.20642C5.07694 2.00745 5.25475 1.87378 5.47974 1.87378H12.6447C12.9213 1.87378 13.1244 2.07687 13.1244 2.35352V9.52087C13.1244 9.7376 12.9995 9.90776 12.8125 9.97192C12.5122 10.1407 12.4054 10.5213 12.5732 10.8221C12.7446 11.1292 13.135 11.2347 13.4375 11.0553C13.9922 10.766 14.375 10.1838 14.375 9.52087V2.35352C14.375 1.40599 13.5922 0.62561 12.6447 0.62561H5.47974ZM2.35535 3.75183C1.40782 3.75183 0.625 4.5316 0.625 5.47913V12.6465C0.625 13.594 1.40782 14.3744 2.35535 14.3744H9.52026C10.4678 14.3744 11.2506 13.594 11.2506 12.6465V5.47913C11.2506 4.5316 10.4678 3.75183 9.52026 3.75183H2.35535ZM2.35535 4.99939H9.52026C9.79691 4.99939 10 5.20248 10 5.47913V12.6465C10 12.9231 9.79691 13.1268 9.52026 13.1268H2.35535C2.0787 13.1268 1.87561 12.9231 1.87561 12.6465V5.47913C1.87561 5.20248 2.0787 4.99939 2.35535 4.99939Z"
                                    fill="white" />
                            </svg>
                        </a>
                        @if (!Auth::user()->hasRole('customer'))
                            <div class="action-btn msg-delete-btn">
                                <form method="POST" action="#" id="user-form-{{ isset($ticket) ? $ticket->id  : '' }}" class="delete-ticket-btn">
                                    @csrf
                                    @method('DELETE')
                                    <input name="_method" type="hidden" value="DELETE">
                                    <a class="danger btn btn-sm align-items-center bs-pass-para bg-danger text-white border-0 show_confirm trigger--fire-modal-1"
                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete" aria-label="Delete"
                                        data-confirm="{{ __('Are You Sure?') }}"
                                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                        data-confirm-yes="delete-form-{{ isset($ticket) ? $ticket->id  : '' }}"><i class="ti ti-trash"></i></a>
                                </form>
                            </div>
                            @permission('ticket edit')
                            <div class="info-icon">
                                <button type="button" class="btn btn-primary">
                                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M7.5 0C3.3645 0 0 3.36455 0 7.50005C0 11.6355 3.3645 15 7.5 15C11.6355 15 15 11.6355 15 7.50005C15 3.36455 11.6355 0 7.5 0ZM7.5 13.6364C4.11636 13.6364 1.36364 10.8836 1.36364 7.50005C1.36364 4.11646 4.11636 1.36364 7.5 1.36364C10.8836 1.36364 13.6364 4.11646 13.6364 7.50005C13.6364 10.8836 10.8836 13.6364 7.5 13.6364Z"
                                            fill="white" />
                                        <path
                                            d="M7.49973 3.18176C6.99855 3.18176 6.59082 3.58976 6.59082 4.09126C6.59082 4.59231 6.99855 4.99994 7.49973 4.99994C8.00091 4.99994 8.40864 4.59231 8.40864 4.09126C8.40864 3.58976 8.00091 3.18176 7.49973 3.18176Z"
                                            fill="white" />
                                        <path
                                            d="M7.50018 6.36365C7.12363 6.36365 6.81836 6.66892 6.81836 7.04547V11.1364C6.81836 11.5129 7.12363 11.8182 7.50018 11.8182C7.87672 11.8182 8.182 11.5129 8.182 11.1364V7.04547C8.182 6.66892 7.87672 6.36365 7.50018 6.36365Z"
                                            fill="white" />
                                    </svg>
                                </button>
                            </div>
                            @endpermission
                        @endif
                    </div>
                </div>
            </div>
            <div class="chat-right-body flex-column" id="messages">
                <!--renderhtml-->
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="{{ asset('js/letter.avatar.js') }}"></script>
    <script src="{{ asset('js/moment.js') }}"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/custom-chat.js') }}"></script>
    <script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('public/libs/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $("#filterTickets").click(function () {
                $("#showTicketFilter").toggle();
            });


            // When click on new chat perform new action
            $(document).off('click', '.user_chat').on('click', '.user_chat', function () {
                // $('.user_chat').removeClass('active');
                $('.user_chat.active').removeClass('active');
                $(this).addClass('active');
                loadTicketDetails($(this));
                handleSettingIcon();
            });

            // copy link
            $(document).off('click', '.cp_link').on('click', '.cp_link', function () {
                var value = $(this).attr('data-link');
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val(value).select();
                document.execCommand("copy");
                $temp.remove();
                show_toastr('Success', '{{ __('Link Copy on Clipboard') }}', 'success');
            });


            // Handle Setting Icon Hide & Show 
            function handleSettingIcon() {
                $('.info-icon').show();
                $('.close-icon').hide();
                $(".chat-main-wrapper .chat-wrapper-right").removeClass('info-active');
            }
        });
    </script>

    <script>
        // search ticket by name
        function myFunction() {
            var input, filter, ul, li, span, emailSpan, txtValue, emailValue;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            ul = document.getElementById("myUL");
            li = ul.getElementsByTagName("li");
            for (var i = 0; i < li.length; i++) {
                span = li[i].getElementsByClassName("user-name")[0];
                if (span) {
                    txtValue = span.textContent || span.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        li[i].style.display = "";
                    } else {
                        li[i].style.display = "none";
                    }
                }
            }
        }

        function common() {
            // reply store
            $('#reply_submit').click(function (e) {
                e.preventDefault();
                var formData = new FormData($('#your-form-id')[0]);
                var description = $('#reply_description').val();
                var file = $('#file').val();

                // when description and attchment null
                if (description.trim() === '' && file.trim() === '') {
                    show_toastr('Error', "{{ __('Please add a description or attachment.') }}",
                        'error');
                } else {
                    $.ajax({
                        // url: "{{ url('/admin/ticketreply') }}" + '/' + ticket_id,
                        url: "{{ route('admin.reply.store', ['id' => '__ticket_id__']) }}".replace(
                            '__ticket_id__', ticket_id),
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (data) {


                            if (data.message) {
                                if (data.errorType == 'whatsapp') {
                                    show_toastr('Error', data.message, 'error');
                                } else if (data.errorType == 'Instagram') {
                                    show_toastr('Error', data.message, 'error');
                                } else if (data.errorType == 'Facebook') {
                                    show_toastr('Error', data.message, 'error');
                                } else {
                                    show_toastr('Error', data.message, 'error');
                                    $('#reply_description').summernote('code', '');
                                    $('.multiple_reply_file_selection').text('');
                                    $('#file').val('');
                                    return false;
                                }
                            }

                            const messageList = $('.messages-container');
                            let avatarSrc = LetterAvatar(data.sender_name, 100);

                            $('#reply_description').summernote('code', '');
                            $('.multiple_reply_file_selection').text('');
                            $('#file').val('');

                            var newMessage =
                                `
                                                                                                                                                        <div class="msg right-msg" data-id="${data.converstation.id}">
                                                                                                                                                            <div class="msg-box" data-conversion-id="${data.converstation.id}">
                                                                                                                                                                <div class="msg-box-content">
                                                                                                                                            <div class="msg-box-inner">

                                                                                                                                                                    <p>${data.new_message}</p>
                                                                                                                                                                    ${data.attachments ? `
                                                                                                                                                                                                                        <div class="attachments-wrp">
                                                                                                                                                                                                                            <h6>Attachments:</h6>
                                                                                                                                                                                                                                <ul class="attachments-list">
                                                                                                                                                                                                                                    ${data.attachments.map(function (attachment) {
                                    var filename = attachment.split('/').pop(); // Extract filename
                                    var fullUrl = data.baseUrl + attachment;
                                    return `
                                                                                                                                                                                            <li>
                                                                                                                                                                                                ${filename}
                                                                                                                                                                                                <a download href="${fullUrl}" class="edit-icon py-1 ml-2" title="Download">
                                                                                                                                                                                                    <i class="fa fa-download ms-2"></i>
                                                                                                                                                                                                </a>
                                                                                                                                                                                            </li>
                                                                                                                                                                                            `;
                                }).join('')}
                                                                                                                                                                                                                                </ul>
                                                                                                                                                                                                                         </div>
                                                                                                                                                                                                                        ` : ''}
                                                                                                                                                                                                                </div>
                                                                                                                                                                    <span>${data.timestamp}</span>
                                                                                                                                                                </div>
                                                                                                                                                                <div class="msg-user-info">
                                                                                                                                                                    <div class="msg-img">
                                                                                                                                                                        <img alt="${data.sender_name}" class="img-fluid" src="${avatarSrc}" />
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                            </div>
                                                                                                                                                        </div>
                                                                                                                                                    `;
                            messageList.append(newMessage);
                            $('.chat-container').scrollTop($('.chat-container')[0].scrollHeight);

                            LetterAvatar.transform();
                        },
                        error: function (xhr) {
                            // If the validation fails, the status code will be 422
                            if (xhr.status == 422) {
                                var errors = xhr.responseJSON.errors;
                                var errorMessage = '';
                                for (var field in errors) {
                                    errorMessage += errors[field].join('<br>');
                                }
                                show_toastr('Error', errorMessage, 'error');
                            }
                        }
                    });
                }

            });
            // summernote
            if ($(".summernote-simple").length > 0) {
                $('.summernote-simple').summernote({
                    dialogsInBody: !0,
                    minHeight: 150,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'strikethrough']],
                        ['list', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link', 'unlink']],
                    ],
                    height: 150,
                });
            }

            // tab
            $(document).ready(function () {
                $('.chat-tabs li').click(function () {
                    var tabId = $(this).attr('data-tab');
                    $('.chat-tabs li').removeClass('active');
                    $('.tab-content').removeClass('active');
                    $(this).addClass('active');
                    $('#' + tabId).addClass('active');
                });
            });

            // Letter avtar
            LetterAvatar.transform();

            // dropdown
            $(".dropdown-toggle").click(function (e) {
                e.stopPropagation();
                const dropdownMenu = $(this).siblings(".dropdown-menu");
                $(".dropdown-menu").not(dropdownMenu).removeClass("show");
                dropdownMenu.toggleClass("show");
            });

            $(document).click(function () {
                $(".dropdown-menu").removeClass("show");
            });

        }

        var ticket_id;

        function loadTicketDetails(userChatElement) {
            ticket_id = userChatElement.attr('id');
            var name = userChatElement.find('.chat_users_' + ticket_id).html();
            var img = userChatElement.find('.chat_users_img img').attr('src');
            var subject = userChatElement.find('.ticket_subject').val();
            var category = userChatElement.find('.ticket_category').val();
            var priority = userChatElement.find('.ticket_priority').val();
            if ("{{ isset($isTags) }}") {
                var ticket_tag_color = userChatElement.find('.ticket_tag_color').val();
            }
            if ("{{ isset($isMarkAsImportant) }}") {
                var ticket_mark_important = userChatElement.find('.ticket_mark_important').val();
            }
            if ("{{ isset($isChatPinEnabled) }}") {
                var ticket_chat_pin = userChatElement.find('.ticket_chat_pin').val();
            }
            $.ajax({
                type: "get",
                url: "{{ url('/admin/ticketdetail') }}" + '/' + ticket_id,
                data: "",
                cache: false,
                dataType: 'json',
                success: function (data) {
                    if (data.status == 'error') {
                        $('.chat-header').hide();
                        $('#load-btn').html('');

                        var messgehtml = '';

                        messgehtml += `
                                                                                                                                                <div class="no-conversation d-flex flex-column align-items-center justify-content-center text-center">
                                                                                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
                                                                                                                                                        <g clip-path="url(#clip0_5340_380)">
                                                                                                                                                        <path d="M19.9009 11.0289C21.1994 11.0289 22.411 11.4079 23.431 12.0611V5.82523C23.431 3.4441 21.4918 1.5625 19.1683 1.5625H4.26273C1.94054 1.5625 0 3.44296 0 5.82523V22.6921C0 23.3046 0.718117 23.6576 1.20125 23.2548L5.70469 19.5019H13.6208C13.4365 18.8974 13.3371 18.2566 13.3371 17.5926C13.3371 13.9732 16.2817 11.0289 19.9009 11.0289ZM4.65488 6.26946H18.7759C19.1805 6.26946 19.5084 6.59752 19.5084 7.00188C19.5084 7.40643 19.1805 7.7343 18.7759 7.7343H4.65488C4.25053 7.7343 3.92246 7.40643 3.92246 7.00188C3.92246 6.59752 4.25053 6.26946 4.65488 6.26946ZM4.65488 10.1921H14.069C14.4735 10.1921 14.8014 10.52 14.8014 10.9245C14.8014 11.3289 14.4735 11.657 14.069 11.657H4.65488C4.25053 11.657 3.92246 11.3289 3.92246 10.9245C3.92246 10.52 4.25053 10.1921 4.65488 10.1921ZM10.931 15.5794H4.65488C4.25053 15.5794 3.92246 15.2515 3.92246 14.847C3.92246 14.4424 4.25053 14.1146 4.65488 14.1146H10.931C11.3354 14.1146 11.6634 14.4424 11.6634 14.847C11.6634 15.2515 11.3356 15.5794 10.931 15.5794Z" fill="black"/>
                                                                                                                                                        <path d="M19.9007 12.4937C17.0847 12.4937 14.8018 14.7766 14.8018 17.5926C14.8018 20.4088 17.0847 22.6917 19.9007 22.6917C22.7169 22.6917 24.9998 20.4088 24.9998 17.5926C24.9998 14.7764 22.7169 12.4937 19.9007 12.4937ZM20.9516 19.6794L19.9005 18.6283L18.8493 19.6794C18.1637 20.3651 17.1287 19.3284 17.8135 18.6435L18.8646 17.5926L17.8135 16.5414C17.1278 15.8557 18.1646 14.8208 18.8493 15.5055L19.9005 16.5567L20.9516 15.5055C21.6371 14.8199 22.6721 15.8567 21.9873 16.5414L20.9362 17.5926L21.9873 18.6435C22.6724 19.3286 21.6371 20.3649 20.9516 19.6794Z" fill="black"/>
                                                                                                                                                        </g>
                                                                                                                                                        <defs>
                                                                                                                                                        <clipPath id="clip0_5340_380">
                                                                                                                                                        <rect width="25" height="25" fill="white"/>
                                                                                                                                                        </clipPath>
                                                                                                                                                        </defs>
                                                                                                                                                    </svg>
                                                                                                                                                    <h5>No conversation</h5>
                                                                                                                                                </div>
                                                                                                                                            `;

                        $('#messages').html(messgehtml);


                        var lihtml = '';

                        lihtml += `
                                                                                                                                                <li class="nav-item no-tickets text-center">
                                                                                                                                                    <p>No tickets Avaliable </p>
                                                                                                                                                </li>
                                                                                                                                            `;

                        $('#myUL').html(lihtml);
                        show_toastr('Error', data.message, 'error');
                    } else {
                        $('.chat-header').show();
                        if (data.unread_message_count > 0) {
                            $('#unread_notification_' + ticket_id)
                                .text(data.unread_message_count)
                                .removeClass('d-none');
                            $('#not_read_' + ticket_id)
                                .addClass('not-read');
                        } else {
                            $('#unread_notification_' + ticket_id)
                                .addClass('d-none');
                            $('#not_read_' + ticket_id)
                                .removeClass('not-read');
                        }
                        // For Ticket CopyLink
                        const encryptedIdForCopyLink = data.encryptedTicketId;
                        const ticketCopyLinkUrl = "{{ route('home.view', ':id') }}".replace(':id', encryptedIdForCopyLink);
                        const ticketDestroykUrl = "{{ route('admin.tickets.destroy', ':id') }}".replace(':id', encryptedIdForCopyLink);
                        $('#messages').html(data.tickethtml);
                        $('.chat_head').text(name);
                        $('.chat_subject').text(subject);
                        $('.chat_category').text(category);
                        $('.chat_priority').text(priority);
                        $('.chat_img').attr('src', img);
                        $('.chat-info .ticket-number').text(data.ticketNumber);
                        $('#copyTicketLink').attr('data-link', ticketCopyLinkUrl);
                        $('.delete-ticket-btn').attr('action', ticketDestroykUrl);
                        if ("{{ isset($isTags) }}") {
                            // Clear previous tags
                            $('.tag_color').empty();
                            if (Array.isArray(data?.tag) && data.tag.length > 0) {
                                data.tag.forEach(tag => {
                                    const html = `<label>
                                                                                        <svg class="chat_tag_color" width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                            <rect width="13" height="13" rx="2" fill="${tag.color}" />
                                                                                        </svg>
                                                                                        <span class="chat_tag_name">${tag.name}</span>
                                                                                                         </label>`;
                                    $('.tag_color').append(html);
                                });
                            }
                        }
                        if ("{{ isset($isMarkAsImportant) }}") {
                            if (ticket_mark_important == 1) {
                                var unmarkButton =
                                    `<button class="btn btn-sm btn-danger unmark-important" id="markImport" type="submit">{{ __('UnMark As Important') }}</button>`;
                                $('.mark-as-important').html('');
                                $('.mark-as-important').append(unmarkButton);
                            } else {
                                var unmarkButton =
                                    `<button class="btn btn-sm mark-important" id="markImport" type="submit">{{ __('Mark As Important') }}</button>`;
                                $('.mark-as-important').html('');
                                $('.mark-as-important').append(unmarkButton);
                            }
                        }
                        if ("{{ isset($isChatPinEnabled) }}") {
                            if (ticket_chat_pin == 1) {
                                var unpinButton = `<svg id="chatPin" class="unpin-svg" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <g clip-path="url(#clip0_1447_2876)">
                                                                            <path d="M11.1096 2.58333L4.88706 2.58333C4.60422 2.58333 4.39209 2.79546 4.39209 3.07831L4.39209 5.34105C4.39209 5.6946 4.67493 5.83602 4.88706 5.83602L5.38204 5.83602L5.38204 7.95734C4.74564 8.38161 3.04859 9.58369 3.04859 11.1393C3.04859 11.4222 3.26072 11.6343 3.54356 11.6343L7.57407 11.6343L7.57407 16.0891C7.57407 16.3719 7.7862 16.584 8.06905 16.584C8.35189 16.584 8.56402 16.3012 8.56402 16.0891L8.56402 11.6343L12.5945 11.6343C12.9834 11.5989 13.1249 11.3161 13.0895 11.1393C13.0895 9.58369 11.3924 8.38161 10.7561 7.95734V5.83602H11.251C11.5339 5.83602 11.746 5.62389 11.746 5.34105L11.746 3.07831C11.6046 2.79546 11.3924 2.58333 11.1096 2.58333ZM10.6146 4.84607L10.1197 4.84607C9.83681 4.84607 9.62468 5.0582 9.62468 5.34105L9.66004 8.27554C9.66004 8.48767 9.73075 8.62909 9.90752 8.73516C10.4025 9.018 11.5339 9.79582 11.8874 10.7151L4.10925 10.7151C4.42744 9.83118 5.59417 9.018 6.08915 8.73516C6.23057 8.66445 6.33663 8.48767 6.33663 8.27554L6.33663 5.3764C6.33663 4.95214 6.05379 4.88143 5.84166 4.88143L5.34668 4.88143L5.38204 3.57328L10.6146 3.57328L10.6146 4.84607Z" fill="black"/>
                                                                            <rect y="4" width="1" height="17.7814" rx="0.5" transform="rotate(-60 0 4)" fill="black"/>
                                                                            </g>
                                                                            <defs>
                                                                            <clipPath id="clip0_1447_2876">
                                                                            <rect width="16" height="16" fill="white"/>
                                                                            </clipPath>
                                                                            </defs>
                                                                            </svg>`;
                                $('.pin-icon').html('');
                                $('.pin-icon').append(unpinButton);
                            } else {
                                var unpinButton =
                                    `<svg id="chatPin" class="pin-svg" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                 <g clip-path="url(#clip0_1447_2864)">
                                                                     <path d="M11.1097 2.58331L4.88712 2.58331C4.60428 2.58331 4.39215 2.79544 4.39215 3.07828L4.39215 5.34103C4.39215 5.69458 4.67499 5.836 4.88712 5.836H5.3821L5.3821 7.95732C4.7457 8.38158 3.04865 9.58367 3.04865 11.1393C3.04865 11.4221 3.26078 11.6343 3.54362 11.6343L7.57413 11.6343L7.57413 16.089C7.57413 16.3719 7.78626 16.584 8.0691 16.584C8.35195 16.584 8.56408 16.3012 8.56408 16.089L8.56408 11.6343L12.5946 11.6343C12.9835 11.5989 13.1249 11.3161 13.0896 11.1393C13.0896 9.58367 11.3925 8.38158 10.7561 7.95732V5.836H11.2511C11.5339 5.836 11.7461 5.62387 11.7461 5.34103L11.7461 3.07828C11.6046 2.79544 11.3925 2.58331 11.1097 2.58331ZM10.6147 4.84605L10.1197 4.84605C9.83687 4.84605 9.62474 5.05818 9.62474 5.34103L9.66009 8.27552C9.66009 8.48765 9.73081 8.62907 9.90758 8.73514C10.4026 9.01798 11.5339 9.7958 11.8875 10.715L4.10931 10.715C4.4275 9.83115 5.59423 9.01798 6.08921 8.73514C6.23063 8.66443 6.33669 8.48765 6.33669 8.27552L6.33669 5.37638C6.33669 4.95212 6.05385 4.88141 5.84172 4.88141L5.34674 4.88141L5.3821 3.57326L10.6147 3.57326L10.6147 4.84605Z" fill="black" />
                                                                 </g>
                                                                 <defs>
                                                                     <clipPath id="clip0_1447_2864">
                                                                         <rect width="16" height="16" fill="white" />
                                                                     </clipPath>
                                                                 </defs>
                                                                 </svg>`;
                                $('.pin-icon').html('');
                                $('.pin-icon').append(unpinButton);
                            }
                        }
                        @if (isset($isExportConversations)) {
                            const encryptedId = data.encryptedTicketId;
                            const exportUrl = "{{ route('conversation.pdf', ':id') }}".replace(':id', encryptedId);
                            $('#exportTicketConverstationBtn').attr('href', exportUrl);
                        @endif



                        var status = data.status;

                        $('.chat_status option').each(function () {
                            if ($(this).val() === status) {
                                $(this).prop('selected', true);
                            }
                        });
                        $('.chat_status').niceSelect('update');

                        //common script
                        common();
                        $('.chat-container').scrollTop($('.chat-container')[0].scrollHeight);
                    }
                }
            });
        }

        // on ready active user chat
        $(document).ready(function () {
            var firstUserChat = $('.user_chat').first();
            firstUserChat.addClass('active');

            ticket_id = firstUserChat.attr('id');
            var name = firstUserChat.find('.chat_users_' + ticket_id).html();
            var img = firstUserChat.find('.chat_users_img img').attr('src');
            var subject = firstUserChat.find('.ticket_subject').val();
            var category = firstUserChat.find('.ticket_category').val();
            var priority = firstUserChat.find('.ticket_priority').val();
            if ("{{ isset($isTags) }}") {
                var ticket_tag_color = firstUserChat.find('.ticket_tag_color').val();
            }
            if ("{{ isset($isMarkAsImportant) }}") {
                var ticket_mark_important = firstUserChat.find('.ticket_mark_important').val();
            }
            if ("{{ isset($isChatPinEnabled) }}") {
                var ticket_chat_pin = firstUserChat.find('.ticket_chat_pin').val();
            }
            $.ajax({
                type: "get",
                url: "{{ url('/admin/ticketdetail') }}" + '/' + ticket_id,
                data: "",
                cache: false,
                dataType: 'json',
                success: function (data) {
                    if (data.status == 'error') {
                        $('.chat-header').hide();
                        $('#load-btn').html('');

                        var messgehtml = '';

                        messgehtml += `
                                                                                                                                                <div class="no-conversation d-flex flex-column align-items-center justify-content-center text-center">
                                                                                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
                                                                                                                                                        <g clip-path="url(#clip0_5340_380)">
                                                                                                                                                        <path d="M19.9009 11.0289C21.1994 11.0289 22.411 11.4079 23.431 12.0611V5.82523C23.431 3.4441 21.4918 1.5625 19.1683 1.5625H4.26273C1.94054 1.5625 0 3.44296 0 5.82523V22.6921C0 23.3046 0.718117 23.6576 1.20125 23.2548L5.70469 19.5019H13.6208C13.4365 18.8974 13.3371 18.2566 13.3371 17.5926C13.3371 13.9732 16.2817 11.0289 19.9009 11.0289ZM4.65488 6.26946H18.7759C19.1805 6.26946 19.5084 6.59752 19.5084 7.00188C19.5084 7.40643 19.1805 7.7343 18.7759 7.7343H4.65488C4.25053 7.7343 3.92246 7.40643 3.92246 7.00188C3.92246 6.59752 4.25053 6.26946 4.65488 6.26946ZM4.65488 10.1921H14.069C14.4735 10.1921 14.8014 10.52 14.8014 10.9245C14.8014 11.3289 14.4735 11.657 14.069 11.657H4.65488C4.25053 11.657 3.92246 11.3289 3.92246 10.9245C3.92246 10.52 4.25053 10.1921 4.65488 10.1921ZM10.931 15.5794H4.65488C4.25053 15.5794 3.92246 15.2515 3.92246 14.847C3.92246 14.4424 4.25053 14.1146 4.65488 14.1146H10.931C11.3354 14.1146 11.6634 14.4424 11.6634 14.847C11.6634 15.2515 11.3356 15.5794 10.931 15.5794Z" fill="black"/>
                                                                                                                                                        <path d="M19.9007 12.4937C17.0847 12.4937 14.8018 14.7766 14.8018 17.5926C14.8018 20.4088 17.0847 22.6917 19.9007 22.6917C22.7169 22.6917 24.9998 20.4088 24.9998 17.5926C24.9998 14.7764 22.7169 12.4937 19.9007 12.4937ZM20.9516 19.6794L19.9005 18.6283L18.8493 19.6794C18.1637 20.3651 17.1287 19.3284 17.8135 18.6435L18.8646 17.5926L17.8135 16.5414C17.1278 15.8557 18.1646 14.8208 18.8493 15.5055L19.9005 16.5567L20.9516 15.5055C21.6371 14.8199 22.6721 15.8567 21.9873 16.5414L20.9362 17.5926L21.9873 18.6435C22.6724 19.3286 21.6371 20.3649 20.9516 19.6794Z" fill="black"/>
                                                                                                                                                        </g>
                                                                                                                                                        <defs>
                                                                                                                                                        <clipPath id="clip0_5340_380">
                                                                                                                                                        <rect width="25" height="25" fill="white"/>
                                                                                                                                                        </clipPath>
                                                                                                                                                        </defs>
                                                                                                                                                    </svg>
                                                                                                                                                    <h5>No conversation</h5>
                                                                                                                                                </div>
                                                                                                                                            `;

                        $('#messages').html(messgehtml);


                        var lihtml = '';

                        lihtml += `
                                                                                                                                                <li class="nav-item no-tickets text-center">
                                                                                                                                                    <p>No tickets Avaliable </p>
                                                                                                                                                </li>
                                                                                                                                            `;

                        $('#myUL').html(lihtml);
                        // show_toastr('Error', data.message, 'error');
                    } else {
                        $('.chat-header').show();
                        if (data.unread_message_count > 0) {
                            $('#unread_notification_' + ticket_id)
                                .text(data.unread_message_count)
                                .removeClass('d-none');
                            $('#not_read_' + ticket_id)
                                .addClass('not-read');
                        } else {
                            $('#unread_notification_' + ticket_id)
                                .addClass('d-none');
                            $('#not_read_' + ticket_id)
                                .removeClass('not-read');
                        }
                        // For Ticket CopyLink
                        const encryptedIdForCopyLink = data.encryptedTicketId;
                        const ticketCopyLinkUrl = "{{ route('home.view', ':id') }}".replace(':id', encryptedIdForCopyLink);
                        const ticketDestroykUrl = "{{ route('admin.tickets.destroy', ':id') }}".replace(':id', encryptedIdForCopyLink);

                        $('#messages').html(data.tickethtml);
                        $('.chat_head').text(name);
                        $('.chat_subject').text(subject);
                        $('.chat_category').text(category);
                        $('.chat_priority').text(priority);
                        $('.chat_img').attr('src', img);
                        $('.chat-info .ticket-number').text(data.ticketNumber);
                        $('#copyTicketLink').attr('data-link', ticketCopyLinkUrl);
                        $('.delete-ticket-btn').attr('action', ticketDestroykUrl);
                        if ("{{ isset($isTags) }}") {
                            $('.tag_color').empty();
                            if (Array.isArray(data?.tag) && data.tag.length > 0) {
                                data.tag.forEach(tag => {
                                    const html = `<label>
                                                                                        <svg class="chat_tag_color" width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                            <rect width="13" height="13" rx="2" fill="${tag.color}" />
                                                                                        </svg>
                                                                                        <span class="chat_tag_name">${tag.name}</span>
                                                                                                         </label>`;
                                    $('.tag_color').append(html);
                                });
                            }
                        }
                        if ("{{ isset($isMarkAsImportant) }}") {
                            if (ticket_mark_important == 1) {
                                var unmarkButton =
                                    `<button class="btn btn-sm btn-danger unmark-important" id="markImport" type="submit">{{ __('UnMark As Important') }}</button>`;
                                $('.mark-as-important').html('');
                                $('.mark-as-important').append(unmarkButton);
                            } else {
                                var unmarkButton =
                                    `<button class="btn btn-sm mark-important" id="markImport" type="submit">{{ __('Mark As Important') }}</button>`;
                                $('.mark-as-important').html('');
                                $('.mark-as-important').append(unmarkButton);
                            }
                        }
                        if ("{{ isset($isChatPinEnabled) }}") {
                            if (ticket_chat_pin == 1) {
                                var unpinButton = `<svg id="chatPin" class="unpin-svg" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                                                                                    <g clip-path="url(#clip0_1447_2876)">
                                                                                                                                                    <path d="M11.1096 2.58333L4.88706 2.58333C4.60422 2.58333 4.39209 2.79546 4.39209 3.07831L4.39209 5.34105C4.39209 5.6946 4.67493 5.83602 4.88706 5.83602L5.38204 5.83602L5.38204 7.95734C4.74564 8.38161 3.04859 9.58369 3.04859 11.1393C3.04859 11.4222 3.26072 11.6343 3.54356 11.6343L7.57407 11.6343L7.57407 16.0891C7.57407 16.3719 7.7862 16.584 8.06905 16.584C8.35189 16.584 8.56402 16.3012 8.56402 16.0891L8.56402 11.6343L12.5945 11.6343C12.9834 11.5989 13.1249 11.3161 13.0895 11.1393C13.0895 9.58369 11.3924 8.38161 10.7561 7.95734V5.83602H11.251C11.5339 5.83602 11.746 5.62389 11.746 5.34105L11.746 3.07831C11.6046 2.79546 11.3924 2.58333 11.1096 2.58333ZM10.6146 4.84607L10.1197 4.84607C9.83681 4.84607 9.62468 5.0582 9.62468 5.34105L9.66004 8.27554C9.66004 8.48767 9.73075 8.62909 9.90752 8.73516C10.4025 9.018 11.5339 9.79582 11.8874 10.7151L4.10925 10.7151C4.42744 9.83118 5.59417 9.018 6.08915 8.73516C6.23057 8.66445 6.33663 8.48767 6.33663 8.27554L6.33663 5.3764C6.33663 4.95214 6.05379 4.88143 5.84166 4.88143L5.34668 4.88143L5.38204 3.57328L10.6146 3.57328L10.6146 4.84607Z" fill="black"/>
                                                                                                                                                    <rect y="4" width="1" height="17.7814" rx="0.5" transform="rotate(-60 0 4)" fill="black"/>
                                                                                                                                                    </g>
                                                                                                                                                    <defs>
                                                                                                                                                    <clipPath id="clip0_1447_2876">
                                                                                                                                                    <rect width="16" height="16" fill="white"/>
                                                                                                                                                    </clipPath>
                                                                                                                                                    </defs>
                                                                                                                                                    </svg>
                                                                                                                                                `;
                                $('.pin-icon').html('');
                                $('.pin-icon').append(unpinButton);
                            } else {
                                var unpinButton =
                                    `<svg id="chatPin" class="pin-svg" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <g clip-path="url(#clip0_1447_2864)">
                                                                    <path d="M11.1097 2.58331L4.88712 2.58331C4.60428 2.58331 4.39215 2.79544 4.39215 3.07828L4.39215 5.34103C4.39215 5.69458 4.67499 5.836 4.88712 5.836H5.3821L5.3821 7.95732C4.7457 8.38158 3.04865 9.58367 3.04865 11.1393C3.04865 11.4221 3.26078 11.6343 3.54362 11.6343L7.57413 11.6343L7.57413 16.089C7.57413 16.3719 7.78626 16.584 8.0691 16.584C8.35195 16.584 8.56408 16.3012 8.56408 16.089L8.56408 11.6343L12.5946 11.6343C12.9835 11.5989 13.1249 11.3161 13.0896 11.1393C13.0896 9.58367 11.3925 8.38158 10.7561 7.95732V5.836H11.2511C11.5339 5.836 11.7461 5.62387 11.7461 5.34103L11.7461 3.07828C11.6046 2.79544 11.3925 2.58331 11.1097 2.58331ZM10.6147 4.84605L10.1197 4.84605C9.83687 4.84605 9.62474 5.05818 9.62474 5.34103L9.66009 8.27552C9.66009 8.48765 9.73081 8.62907 9.90758 8.73514C10.4026 9.01798 11.5339 9.7958 11.8875 10.715L4.10931 10.715C4.4275 9.83115 5.59423 9.01798 6.08921 8.73514C6.23063 8.66443 6.33669 8.48765 6.33669 8.27552L6.33669 5.37638C6.33669 4.95212 6.05385 4.88141 5.84172 4.88141L5.34674 4.88141L5.3821 3.57326L10.6147 3.57326L10.6147 4.84605Z" fill="black" />
                                                                </g>
                                                                <defs>
                                                                    <clipPath id="clip0_1447_2864">
                                                                        <rect width="16" height="16" fill="white" />
                                                                    </clipPath>
                                                                </defs>
                                                                </svg>`;
                                $('.pin-icon').html('');
                                $('.pin-icon').append(unpinButton);
                            }
                        }
                        @if (isset($isExportConversations)) {
                            const encryptedId = data.encryptedTicketId;
                            const exportUrl = "{{ route('conversation.pdf', ':id') }}".replace(':id', encryptedId);
                            $('#exportTicketConverstationBtn').attr('href', exportUrl);
                        @endif
                        var status = data.status;
                        $('.chat_status option').each(function () {
                            if ($(this).val() === status) {
                                $(this).prop('selected', true);
                            }
                        });
                        $('.chat_status').niceSelect('update');

                        //common script
                        common();
                        $('.chat-container').scrollTop($('.chat-container')[0].scrollHeight);
                    }

                }
            });
        });
    </script>

    <script>
        // ticket status change
        $(document).on('change', '.status_change', function () {
            var id = $('.user_chat.active').attr('id');
            var status = this.value;
            var url = $(this).data('url');
            var Url = url.replace('{{ $ticket->id ?? 0 }}', id);

            $.ajax({
                url: Url + '?status=' + status,
                type: 'GET',
                cache: false,
                success: function (data) {
                    if (data.status == 'success') {
                        show_toastr('Success', data.message, 'success');
                    } else {
                        show_toastr('Error', data.message, 'error');
                    }
                },
            });
        });

        // ticket assign user change
        $(document).on('change', '#agents', function () {
            var id = $('.user_chat.active').attr('id');
            var user = this.value;
            var url = $(this).data('url');

            var Url = url.replace('{{ $ticket->id ?? 0 }}', id);
            $.ajax({
                url: Url + '?assign=' + user,
                type: 'GET',
                cache: false,
                success: function (data) {
                    if (data.status == 'success') {
                        show_toastr('Success', data.message, 'success');
                    } else {
                        show_toastr('Error', data.message, 'error');
                    }
                }
            });
        });

        // ticket category change

        $(document).on('change', '#category', function () {
            var id = $('.user_chat.active').attr('id');
            var category = this.value;
            var url = $(this).data('url');
            var Url = url.replace('{{ $ticket->id ?? 0 }}', id);
            $.ajax({
                url: Url + '?category=' + category,
                type: 'GET',
                cache: false,
                success: function (data) {
                    if (data.status == 'success') {
                        show_toastr('Success', data.message, 'success');
                    } else {
                        show_toastr('Error', data.message, 'error');
                    }
                }
            });
        });

        // ticket priority change

        $(document).on('change', '#priority', function () {
            var id = $('.user_chat.active').attr('id');
            var priority = this.value;
            var url = $(this).data('url');
            var Url = url.replace('{{ $ticket->id ?? 0 }}', id);
            $.ajax({
                url: Url + '?priority=' + priority,
                type: 'GET',
                cache: false,
                success: function (data) {
                    if (data.status == 'success') {
                        show_toastr('Success', data.message, 'success');
                    } else {
                        show_toastr('Error', data.message, 'error');
                    }
                }
            });
        });

        // ticket name change
        $(document).off('click', '#save-name').on('click', '#save-name', function (e) {
            $('#save-name').prop('disabled', true);
            e.preventDefault();
            var newName = $('#ticket-user-name').val();
            var id = $('.user_chat.active').attr('id');
            var url = '{{ route('admin.ticket.name.change', ['id' => $ticket->id ?? 0]) }}';
            url = url.replace('{{ $ticket->id ?? 0 }}', id);
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    name: newName,
                    _token: '{{ csrf_token() }}'
                },
                success: function (data) {
                    $('#save-name').prop('disabled', false);
                    if (data.status == 'success') {
                        show_toastr('Success', data.message, 'success');
                        $('.chat_head').text(newName);
                        $('.chat_users_' + id).text(newName);
                    } else {
                        show_toastr('Error', data.message, 'error');
                    }
                }
            });
        });


        // ticket email change
        $(document).off('click', '#save-email').on('click', '#save-email', function (e) {
            $('#save-email').prop('disabled', true);
            e.preventDefault();

            var newEmail = $('#ticket-email').val();
            var id = $('.user_chat.active').attr('id');
            var url = '{{ route('admin.ticket.email.change', ['id' => $ticket->id ?? 0]) }}';
            url = url.replace('{{ $ticket->id ?? 0 }}', id);

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    email: newEmail,
                    _token: '{{ csrf_token() }}'
                },
                success: function (data) {
                    $('#save-email').prop('disabled', false);
                    if (data.status == 'success') {
                        show_toastr('Success', data.message, 'success');
                        // $('.chat-user').text(newEmail);
                    } else {
                        show_toastr('Error', data.message, 'error');
                    }
                }
            });
        });

        // ticket subject change
        $(document).off('click', '#save-subject').on('click', '#save-subject', function (e) {
            $('#save-subject').prop('disabled', true);
            e.preventDefault();

            var newSubject = $('#ticket-subject').val();
            var id = $('.user_chat.active').attr('id');
            var url = '{{ route('admin.ticket.subject.change', ['id' => $ticket->id ?? 0]) }}';
            url = url.replace('{{ $ticket->id ?? 0 }}', id);

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    subject: newSubject,
                    _token: '{{ csrf_token() }}'
                },
                success: function (data) {
                    $('#save-subject').prop('disabled', false);
                    if (data.status == 'success') {
                        show_toastr('Success', data.message, 'success');
                        $('.chat_subject').text(newSubject);
                    } else {
                        show_toastr('Error', data.message, 'error');
                    }
                }
            });
        });


        // ticket type select filter
        $(document).on('change', '#tikcettype', function () {
            var tikcettype = this.value;
            if (tikcettype) {
                $.ajax({
                    url: "{{ route('admin.new.chat') }}",
                    type: 'GET',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "tikcettype": tikcettype,
                    },
                    success: function (data) {
                        if (data.tickets && data.tickets.length > 0) {
                            var ticketHtml = '';
                            var ticketsToDisplay = data.tickets.slice(0, 10);
                            $.each(ticketsToDisplay, function (index, ticket) {
                                var createdAtFormatted = moment(ticket.created_at).fromNow();

                                let avatarSrc = LetterAvatar(ticket.name, 100);

                                var description = ticket.latest_message ? ticket
                                    .latest_message : '';
                                var unread = ticket.unread ? ticket.unread : '';

                                $('.chat-header').show();
                                var ticketClass = (ticket.is_mark && ticket.is_mark == 1) ?
                                    'ticket-danger' : '';

                                ticketHtml += `<li class="nav-item user_chat" id="${ticket.id}">
                                                                                                                                                        <div class="social-chat">
                                                                                                                                                            <div class="social-chat-img chat_users_img ">
                                                                                                                                                                <img alt="${ticket.name}" class="img-fluid" avatar="${ticket.name}" src="${avatarSrc}">
                                                                                                                                                            </div>
                                                                                                                                                            <div class="user-info flex-1">
                                                                                                                                                                <span class="user-name chat_users_${ticket.id}  ${ticketClass}">${ticket.name}</span>
                                                                                                                                                                <p class="chat-user ${unread > 0 ? 'not-read' : ''}" id="not_read_${ticket.id}">
                                                                                                                                                                ${description}
                                                                                                                                                                </p>

                                                                                                                                                            </div>
                                                                                                                                                            <input type="hidden" class="ticket_subject" value="${ticket.subject}">
                                                                                                                                                            <input type="hidden" class="ticket_category" value="${ticket.getCategory ? ticket.getCategory.name : '---'}">
                                                                                                                                                            <input type="hidden" class="ticket_priority" value="${ticket.getPriority ? ticket.getPriority.name : '---'}">
                                                                                                                                                            <input type="hidden" class="ticket_category_color" value="${ticket.getCategory ? ticket.getCategory.color : '---'}">
                                                                                                                                                            <input type="hidden" class="ticket_priority_color" value="${ticket.getPriority ? ticket.getPriority.color : '---'}">
                                                                                                                                                            <input type="hidden" class="ticket_status" value="${ticket.status ? ticket.status : '---'}">
                                                                                                                                                            <input type="hidden" class="ticket_tag_color" value="${ticket.getTagsAttribute ? ticket.getTagsAttribute.color : '---'}">
                                                                                                                                                            <input type="hidden" class="ticket_mark_important" value="${ticket.is_mark}">
                                                                                                                                                            <input type="hidden" class="ticket_chat_pin" value="${ticket.is_pin}">
                                                                                                                                                            <div class="chat-pin-icon"> </div>
                                                                                                                                                           <div class="social-icon-wrp">

                                                                                                                                                                 ${ticket.type === 'Whatsapp' ? `
                                                                                                                                                                                                 <a href="javascript:;">
                                                                                                                                                                                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                                                                                                                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                                                                                                                                        <g clip-path="url(#clip0_51_375)">
                                                                                                                                                                                                            <path
                                                                                                                                                                                                                d="M6 12C9.31371 12 12 9.31371 12 6C12 2.68629 9.31371 0 6 0C2.68629 0 0 2.68629 0 6C0 9.31371 2.68629 12 6 12Z"
                                                                                                                                                                                                                fill="#29A71A" />
                                                                                                                                                                                                            <path
                                                                                                                                                                                                                d="M8.6454 3.35454C8.02115 2.72406 7.19214 2.33741 6.30792 2.26433C5.4237 2.19125 4.54247 2.43654 3.82318 2.95598C3.10388 3.47541 2.59389 4.23478 2.38518 5.09712C2.17647 5.95946 2.28278 6.868 2.68494 7.65886L2.29017 9.57545C2.28607 9.59452 2.28596 9.61424 2.28983 9.63336C2.2937 9.65249 2.30148 9.67061 2.31267 9.68659C2.32907 9.71084 2.35247 9.72951 2.37976 9.74011C2.40705 9.75071 2.43693 9.75273 2.4654 9.7459L4.34381 9.30068C5.13244 9.69266 6.03456 9.79214 6.88966 9.58141C7.74475 9.37068 8.49735 8.86342 9.01355 8.14988C9.52974 7.43634 9.77604 6.56281 9.70863 5.68471C9.64122 4.80662 9.26446 3.98092 8.6454 3.35454ZM8.05971 8.02909C7.6278 8.45979 7.07162 8.7441 6.46955 8.84196C5.86749 8.93981 5.24989 8.84628 4.70381 8.57454L4.44199 8.44499L3.2904 8.71772L3.29381 8.7034L3.53244 7.54431L3.40426 7.29136C3.12523 6.74336 3.0268 6.12111 3.12307 5.51375C3.21934 4.90639 3.50536 4.34508 3.94017 3.91022C4.48651 3.36405 5.22741 3.05722 5.99994 3.05722C6.77247 3.05722 7.51337 3.36405 8.05971 3.91022C8.06437 3.91556 8.06938 3.92057 8.07471 3.92522C8.61429 4.4728 8.9155 5.21149 8.91269 5.98024C8.90988 6.74899 8.60327 7.48546 8.05971 8.02909Z"
                                                                                                                                                                                                                fill="white" />
                                                                                                                                                                                                            <path
                                                                                                                                                                                                                d="M7.95745 7.17885C7.81632 7.40112 7.59336 7.67317 7.31314 7.74067C6.82223 7.85931 6.06882 7.74476 5.13132 6.87067L5.11973 6.86044C4.29541 6.09613 4.08132 5.45999 4.13314 4.95544C4.16177 4.66908 4.40041 4.40999 4.60155 4.2409C4.63334 4.21376 4.67105 4.19443 4.71166 4.18447C4.75226 4.17451 4.79463 4.17419 4.83538 4.18353C4.87613 4.19288 4.91412 4.21162 4.94633 4.23828C4.97854 4.26493 5.00406 4.29875 5.02086 4.33703L5.32427 5.01885C5.34399 5.06306 5.3513 5.1118 5.34541 5.15985C5.33952 5.2079 5.32067 5.25344 5.29086 5.29158L5.13745 5.49067C5.10454 5.53178 5.08467 5.5818 5.08042 5.63429C5.07617 5.68678 5.08772 5.73934 5.11359 5.78522C5.1995 5.9359 5.40541 6.15749 5.63382 6.36272C5.89018 6.59453 6.1745 6.80658 6.3545 6.87885C6.40266 6.89853 6.45562 6.90333 6.50654 6.89264C6.55745 6.88194 6.604 6.85624 6.64018 6.81885L6.81814 6.63953C6.85247 6.60567 6.89517 6.58153 6.94189 6.56955C6.9886 6.55757 7.03765 6.55819 7.08405 6.57135L7.80473 6.7759C7.84448 6.78809 7.88092 6.80922 7.91126 6.83765C7.9416 6.86609 7.96503 6.90109 7.97977 6.93997C7.99451 6.97886 8.00016 7.0206 7.99629 7.062C7.99242 7.1034 7.97914 7.14337 7.95745 7.17885Z"
                                                                                                                                                                                                                fill="white" />
                                                                                                                                                                                                        </g>
                                                                                                                                                                                                        <defs>
                                                                                                                                                                                                            <clipPath id="clip0_51_375">
                                                                                                                                                                                                                <rect width="12" height="12" fill="white" />
                                                                                                                                                                                                            </clipPath>
                                                                                                                                                                                                        </defs>
                                                                                                                                                                                                    </svg>

                                                                                                                                                                                                </a>
                                                                                                                                                                                            ` : ticket.type === 'Instagram' ? `
                                                                                                                                                                                                 <a href="javascript:;">
                                                                                                                                                                                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                                                                                                                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                                                                                                                                        <g clip-path="url(#clip0_51_298)">
                                                                                                                                                                                                            <path
                                                                                                                                                                                                                d="M9.23313 0H2.76687C1.23877 0 0 1.23877 0 2.76687V9.23313C0 10.7612 1.23877 12 2.76687 12H9.23313C10.7612 12 12 10.7612 12 9.23313V2.76687C12 1.23877 10.7612 0 9.23313 0Z"
                                                                                                                                                                                                                fill="url(#paint0_linear_51_298)" />
                                                                                                                                                                                                            <path
                                                                                                                                                                                                                d="M7.93435 2.36992C8.38969 2.37175 8.82585 2.55341 9.14783 2.87531C9.46981 3.19722 9.6515 3.63329 9.65334 4.08852V7.91148C9.6515 8.36671 9.46981 8.80278 9.14783 9.12469C8.82585 9.44659 8.38969 9.62825 7.93435 9.63008H4.06565C3.61031 9.62825 3.17415 9.44659 2.85217 9.12469C2.53019 8.80278 2.3485 8.36671 2.34666 7.91148V4.08852C2.3485 3.63329 2.53019 3.19722 2.85217 2.87531C3.17415 2.55341 3.61031 2.37175 4.06565 2.36992H7.93435ZM7.93435 1.57057H4.06565C2.6804 1.57057 1.54688 2.70513 1.54688 4.08878V7.91148C1.54688 9.29642 2.68169 10.4297 4.06565 10.4297H7.93435C9.3196 10.4297 10.4531 9.29513 10.4531 7.91148V4.08852C10.4531 2.70358 9.3196 1.57031 7.93435 1.57031V1.57057Z"
                                                                                                                                                                                                                fill="white" />
                                                                                                                                                                                                            <path
                                                                                                                                                                                                                d="M6 4.50433C6.29582 4.50433 6.58499 4.59205 6.83095 4.7564C7.07691 4.92074 7.26862 5.15433 7.38182 5.42763C7.49502 5.70093 7.52464 6.00166 7.46693 6.29179C7.40922 6.58192 7.26677 6.84842 7.0576 7.0576C6.84842 7.26677 6.58192 7.40922 6.29179 7.46693C6.00166 7.52464 5.70093 7.49502 5.42763 7.38182C5.15433 7.26861 4.92074 7.07691 4.7564 6.83095C4.59205 6.58499 4.50433 6.29581 4.50433 6C4.50481 5.60347 4.66254 5.22332 4.94293 4.94293C5.22332 4.66254 5.60347 4.50481 6 4.50433ZM6 3.70313C5.54572 3.70312 5.10164 3.83783 4.72393 4.09022C4.34621 4.3426 4.05181 4.70132 3.87797 5.12102C3.70412 5.54072 3.65863 6.00255 3.74726 6.4481C3.83589 6.89365 4.05464 7.30291 4.37587 7.62413C4.69709 7.94536 5.10635 8.16411 5.5519 8.25274C5.99745 8.34137 6.45928 8.29588 6.87898 8.12203C7.29868 7.94819 7.6574 7.65379 7.90978 7.27607C8.16217 6.89836 8.29688 6.45428 8.29688 6C8.29688 5.39083 8.05488 4.80661 7.62414 4.37586C7.19339 3.94512 6.60917 3.70313 6 3.70313Z"
                                                                                                                                                                                                                fill="white" />
                                                                                                                                                                                                            <path
                                                                                                                                                                                                                d="M8.34375 4.14844C8.64147 4.14844 8.88281 3.90709 8.88281 3.60937C8.88281 3.31166 8.64147 3.07031 8.34375 3.07031C8.04603 3.07031 7.80469 3.31166 7.80469 3.60937C7.80469 3.90709 8.04603 4.14844 8.34375 4.14844Z"
                                                                                                                                                                                                                fill="white" />
                                                                                                                                                                                                        </g>
                                                                                                                                                                                                        <defs>
                                                                                                                                                                                                            <linearGradient id="paint0_linear_51_298" x1="7.86479" y1="12.5037"
                                                                                                                                                                                                                x2="4.13521" y2="-0.503678" gradientUnits="userSpaceOnUse">
                                                                                                                                                                                                                <stop stop-color="#FFDB73" />
                                                                                                                                                                                                                <stop offset="0.08" stop-color="#FDAD4E" />
                                                                                                                                                                                                                <stop offset="0.15" stop-color="#FB832E" />
                                                                                                                                                                                                                <stop offset="0.19" stop-color="#FA7321" />
                                                                                                                                                                                                                <stop offset="0.23" stop-color="#F6692F" />
                                                                                                                                                                                                                <stop offset="0.37" stop-color="#E84A5A" />
                                                                                                                                                                                                                <stop offset="0.48" stop-color="#E03675" />
                                                                                                                                                                                                                <stop offset="0.55" stop-color="#DD2F7F" />
                                                                                                                                                                                                                <stop offset="0.68" stop-color="#B43D97" />
                                                                                                                                                                                                                <stop offset="0.97" stop-color="#4D60D4" />
                                                                                                                                                                                                                <stop offset="1" stop-color="#4264DB" />
                                                                                                                                                                                                            </linearGradient>
                                                                                                                                                                                                            <clipPath id="clip0_51_298">
                                                                                                                                                                                                                <rect width="12" height="12" fill="white" />
                                                                                                                                                                                                            </clipPath>
                                                                                                                                                                                                        </defs>
                                                                                                                                                                                                    </svg>
                                                                                                                                                                                                </a>
                                                                                                                                                                                                ` : ticket.type === 'Facebook' ? `
                                                                                                                                                                                                    <a href="javascript:;">
                                                                                                                                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 15 16" fill="url(#Ld6sqrtcxMyckEl6xeDdMa)">
                                                                                                                                                                                                        <g clip-path="url(#clip0_17_3195)">
                                                                                                                                                                                                        <path d="M14.8586 7.95076C14.8586 3.89299 11.5691 0.603516 7.51131 0.603516C3.45353 0.603516 0.164062 3.89299 0.164062 7.95076C0.164062 11.6179 2.85083 14.6576 6.3633 15.2088V10.0746H4.49779V7.95076H6.3633V6.33207C6.3633 4.49067 7.46022 3.47353 9.13847 3.47353C9.94207 3.47353 10.7831 3.61703 10.7831 3.61703V5.42515H9.85669C8.94402 5.42515 8.65932 5.99154 8.65932 6.57315V7.95076H10.697L10.3713 10.0746H8.65932V15.2088C12.1718 14.6576 14.8586 11.6179 14.8586 7.95076Z" fill="#0017A8"></path>
                                                                                                                                                                                                        </g>
                                                                                                                                                                                                        <defs>
                                                                                                                                                                                                        <clipPath id="clip0_17_3195">
                                                                                                                                                                                                        <rect width="14.6945" height="14.6945" fill="white" transform="translate(0.164062 0.603516)"></rect>
                                                                                                                                                                                                        </clipPath>
                                                                                                                                                                                                        </defs>
                                                                                                                                                                                                    </svg>
                                                                                                                                                                                                    </a>
                                                                                                                                                                                            ` : ticket.type === 'Unassigned' || ticket.type === 'Assigned' ? `
                                                                                                                                                                                                <a href="javascript:;">
                                                                                                                                                                                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                                                                                                                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                                                                                                                                        <g clip-path="url(#clip0_5_426)">
                                                                                                                                                                                                            <path
                                                                                                                                                                                                                d="M9.59978 0.00012207H2.40013C1.76364 0.00012207 1.15322 0.252966 0.703154 0.703032C0.253088 1.1531 0.000244141 1.76352 0.000244141 2.40001V7.19977C0.000244141 7.83626 0.253088 8.44668 0.703154 8.89675C1.15322 9.34681 1.76364 9.59966 2.40013 9.59966V11.3996C2.39943 11.5187 2.43425 11.6354 2.50012 11.7347C2.566 11.834 2.65996 11.9115 2.77002 11.9572C2.88008 12.0029 3.00126 12.0148 3.1181 11.9913C3.23494 11.9679 3.34216 11.9102 3.42608 11.8255L5.64597 9.59966H9.59978C10.2363 9.59966 10.8467 9.34681 11.2968 8.89675C11.7468 8.44668 11.9997 7.83626 11.9997 7.19977V2.40001C11.9997 1.76352 11.7468 1.1531 11.2968 0.703032C10.8467 0.252966 10.2363 0.00012207 9.59978 0.00012207Z"
                                                                                                                                                                                                                fill="#2675E2" />
                                                                                                                                                                                                            <path
                                                                                                                                                                                                                d="M3.3001 5.69985C3.79714 5.69985 4.20006 5.29692 4.20006 4.79989C4.20006 4.30286 3.79714 3.89993 3.3001 3.89993C2.80307 3.89993 2.40015 4.30286 2.40015 4.79989C2.40015 5.29692 2.80307 5.69985 3.3001 5.69985Z"
                                                                                                                                                                                                                fill="#EDEBEA" />
                                                                                                                                                                                                            <path
                                                                                                                                                                                                                d="M6.00005 5.69985C6.49709 5.69985 6.90001 5.29692 6.90001 4.79989C6.90001 4.30286 6.49709 3.89993 6.00005 3.89993C5.50302 3.89993 5.1001 4.30286 5.1001 4.79989C5.1001 5.29692 5.50302 5.69985 6.00005 5.69985Z"
                                                                                                                                                                                                                fill="#EDEBEA" />
                                                                                                                                                                                                            <path
                                                                                                                                                                                                                d="M8.69988 5.69985C9.19692 5.69985 9.59984 5.29692 9.59984 4.79989C9.59984 4.30286 9.19692 3.89993 8.69988 3.89993C8.20285 3.89993 7.79993 4.30286 7.79993 4.79989C7.79993 5.29692 8.20285 5.69985 8.69988 5.69985Z"
                                                                                                                                                                                                                fill="#EDEBEA" />
                                                                                                                                                                                                        </g>
                                                                                                                                                                                                        <defs>
                                                                                                                                                                                                            <clipPath id="clip0_5_426">
                                                                                                                                                                                                                <rect width="12" height="12" fill="white" />
                                                                                                                                                                                                            </clipPath>
                                                                                                                                                                                                        </defs>
                                                                                                                                                                                                    </svg>
                                                                                                                                                                                                </a>
                                                                                                                                                                                            ` : ''}
                                                                                                                                                                <span class="chat-time">${createdAtFormatted}</span>
                                                                                                                                                                ${unread > 0 ? `<span class="notification" id="unread_notification_${ticket.id}">${unread}</span>` : ''}
                                                                                                                                                            </div>

                                                                                                                                                        </div>
                                                                                                                                                    </li>
                                                                                                                                                `;
                            });

                            $('#myUL').html(ticketHtml);
                            $('#load-btn').html('');
                            $('#myUL li:first').addClass('active');

                            var firstUserChat = $('#myUL li:first');
                            loadTicketDetails(firstUserChat);

                            if (data.tickets.length > 10) {
                                var btnHtml = '';
                                btnHtml +=
                                    `<button class="load-more-btn" id="load_more">{{ __('Load More Conversations') }}</button>`;
                                $('#load-btn').html(btnHtml);
                            }

                        } else {
                            $('.chat-header').hide();
                            $('#load-btn').html('');

                            var messgehtml = '';

                            messgehtml += `<div class="no-conversation d-flex flex-column align-items-center justify-content-center text-center">
                                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
                                                                                                <g clip-path="url(#clip0_5340_380)">
                                                                                                <path d="M19.9009 11.0289C21.1994 11.0289 22.411 11.4079 23.431 12.0611V5.82523C23.431 3.4441 21.4918 1.5625 19.1683 1.5625H4.26273C1.94054 1.5625 0 3.44296 0 5.82523V22.6921C0 23.3046 0.718117 23.6576 1.20125 23.2548L5.70469 19.5019H13.6208C13.4365 18.8974 13.3371 18.2566 13.3371 17.5926C13.3371 13.9732 16.2817 11.0289 19.9009 11.0289ZM4.65488 6.26946H18.7759C19.1805 6.26946 19.5084 6.59752 19.5084 7.00188C19.5084 7.40643 19.1805 7.7343 18.7759 7.7343H4.65488C4.25053 7.7343 3.92246 7.40643 3.92246 7.00188C3.92246 6.59752 4.25053 6.26946 4.65488 6.26946ZM4.65488 10.1921H14.069C14.4735 10.1921 14.8014 10.52 14.8014 10.9245C14.8014 11.3289 14.4735 11.657 14.069 11.657H4.65488C4.25053 11.657 3.92246 11.3289 3.92246 10.9245C3.92246 10.52 4.25053 10.1921 4.65488 10.1921ZM10.931 15.5794H4.65488C4.25053 15.5794 3.92246 15.2515 3.92246 14.847C3.92246 14.4424 4.25053 14.1146 4.65488 14.1146H10.931C11.3354 14.1146 11.6634 14.4424 11.6634 14.847C11.6634 15.2515 11.3356 15.5794 10.931 15.5794Z" fill="black"/>
                                                                                                <path d="M19.9007 12.4937C17.0847 12.4937 14.8018 14.7766 14.8018 17.5926C14.8018 20.4088 17.0847 22.6917 19.9007 22.6917C22.7169 22.6917 24.9998 20.4088 24.9998 17.5926C24.9998 14.7764 22.7169 12.4937 19.9007 12.4937ZM20.9516 19.6794L19.9005 18.6283L18.8493 19.6794C18.1637 20.3651 17.1287 19.3284 17.8135 18.6435L18.8646 17.5926L17.8135 16.5414C17.1278 15.8557 18.1646 14.8208 18.8493 15.5055L19.9005 16.5567L20.9516 15.5055C21.6371 14.8199 22.6721 15.8567 21.9873 16.5414L20.9362 17.5926L21.9873 18.6435C22.6724 19.3286 21.6371 20.3649 20.9516 19.6794Z" fill="black"/>
                                                                                                </g>
                                                                                                <defs>
                                                                                                <clipPath id="clip0_5340_380">
                                                                                                <rect width="25" height="25" fill="white"/>
                                                                                                </clipPath>
                                                                                                </defs>
                                                                                            </svg>
                                                                                <h5>No conversation</h5></div>`;

                            $('#messages').html(messgehtml);


                            var lihtml = '';

                            lihtml +=
                                `<li class="nav-item no-tickets text-center"> <p>No tickets Avaliable </p></li>`;

                            $('#myUL').html(lihtml);
                        }
                    },
                });
            } else {
                location.reload();
            }
        });

        // load more tickets
        $(document).on('click', '#load_more', function () {
            var lastTicketId = $('#myUL li:last').attr('id');
            var ticketType = $('#tikcettype').val();
            var loadbtn = $('.load-more-btn');
            loadbtn.addClass('loading');

            setTimeout(() => {
                $.ajax({
                    url: "{{ route('admin.get.all.tickets') }}",
                    type: 'GET',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "ticketType": ticketType,
                        "lastTicketId": lastTicketId,
                    },
                    success: function (data) {
                        if (data.tickets && data.tickets.length > 0) {
                            var ticketHtml = '';
                            $.each(data.tickets, function (index, ticket) {
                                var createdAtFormatted = moment(ticket.created_at).fromNow();
                                let avatarSrc = LetterAvatar(ticket.name, 100);
                                var description = ticket.latest_message ? ticket.latest_message : '';
                                var unread = ticket.unread ? ticket.unread : '';
                                var ticketClass = (ticket.is_mark && ticket.is_mark == 1) ? 'ticket-danger' : '';

                                ticketHtml += `<li class="nav-item user_chat" id="${ticket.id}">
                                                                                                                                                    <div class="social-chat">
                                                                                                                                                        <div class="social-chat-img chat_users_img">
                                                                                                                                                            <img alt="${ticket.name}" class="img-fluid" avatar="${ticket.name}" src="${avatarSrc}">
                                                                                                                                                        </div>
                                                                                                                                                        <div class="user-info flex-1">
                                                                                                                                                            <span class="user-name chat_users_${ticket.id} ${ticketClass}">${ticket.name}</span>
                                                                                                                                                            <p class="chat-user ${unread > 0 ? 'not-read' : ''}" id="not_read_${ticket.id}">
                                                                                                                                                                ${description}
                                                                                                                                                            </p>
                                                                                                                                                        </div>
                                                                                                                                                        <input type="hidden" class="ticket_subject" value="${ticket.subject}">
                                                                                                                                                        <input type="hidden" class="ticket_category" value="${ticket.getCategory ? ticket.getCategory.name : '---'}">
                                                                                                                                                        <input type="hidden" class="ticket_priority" value="${ticket.getPriority ? ticket.getPriority.name : '---'}">
                                                                                                                                                        <input type="hidden" class="ticket_category_color" value="${ticket.getCategory ? ticket.getCategory.color : '---'}">
                                                                                                                                                        <input type="hidden" class="ticket_priority_color" value="${ticket.getPriority ? ticket.getPriority.color : '---'}">
                                                                                                                                                        <input type="hidden" class="ticket_status" value="${ticket.status ? ticket.status : '---'}">
                                                                                                                                                        <input type="hidden" class="ticket_tag_color" value="${ticket.getTagsAttribute ? ticket.getTagsAttribute.color : '---'}">
                                                                                                                                                        <input type="hidden" class="ticket_mark_important" value="${ticket.is_mark}">
                                                                                                                                                        <input type="hidden" class="ticket_chat_pin" value="${ticket.is_pin}">
                                                                                                                                                        <div class="chat-pin-icon"> </div>

                                                                                                                                                        <div class="social-icon-wrp">
                                                                                                                                                                 ${ticket.type === 'Whatsapp' ? `
                                                                                                                                                                                                 <a href="javascript:;">
                                                                                                                                                                                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                                                                                                                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                                                                                                                                        <g clip-path="url(#clip0_51_375)">
                                                                                                                                                                                                            <path
                                                                                                                                                                                                                d="M6 12C9.31371 12 12 9.31371 12 6C12 2.68629 9.31371 0 6 0C2.68629 0 0 2.68629 0 6C0 9.31371 2.68629 12 6 12Z"
                                                                                                                                                                                                                fill="#29A71A" />
                                                                                                                                                                                                            <path
                                                                                                                                                                                                                d="M8.6454 3.35454C8.02115 2.72406 7.19214 2.33741 6.30792 2.26433C5.4237 2.19125 4.54247 2.43654 3.82318 2.95598C3.10388 3.47541 2.59389 4.23478 2.38518 5.09712C2.17647 5.95946 2.28278 6.868 2.68494 7.65886L2.29017 9.57545C2.28607 9.59452 2.28596 9.61424 2.28983 9.63336C2.2937 9.65249 2.30148 9.67061 2.31267 9.68659C2.32907 9.71084 2.35247 9.72951 2.37976 9.74011C2.40705 9.75071 2.43693 9.75273 2.4654 9.7459L4.34381 9.30068C5.13244 9.69266 6.03456 9.79214 6.88966 9.58141C7.74475 9.37068 8.49735 8.86342 9.01355 8.14988C9.52974 7.43634 9.77604 6.56281 9.70863 5.68471C9.64122 4.80662 9.26446 3.98092 8.6454 3.35454ZM8.05971 8.02909C7.6278 8.45979 7.07162 8.7441 6.46955 8.84196C5.86749 8.93981 5.24989 8.84628 4.70381 8.57454L4.44199 8.44499L3.2904 8.71772L3.29381 8.7034L3.53244 7.54431L3.40426 7.29136C3.12523 6.74336 3.0268 6.12111 3.12307 5.51375C3.21934 4.90639 3.50536 4.34508 3.94017 3.91022C4.48651 3.36405 5.22741 3.05722 5.99994 3.05722C6.77247 3.05722 7.51337 3.36405 8.05971 3.91022C8.06437 3.91556 8.06938 3.92057 8.07471 3.92522C8.61429 4.4728 8.9155 5.21149 8.91269 5.98024C8.90988 6.74899 8.60327 7.48546 8.05971 8.02909Z"
                                                                                                                                                                                                                fill="white" />
                                                                                                                                                                                                            <path
                                                                                                                                                                                                                d="M7.95745 7.17885C7.81632 7.40112 7.59336 7.67317 7.31314 7.74067C6.82223 7.85931 6.06882 7.74476 5.13132 6.87067L5.11973 6.86044C4.29541 6.09613 4.08132 5.45999 4.13314 4.95544C4.16177 4.66908 4.40041 4.40999 4.60155 4.2409C4.63334 4.21376 4.67105 4.19443 4.71166 4.18447C4.75226 4.17451 4.79463 4.17419 4.83538 4.18353C4.87613 4.19288 4.91412 4.21162 4.94633 4.23828C4.97854 4.26493 5.00406 4.29875 5.02086 4.33703L5.32427 5.01885C5.34399 5.06306 5.3513 5.1118 5.34541 5.15985C5.33952 5.2079 5.32067 5.25344 5.29086 5.29158L5.13745 5.49067C5.10454 5.53178 5.08467 5.5818 5.08042 5.63429C5.07617 5.68678 5.08772 5.73934 5.11359 5.78522C5.1995 5.9359 5.40541 6.15749 5.63382 6.36272C5.89018 6.59453 6.1745 6.80658 6.3545 6.87885C6.40266 6.89853 6.45562 6.90333 6.50654 6.89264C6.55745 6.88194 6.604 6.85624 6.64018 6.81885L6.81814 6.63953C6.85247 6.60567 6.89517 6.58153 6.94189 6.56955C6.9886 6.55757 7.03765 6.55819 7.08405 6.57135L7.80473 6.7759C7.84448 6.78809 7.88092 6.80922 7.91126 6.83765C7.9416 6.86609 7.96503 6.90109 7.97977 6.93997C7.99451 6.97886 8.00016 7.0206 7.99629 7.062C7.99242 7.1034 7.97914 7.14337 7.95745 7.17885Z"
                                                                                                                                                                                                                fill="white" />
                                                                                                                                                                                                        </g>
                                                                                                                                                                                                        <defs>
                                                                                                                                                                                                            <clipPath id="clip0_51_375">
                                                                                                                                                                                                                <rect width="12" height="12" fill="white" />
                                                                                                                                                                                                            </clipPath>
                                                                                                                                                                                                        </defs>
                                                                                                                                                                                                    </svg>

                                                                                                                                                                                                </a>
                                                                                                                                                                                            ` : ticket.type === 'Instagram' ? `
                                                                                                                                                                                                 <a href="javascript:;">
                                                                                                                                                                                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                                                                                                                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                                                                                                                                        <g clip-path="url(#clip0_51_298)">
                                                                                                                                                                                                            <path
                                                                                                                                                                                                                d="M9.23313 0H2.76687C1.23877 0 0 1.23877 0 2.76687V9.23313C0 10.7612 1.23877 12 2.76687 12H9.23313C10.7612 12 12 10.7612 12 9.23313V2.76687C12 1.23877 10.7612 0 9.23313 0Z"
                                                                                                                                                                                                                fill="url(#paint0_linear_51_298)" />
                                                                                                                                                                                                            <path
                                                                                                                                                                                                                d="M7.93435 2.36992C8.38969 2.37175 8.82585 2.55341 9.14783 2.87531C9.46981 3.19722 9.6515 3.63329 9.65334 4.08852V7.91148C9.6515 8.36671 9.46981 8.80278 9.14783 9.12469C8.82585 9.44659 8.38969 9.62825 7.93435 9.63008H4.06565C3.61031 9.62825 3.17415 9.44659 2.85217 9.12469C2.53019 8.80278 2.3485 8.36671 2.34666 7.91148V4.08852C2.3485 3.63329 2.53019 3.19722 2.85217 2.87531C3.17415 2.55341 3.61031 2.37175 4.06565 2.36992H7.93435ZM7.93435 1.57057H4.06565C2.6804 1.57057 1.54688 2.70513 1.54688 4.08878V7.91148C1.54688 9.29642 2.68169 10.4297 4.06565 10.4297H7.93435C9.3196 10.4297 10.4531 9.29513 10.4531 7.91148V4.08852C10.4531 2.70358 9.3196 1.57031 7.93435 1.57031V1.57057Z"
                                                                                                                                                                                                                fill="white" />
                                                                                                                                                                                                            <path
                                                                                                                                                                                                                d="M6 4.50433C6.29582 4.50433 6.58499 4.59205 6.83095 4.7564C7.07691 4.92074 7.26862 5.15433 7.38182 5.42763C7.49502 5.70093 7.52464 6.00166 7.46693 6.29179C7.40922 6.58192 7.26677 6.84842 7.0576 7.0576C6.84842 7.26677 6.58192 7.40922 6.29179 7.46693C6.00166 7.52464 5.70093 7.49502 5.42763 7.38182C5.15433 7.26861 4.92074 7.07691 4.7564 6.83095C4.59205 6.58499 4.50433 6.29581 4.50433 6C4.50481 5.60347 4.66254 5.22332 4.94293 4.94293C5.22332 4.66254 5.60347 4.50481 6 4.50433ZM6 3.70313C5.54572 3.70312 5.10164 3.83783 4.72393 4.09022C4.34621 4.3426 4.05181 4.70132 3.87797 5.12102C3.70412 5.54072 3.65863 6.00255 3.74726 6.4481C3.83589 6.89365 4.05464 7.30291 4.37587 7.62413C4.69709 7.94536 5.10635 8.16411 5.5519 8.25274C5.99745 8.34137 6.45928 8.29588 6.87898 8.12203C7.29868 7.94819 7.6574 7.65379 7.90978 7.27607C8.16217 6.89836 8.29688 6.45428 8.29688 6C8.29688 5.39083 8.05488 4.80661 7.62414 4.37586C7.19339 3.94512 6.60917 3.70313 6 3.70313Z"
                                                                                                                                                                                                                fill="white" />
                                                                                                                                                                                                            <path
                                                                                                                                                                                                                d="M8.34375 4.14844C8.64147 4.14844 8.88281 3.90709 8.88281 3.60937C8.88281 3.31166 8.64147 3.07031 8.34375 3.07031C8.04603 3.07031 7.80469 3.31166 7.80469 3.60937C7.80469 3.90709 8.04603 4.14844 8.34375 4.14844Z"
                                                                                                                                                                                                                fill="white" />
                                                                                                                                                                                                        </g>
                                                                                                                                                                                                        <defs>
                                                                                                                                                                                                            <linearGradient id="paint0_linear_51_298" x1="7.86479" y1="12.5037"
                                                                                                                                                                                                                x2="4.13521" y2="-0.503678" gradientUnits="userSpaceOnUse">
                                                                                                                                                                                                                <stop stop-color="#FFDB73" />
                                                                                                                                                                                                                <stop offset="0.08" stop-color="#FDAD4E" />
                                                                                                                                                                                                                <stop offset="0.15" stop-color="#FB832E" />
                                                                                                                                                                                                                <stop offset="0.19" stop-color="#FA7321" />
                                                                                                                                                                                                                <stop offset="0.23" stop-color="#F6692F" />
                                                                                                                                                                                                                <stop offset="0.37" stop-color="#E84A5A" />
                                                                                                                                                                                                                <stop offset="0.48" stop-color="#E03675" />
                                                                                                                                                                                                                <stop offset="0.55" stop-color="#DD2F7F" />
                                                                                                                                                                                                                <stop offset="0.68" stop-color="#B43D97" />
                                                                                                                                                                                                                <stop offset="0.97" stop-color="#4D60D4" />
                                                                                                                                                                                                                <stop offset="1" stop-color="#4264DB" />
                                                                                                                                                                                                            </linearGradient>
                                                                                                                                                                                                            <clipPath id="clip0_51_298">
                                                                                                                                                                                                                <rect width="12" height="12" fill="white" />
                                                                                                                                                                                                            </clipPath>
                                                                                                                                                                                                        </defs>
                                                                                                                                                                                                    </svg>
                                                                                                                                                                                                </a>
                                                                                                                                                                                                ` : ticket.type === 'Facebook' ? `
                                                                                                                                                                                                    <a href="javascript:;">
                                                                                                                                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 15 16" fill="url(#Ld6sqrtcxMyckEl6xeDdMa)">
                                                                                                                                                                                                        <g clip-path="url(#clip0_17_3195)">
                                                                                                                                                                                                        <path d="M14.8586 7.95076C14.8586 3.89299 11.5691 0.603516 7.51131 0.603516C3.45353 0.603516 0.164062 3.89299 0.164062 7.95076C0.164062 11.6179 2.85083 14.6576 6.3633 15.2088V10.0746H4.49779V7.95076H6.3633V6.33207C6.3633 4.49067 7.46022 3.47353 9.13847 3.47353C9.94207 3.47353 10.7831 3.61703 10.7831 3.61703V5.42515H9.85669C8.94402 5.42515 8.65932 5.99154 8.65932 6.57315V7.95076H10.697L10.3713 10.0746H8.65932V15.2088C12.1718 14.6576 14.8586 11.6179 14.8586 7.95076Z" fill="#0017A8"></path>
                                                                                                                                                                                                        </g>
                                                                                                                                                                                                        <defs>
                                                                                                                                                                                                        <clipPath id="clip0_17_3195">
                                                                                                                                                                                                        <rect width="14.6945" height="14.6945" fill="white" transform="translate(0.164062 0.603516)"></rect>
                                                                                                                                                                                                        </clipPath>
                                                                                                                                                                                                        </defs>
                                                                                                                                                                                                    </svg>
                                                                                                                                                                                                    </a>
                                                                                                                                                                                            ` : ticket.type === 'Unassigned' || ticket.type === 'Assigned' ? `
                                                                                                                                                                                                <a href="javascript:;">
                                                                                                                                                                                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                                                                                                                                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                                                                                                                                                        <g clip-path="url(#clip0_5_426)">
                                                                                                                                                                                                            <path
                                                                                                                                                                                                                d="M9.59978 0.00012207H2.40013C1.76364 0.00012207 1.15322 0.252966 0.703154 0.703032C0.253088 1.1531 0.000244141 1.76352 0.000244141 2.40001V7.19977C0.000244141 7.83626 0.253088 8.44668 0.703154 8.89675C1.15322 9.34681 1.76364 9.59966 2.40013 9.59966V11.3996C2.39943 11.5187 2.43425 11.6354 2.50012 11.7347C2.566 11.834 2.65996 11.9115 2.77002 11.9572C2.88008 12.0029 3.00126 12.0148 3.1181 11.9913C3.23494 11.9679 3.34216 11.9102 3.42608 11.8255L5.64597 9.59966H9.59978C10.2363 9.59966 10.8467 9.34681 11.2968 8.89675C11.7468 8.44668 11.9997 7.83626 11.9997 7.19977V2.40001C11.9997 1.76352 11.7468 1.1531 11.2968 0.703032C10.8467 0.252966 10.2363 0.00012207 9.59978 0.00012207Z"
                                                                                                                                                                                                                fill="#2675E2" />
                                                                                                                                                                                                            <path
                                                                                                                                                                                                                d="M3.3001 5.69985C3.79714 5.69985 4.20006 5.29692 4.20006 4.79989C4.20006 4.30286 3.79714 3.89993 3.3001 3.89993C2.80307 3.89993 2.40015 4.30286 2.40015 4.79989C2.40015 5.29692 2.80307 5.69985 3.3001 5.69985Z"
                                                                                                                                                                                                                fill="#EDEBEA" />
                                                                                                                                                                                                            <path
                                                                                                                                                                                                                d="M6.00005 5.69985C6.49709 5.69985 6.90001 5.29692 6.90001 4.79989C6.90001 4.30286 6.49709 3.89993 6.00005 3.89993C5.50302 3.89993 5.1001 4.30286 5.1001 4.79989C5.1001 5.29692 5.50302 5.69985 6.00005 5.69985Z"
                                                                                                                                                                                                                fill="#EDEBEA" />
                                                                                                                                                                                                            <path
                                                                                                                                                                                                                d="M8.69988 5.69985C9.19692 5.69985 9.59984 5.29692 9.59984 4.79989C9.59984 4.30286 9.19692 3.89993 8.69988 3.89993C8.20285 3.89993 7.79993 4.30286 7.79993 4.79989C7.79993 5.29692 8.20285 5.69985 8.69988 5.69985Z"
                                                                                                                                                                                                                fill="#EDEBEA" />
                                                                                                                                                                                                        </g>
                                                                                                                                                                                                        <defs>
                                                                                                                                                                                                            <clipPath id="clip0_5_426">
                                                                                                                                                                                                                <rect width="12" height="12" fill="white" />
                                                                                                                                                                                                            </clipPath>
                                                                                                                                                                                                        </defs>
                                                                                                                                                                                                    </svg>
                                                                                                                                                                                                </a>
                                                                                                                                                                                            ` : ''}
                                                                                                                                                                <span class="chat-time">${createdAtFormatted}</span>
                                                                                                                                                                ${unread > 0 ? `<span class="notification" id="unread_notification_${ticket.id}">${unread}</span>` : ''}
                                                                                                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                </li>
                                                                                                                                                `;
                            });

                            $('#myUL').append(ticketHtml);



                            $('#load-btn').html(`
                                                                                                                                                <button class="load-more-btn" id="load_more">
                                                                                                                                                    <i class="fa fa-spinner"></i>
                                                                                                                                                    <span>{{ __('Load More Conversations') }}</span>
                                                                                                                                                </button>

                                                                                                                                                `);

                        } else {
                            $('#load-btn').html(`
                                                                                                                                                    <button class="no-more-btn loading" id="no_more">
                                                                                                                                                        <span>No More Conversations</span>
                                                                                                                                                    </button>
                                                                                                                                                `);

                            setTimeout(() => {
                                $('#no_more').removeClass('loading');
                            }, 500);
                        }
                    }
                });
            }, 500);
        });
    </script>

    {{-- This Pusher Code For Live Chating & Messages --}}
    @if (isset($settings['CHAT_MODULE']) && $settings['CHAT_MODULE'] == 'yes')
        <script>
            Pusher.logToConsole = false;
            var pusher = new Pusher(
                '{{ isset($settings['PUSHER_APP_KEY']) && $settings['PUSHER_APP_KEY'] ? $settings['PUSHER_APP_KEY'] : '' }}', {
                cluster: '{{ isset($settings['PUSHER_APP_CLUSTER']) && $settings['PUSHER_APP_CLUSTER'] ? $settings['PUSHER_APP_CLUSTER'] : '' }}',
                forceTLS: true
            });


            // Subscribe to the Pusher channel after getting the ticket reply
            var channel = pusher.subscribe('ticket-reply-{{ auth()->user()->id }}');
            channel.bind('ticket-reply-event-{{ auth()->user()->id }}', function (data) {
                let avatarSrc = data.profile_img ? data.profile_img : LetterAvatar(data.sender_name,
                    100);
                if (ticket_id == data.ticket_unique_id) {
                    var ticketItem = $('#myUL').find('li#' + data.ticket_unique_id);
                    ticketItem.find('.chat-user').text(data.latestMessage);

                    const messageList = $('.messages-container');
                    var newMessage = `
                                                <div class="msg left-msg">
                                                    <div class="msg-box" data-conversion-id="${data.id}">
                                                        <div class="msg-user-info" data-bs-toggle="tooltip" data-bs-placement="top" title="${data.sender_name}">
                                                            <div class="msg-img">
                                                                <img alt="${data.sender_name}" class="img-fluid" src="${avatarSrc}" />
                                                            </div>
                                                        </div>
                                                        <div class="msg-box-content">
                                                            <div class="msg-box-inner">
                                                            <p>${data.new_message}</p>
                                                                ${data.attachments ? `
                                                                    <div class="attachments-wrp">
                                                                        <h6>Attachments:</h6>
                                                                        <ul class="attachments-list">
                                                                            ${data.attachments.map(function (attachment) {
                        var filename = attachment.split('/').pop(); // Extract filename
                        var fullUrl = data.baseUrl + attachment;
                        return `
                                                                                    <li>
                                                                                        ${filename}
                                                                                        <a download href="${fullUrl}" class="edit-icon py-1 ml-2" title="Download">
                                                                                            <i class="fa fa-download ms-2"></i>
                                                                                        </a>
                                                                                    </li>
                                                                                `;
                    }).join('')}
                                                                        </ul>
                                                                    </div>
                                                                ` : ''}
                                                            </div>
                                                           <span>${data.timestamp}</span>
                                                        </div>
                                                    </div>
                                                </div>`;

                    messageList.append(newMessage);
                    $('.chat-container').scrollTop($('.chat-container')[0].scrollHeight);

                    LetterAvatar.transform();

                    $.ajax({
                        url: "{{ url('/admin/readmessge') }}" + '/' + data.ticket_unique_id,
                        type: 'GET',
                        cache: false,
                        success: function (data) {

                            if (data.status == 'error') {
                                show_toastr('Error', data.message, 'error');
                            }
                        }
                    });
                } else {
                    // When not in active use and message received from user
                    var ticketItem = $('#myUL').find('li#' + data.ticket_unique_id);

                    if (ticketItem.length > 0) {
                        $('#myUL').prepend(ticketItem);
                        ticketItem.find('.chat-time').text(data.timestamp);

                        var notification = ticketItem.find('.social-icon-wrp .notification');
                        if (notification.length > 0) {
                            if (data.unreadMessge > 0) {
                                ticketItem.find('.chat-time').text(data.timestamp);
                                notification.removeClass('d-none');
                                notification.text(data.unreadMessge);
                            }
                        } else {
                            var unreadhtml = `
                                            <a href="javascript:;">
                                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <g clip-path="url(#clip0_5_426)">
                                                        <path
                                                            d="M9.59978 0.00012207H2.40013C1.76364 0.00012207 1.15322 0.252966 0.703154 0.703032C0.253088 1.1531 0.000244141 1.76352 0.000244141 2.40001V7.19977C0.000244141 7.83626 0.253088 8.44668 0.703154 8.89675C1.15322 9.34681 1.76364 9.59966 2.40013 9.59966V11.3996C2.39943 11.5187 2.43425 11.6354 2.50012 11.7347C2.566 11.834 2.65996 11.9115 2.77002 11.9572C2.88008 12.0029 3.00126 12.0148 3.1181 11.9913C3.23494 11.9679 3.34216 11.9102 3.42608 11.8255L5.64597 9.59966H9.59978C10.2363 9.59966 10.8467 9.34681 11.2968 8.89675C11.7468 8.44668 11.9997 7.83626 11.9997 7.19977V2.40001C11.9997 1.76352 11.7468 1.1531 11.2968 0.703032C10.8467 0.252966 10.2363 0.00012207 9.59978 0.00012207Z"
                                                            fill="#2675E2"
                                                        />
                                                        <path
                                                            d="M3.3001 5.69985C3.79714 5.69985 4.20006 5.29692 4.20006 4.79989C4.20006 4.30286 3.79714 3.89993 3.3001 3.89993C2.80307 3.89993 2.40015 4.30286 2.40015 4.79989C2.40015 5.29692 2.80307 5.69985 3.3001 5.69985Z"
                                                            fill="#EDEBEA"
                                                        />
                                                        <path
                                                            d="M6.00005 5.69985C6.49709 5.69985 6.90001 5.29692 6.90001 4.79989C6.90001 4.30286 6.49709 3.89993 6.00005 3.89993C5.50302 3.89993 5.1001 4.30286 5.1001 4.79989C5.1001 5.29692 5.50302 5.69985 6.00005 5.69985Z"
                                                            fill="#EDEBEA"
                                                        />
                                                        <path
                                                            d="M8.69988 5.69985C9.19692 5.69985 9.59984 5.29692 9.59984 4.79989C9.59984 4.30286 9.19692 3.89993 8.69988 3.89993C8.20285 3.89993 7.79993 4.30286 7.79993 4.79989C7.79993 5.29692 8.20285 5.69985 8.69988 5.69985Z"
                                                            fill="#EDEBEA"
                                                        />
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_5_426">
                                                            <rect width="12" height="12" fill="white" />
                                                        </clipPath>
                                                    </defs>
                                                </svg>
                                            </a>
                                            <span class="chat-time">${data.timestamp}</span>
                                            ${data.unreadMessge > 0
                                    ? `<span class="notification" id="unread_notification_${data.tikcet_id}">${data.unreadMessge}</span>`
                                    : ''}
                                        `;

                            ticketItem.find('.social-icon-wrp').html(unreadhtml);
                        }

                        ticketItem.find('.chat-user').addClass('not-read');
                        ticketItem.find('.chat-user').text(data.latestMessage);
                    }

                }
            });
        </script>
    @endif

    {{-- This Pusher Code For Frontend Ticket Create & live chat ticket create --}}
    @if (auth()->user()->id == 1 && (isset($settings['CHAT_MODULE']) && $settings['CHAT_MODULE'] == 'yes'))
        <script>
            Pusher.logToConsole = false;

            var pusher = new Pusher(
                '{{ isset($settings['PUSHER_APP_KEY']) && $settings['PUSHER_APP_KEY'] ? $settings['PUSHER_APP_KEY'] : '' }}', {
                cluster: '{{ isset($settings['PUSHER_APP_CLUSTER']) && $settings['PUSHER_APP_CLUSTER'] ? $settings['PUSHER_APP_CLUSTER'] : '' }}',
                forceTLS: true
            });

            var channel = pusher.subscribe('new-ticket-1');
            channel.bind('new-ticket-event-1', function (data) {
                let avatarSrc = data.profile_img ? data.profile_img : LetterAvatar(data.name, 100);
                var ticketClass = (data.is_mark && data.is_mark == 1) ? 'ticket-danger' : '';
                var ticketHtml = `
                                             <li class="nav-item user_chat" id="${data.id}">
                                                 <div class="social-chat">
                                                     <div class="social-chat-img chat_users_img">
                                                         <img alt="${data.name}" class="img-fluid" src="${avatarSrc}">
                                                     </div>
                                                     <div class="user-info flex-1">
                                                         <span class="user-name chat_users_${data.id} ${ticketClass}">${data.name}</span>
                                                         <p class="chat-user ${data.unreadMessge > 0 ? 'not-read' : ''}" id="not_read_${data.id}">
                                                             ${data.latestMessage}
                                                         </p>
                                                     </div>
                                                     <input type="hidden" class="ticket_subject" value="${data.subject}">
                                                     <input type="hidden" class="ticket_category" value="---">
                                                     <input type="hidden" class="ticket_priority" value="---">
                                                     <input type="hidden" class="ticket_category_color" value="---">
                                                     <input type="hidden" class="ticket_priority_color" value="---">
                                                     <input type="hidden" class="ticket_status" value="${data.status ? data.status : '---'}">
                                                     <div class="social-icon-wrp">
                                                         ${data.type === 'Whatsapp' ? `
                                                             <a href="javascript:;">
                                                                 <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                     <g clip-path="url(#clip0_51_375)">
                                                                         <path d="M6 12C9.31371 12 12 9.31371 12 6C12 2.68629 9.31371 0 6 0C2.68629 0 0 2.68629 0 6C0 9.31371 2.68629 12 6 12Z" fill="#29A71A" />
                                                                         <path d="M8.6454 3.35454C8.02115 2.72406 7.19214 2.33741 6.30792 2.26433C5.4237 2.19125 4.54247 2.43654 3.82318 2.95598C3.10388 3.47541 2.59389 4.23478 2.38518 5.09712C2.17647 5.95946 2.28278 6.868 2.68494 7.65886L2.29017 9.57545C2.28607 9.59452 2.28596 9.61424 2.28983 9.63336C2.2937 9.65249 2.30148 9.67061 2.31267 9.68659C2.32907 9.71084 2.35247 9.72951 2.37976 9.74011C2.40705 9.75071 2.43693 9.75273 2.4654 9.7459L4.34381 9.30068C5.13244 9.69266 6.03456 9.79214 6.88966 9.58141C7.74475 9.37068 8.49735 8.86342 9.01355 8.14988C9.52974 7.43634 9.77604 6.56281 9.70863 5.68471C9.64122 4.80662 9.26446 3.98092 8.6454 3.35454ZM8.05971 8.02909C7.6278 8.45979 7.07162 8.7441 6.46955 8.84196C5.86749 8.93981 5.24989 8.84628 4.70381 8.57454L4.44199 8.44499L3.2904 8.71772L3.29381 8.7034L3.53244 7.54431L3.40426 7.29136C3.12523 6.74336 3.0268 6.12111 3.12307 5.51375C3.21934 4.90639 3.50536 4.34508 3.94017 3.91022C4.48651 3.36405 5.22741 3.05722 5.99994 3.05722C6.77247 3.05722 7.51337 3.36405 8.05971 3.91022C8.06437 3.91556 8.06938 3.92057 8.07471 3.92522C8.61429 4.4728 8.9155 5.21149 8.91269 5.98024C8.90988 6.74899 8.60327 7.48546 8.05971 8.02909Z" fill="white" />
                                                                         <path d="M7.95745 7.17885C7.81632 7.40112 7.59336 7.67317 7.31314 7.74067C6.82223 7.85931 6.06882 7.74476 5.13132 6.87067L5.11973 6.86044C4.29541 6.09613 4.08132 5.45999 4.13314 4.95544C4.16177 4.66908 4.40041 4.40999 4.60155 4.2409C4.63334 4.21376 4.67105 4.19443 4.71166 4.18447C4.75226 4.17451 4.79463 4.17419 4.83538 4.18353C4.87613 4.19288 4.91412 4.21162 4.94633 4.23828C4.97854 4.26493 5.00406 4.29875 5.02086 4.33703L5.32427 5.01885C5.34399 5.06306 5.3513 5.1118 5.34541 5.15985C5.33952 5.2079 5.32067 5.25344 5.29086 5.29158L5.13745 5.49067C5.10454 5.53178 5.08467 5.5818 5.08042 5.63429C5.07617 5.68678 5.08772 5.73934 5.11359 5.78522C5.1995 5.9359 5.40541 6.15749 5.63382 6.36272C5.89018 6.59453 6.1745 6.80658 6.3545 6.87885C6.40266 6.89853 6.45562 6.90333 6.50654 6.89264C6.55745 6.88194 6.604 6.85624 6.64018 6.81885L6.81814 6.63953C6.85247 6.60567 6.89517 6.58153 6.94189 6.56955C6.9886 6.55757 7.03765 6.55819 7.08405 6.57135L7.80473 6.7759C7.84448 6.78809 7.88092 6.80922 7.91126 6.83765C7.9416 6.86609 7.96503 6.90109 7.97977 6.93997C7.99451 6.97886 8.00016 7.0206 7.99629 7.062C7.99242 7.1034 7.97914 7.14337 7.95745 7.17885Z" fill="white" />
                                                                     </g>
                                                                     <defs>
                                                                         <clipPath id="clip0_51_375">
                                                                             <rect width="12" height="12" fill="white" />
                                                                         </clipPath>
                                                                     </defs>
                                                                 </svg>
                                                             </a>
                                                         ` : data.type === 'Instagram' ? `
                                                             <a href="javascript:;">
                                                                 <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                     <g clip-path="url(#clip0_51_298)">
                                                                         <path d="M9.23313 0H2.76687C1.23877 0 0 1.23877 0 2.76687V9.23313C0 10.7612 1.23877 12 2.76687 12H9.23313C10.7612 12 12 10.7612 12 9.23313V2.76687C12 1.23877 10.7612 0 9.23313 0Z" fill="url(#paint0_linear_51_298)" />
                                                                         <path d="M7.93435 2.36992C8.38969 2.37175 8.82585 2.55341 9.14783 2.87531C9.46981 3.19722 9.6515 3.63329 9.65334 4.08852V7.91148C9.6515 8.36671 9.46981 8.80278 9.14783 9.12469C8.82585 9.44659 8.38969 9.62825 7.93435 9.63008H4.06565C3.61031 9.62825 3.17415 9.44659 2.85217 9.12469C2.53019 8.80278 2.3485 8.36671 2.34666 7.91148V4.08852C2.3485 3.63329 2.53019 3.19722 2.85217 2.87531C3.17415 2.55341 3.61031 2.37175 4.06565 2.36992H7.93435ZM7.93435 1.57057H4.06565C2.6804 1.57057 1.54688 2.70513 1.54688 4.08878V7.91148C1.54688 9.29642 2.68169 10.4297 4.06565 10.4297H7.93435C9.3196 10.4297 10.4531 9.29513 10.4531 7.91148V4.08852C10.4531 2.70358 9.3196 1.57031 7.93435 1.57031V1.57057Z" fill="white" />
                                                                         <path d="M6 4.50433C6.29582 4.50433 6.58499 4.59205 6.83095 4.7564C7.07691 4.92074 7.26862 5.15433 7.38182 5.42763C7.49502 5.70093 7.52464 6.00166 7.46693 6.29179C7.40922 6.58192 7.26677 6.84842 7.0576 7.0576C6.84842 7.26677 6.58192 7.40922 6.29179 7.46693C6.00166 7.52464 5.70093 7.49502 5.42763 7.38182C5.15433 7.26861 4.92074 7.07691 4.7564 6.83095C4.59205 6.58499 4.50433 6.29581 4.50433 6C4.50481 5.60347 4.66254 5.22332 4.94293 4.94293C5.22332 4.66254 5.60347 4.50481 6 4.50433ZM6 3.70313C5.54572 3.70312 5.10164 3.83783 4.72393 4.09022C4.34621 4.3426 4.05181 4.70132 3.87797 5.12102C3.70412 5.54072 3.65863 6.00255 3.74726 6.4481C3.83589 6.89365 4.05464 7.30291 4.37587 7.62413C4.69709 7.94536 5.10635 8.16411 5.5519 8.25274C5.99745 8.34137 6.45928 8.29588 6.87898 8.12203C7.29868 7.94819 7.6574 7.65379 7.90978 7.27607C8.16217 6.89836 8.29688 6.45428 8.29688 6C8.29688 5.39083 8.05488 4.80661 7.62414 4.37586C7.19339 3.94512 6.60917 3.70313 6 3.70313Z" fill="white" />
                                                                         <path d="M8.34375 4.14844C8.64147 4.14844 8.88281 3.90709 8.88281 3.60937C8.88281 3.31166 8.64147 3.07031 8.34375 3.07031C8.04603 3.07031 7.80469 3.31166 7.80469 3.60937C7.80469 3.90709 8.04603 4.14844 8.34375 4.14844Z" fill="white" />
                                                                     </g>
                                                                     <defs>
                                                                         <linearGradient id="paint0_linear_51_298" x1="7.86479" y1="12.5037" x2="4.13521" y2="-0.503678" gradientUnits="userSpaceOnUse">
                                                                             <stop stop-color="#FFDB73" />
                                                                             <stop offset="0.08" stop-color="#FDAD4E" />
                                                                             <stop offset="0.15" stop-color="#FB832E" />
                                                                             <stop offset="0.19" stop-color="#FA7321" />
                                                                             <stop offset="0.23" stop-color="#F6692F" />
                                                                             <stop offset="0.37" stop-color="#E84A5A" />
                                                                             <stop offset="0.48" stop-color="#E03675" />
                                                                             <stop offset="0.55" stop-color="#DD2F7F" />
                                                                             <stop offset="0.68" stop-color="#B43D97" />
                                                                             <stop offset="0.97" stop-color="#4D60D4" />
                                                                             <stop offset="1" stop-color="#4264DB" />
                                                                         </linearGradient>
                                                                         <clipPath id="clip0_51_298">
                                                                             <rect width="12" height="12" fill="white" />
                                                                         </clipPath>
                                                                     </defs>
                                                                 </svg>
                                                             </a>
                                                         ` : data.type === 'Facebook' ? `
                                                             <a href="javascript:;">
                                                                 <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 15 16" fill="url(#Ld6sqrtcxMyckEl6xeDdMa)">
                                                                     <g clip-path="url(#clip0_17_3195)">
                                                                         <path d="M14.8586 7.95076C14.8586 3.89299 11.5691 0.603516 7.51131 0.603516C3.45353 0.603516 0.164062 3.89299 0.164062 7.95076C0.164062 11.6179 2.85083 14.6576 6.3633 15.2088V10.0746H4.49779V7.95076H6.3633V6.33207C6.3633 4.49067 7.46022 3.47353 9.13847 3.47353C9.94207 3.47353 10.7831 3.61703 10.7831 3.61703V5.42515H9.85669C8.94402 5.42515 8.65932 5.99154 8.65932 6.57315V7.95076H10.697L10.3713 10.0746H8.65932V15.2088C12.1718 14.657 griff6 14.8586 11.6179 14.8586 7.95076Z" fill="#0017A8"></path>
                                                                     </g>
                                                                     <defs>
                                                                         <clipPath id="clip0_17_3195">
                                                                             <rect width="14.6945" height="14.6945" fill="white" transform="translate(0.164062 0.603516)"></rect>
                                                                         </clipPath>
                                                                     </defs>
                                                                 </svg>
                                                             </a>
                                                         ` : data.type === 'Unassigned' || data.type === 'Assigned' ? `
                                                             <a href="javascript:;">
                                                                 <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                     <g clip-path="url(#clip0_5_426)">
                                                                         <path d="M9.59978 0.00012207H2.40013C1.76364 0.00012207 1.15322 0.252966 0.703154 0.703032C0.253088 1.1531 0.000244141 1.76352 0.000244141 2.40001V7.19977C0.000244141 7.83626 0.253088 8.44668 0.703154 8.89675C1.15322 9.34681 1.76364 9.59966 2.40013 9.59966V11.3996C2.39943 11.5187 2.43425 11.6354 2.50012 11.7347C2.566 11.834 2.65996 11.9115 2.77002 11.9572C2.88008 12.0029 3.00126 12.0148 3.1181 11.9913C3.23494 11.9679 3.34216 11.9102 3.42608 11.8255L5.64597 9.59966H9.59978C10.2363 9.59966 10.8467 9.34681 11.2968 8.89675C11.7468 8.44668 11.9997 7.83626 11.9997 7.19977V2.40001C11.9997 1.76352 11.7468 1.1531 11.2968 0.703032C10.8467 0.252966 10.2363 0.00012207 9.59978 0.00012207Z" fill="#2675E2" />
                                                                         <path d="M3.3001 5.69985C3.79714 5.69985 4.20006 5.29692 4.20006 4.79989C4.20006 4.30286 3.79714 3.89993 3.3001 3.89993C2.80307 3.89993 2.40015 4.30286 2.40015 4.79989C2.40015 5.29692 2.80307 5.69985 3.3001 5.69985Z" fill="#EDEBEA" />
                                                                         <path d="M6.00005 5.69985C6.49709 5.69985 6.90001 5.29692 6.90001 4.79989C6.90001 4.30286 6.49709 3.89993 6.00005 3.89993C5.50302 3.89993 5.1001 4.30286 5.1001 4.79989C5.1001 5.29692 5.50302 5.69985 6.00005 5.69985Z" fill="#EDEBEA" />
                                                                         <path d="M8.69988 5.69985C9.19692 5.69985 9.59984 5.29692 9.59984 4.79989C9.59984 4.30286 9.19692 3.89993 8.69988 3.89993C8.20285 3.89993 7.79993 4.30286 7.79993 4.79989C7.79993 5.29692 8.20285 5.69985 8.69988 5.69985Z" fill="#EDEBEA" />
                                                                     </g>
                                                                     <defs>
                                                                         <clipPath id="clip0_5_426">
                                                                             <rect width="12" height="12" fill="white" />
                                                                         </clipPath>
                                                                     </defs>
                                                                 </svg>
                                                             </a>
                                                         ` : ''}
                                                         <span class="chat-time">${data.created_at}</span>
                                                         ${data.unreadMessge > 0 ? `<span class="notification" id="unread_notification_${data.id}">${data.unreadMessge}</span>` : ''}
                                                     </div>
                                                 </div>
                                             </li>`;

                // Prepend the new ticket ul li to the list
                $('#myUL').prepend(ticketHtml);
                // Remove the active class from the previously active ticket
                $('.user_chat.active').removeClass('active');

                // Add the active class to the new ticket
                var newTicket = $('#myUL').find('.user_chat').first();
                newTicket.addClass('active');

                loadTicketDetails(newTicket);
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchToggle = document.getElementById('searchToggle');
            const searchContainer = document.querySelector('.chat-header-left .input-wrp');
            const searchInput = searchContainer.querySelector('.form-control');
            const headerWrapper = document.querySelector('.chat-header-left-wrp');
        
            searchToggle.addEventListener('click', function (e) {
                e.stopPropagation(); // prevent bubbling
                const expanded = searchContainer.classList.toggle('expanded');
                headerWrapper.classList.toggle('hide-select', expanded);
                if (expanded) {
                    setTimeout(() => searchInput.focus(), 300); // Delay focus to allow transition
                }
            });
        
            document.addEventListener('click', function (e) {
                const isClickInside = searchContainer.contains(e.target) || searchToggle.contains(e.target);
                if (!isClickInside && searchContainer.classList.contains('expanded')) {
                    searchContainer.classList.remove('expanded');
                    headerWrapper.classList.remove('hide-select');
                }
            });
        });
        </script>
        
@endpush