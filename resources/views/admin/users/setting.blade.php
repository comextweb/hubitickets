@php
$color = isset($setting['color']) ? $setting['color'] : 'theme-3';
$flag = isset($setting['color_flag']) ? $setting['color_flag'] : 'false';
@endphp
@extends('layouts.admin')

@section('page-title')
{{ __('System Settings ') }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item">{{ __('System Settings') }}</li>
@endsection

@if ($color == 'theme-1')
<style>
.btn-check:checked+.btn-outline-success,
.btn-check:active+.btn-outline-success,
.btn-outline-success:active,
.btn-outline-success.active,
.btn-outline-success.dropdown-toggle.show {
    color: #ffffff;
    background: linear-gradient(141.55deg, rgba(81, 69, 157, 0) 3.46%, rgba(255, 58, 110, 0.6) 99.86%), #51459d !important;
    border-color: #51459d !important;

}

.btn-outline-success:hover {
    color: #ffffff;
    background: linear-gradient(141.55deg, rgba(81, 69, 157, 0) 3.46%, rgba(255, 58, 110, 0.6) 99.86%), #51459d !important;
    border-color: #51459d !important;
}

.btn.btn-outline-success {
    color: #51459d;
    border-color: #51459d !important;
}
</style>
@endif

@if ($color == 'theme-2')
<style>
.btn-check:checked+.btn-outline-success,
.btn-check:active+.btn-outline-success,
.btn-outline-success:active,
.btn-outline-success.active,
.btn-outline-success.dropdown-toggle.show {
    color: #ffffff;
    background: linear-gradient(141.55deg, rgba(240, 244, 243, 0) 3.46%, #4ebbd3 99.86%)#1f3996 !important;
    border-color: #1F3996 !important;

}

.btn-outline-success:hover {
    color: #ffffff;
    background: linear-gradient(141.55deg, rgba(240, 244, 243, 0) 3.46%, #4ebbd3 99.86%)#1f3996 !important;
    border-color: #1F3996 !important;
}

.btn.btn-outline-success {
    color: #1F3996;
    border-color: #1F3996 !important;
}
</style>
@endif

@if ($color == 'theme-4')
<style>
.btn-check:checked+.btn-outline-success,
.btn-check:active+.btn-outline-success,
.btn-outline-success:active,
.btn-outline-success.active,
.btn-outline-success.dropdown-toggle.show {
    color: #ffffff;
    background-color: #584ed2 !important;
    border-color: #584ed2 !important;

}

.btn-outline-success:hover {
    color: #ffffff;
    background-color: #584ed2 !important;
    border-color: #584ed2 !important;
}

.btn.btn-outline-success {
    color: #584ed2;
    border-color: #584ed2 !important;
}
</style>
@endif

@if ($color == 'theme-3')
<style>
.btn-check:checked+.btn-outline-success,
.btn-check:active+.btn-outline-success,
.btn-outline-success:active,
.btn-outline-success.active,
.btn-outline-success.dropdown-toggle.show {
    color: #ffffff;
    background-color: #6fd943 !important;
    border-color: #6fd943 !important;

}

.btn-outline-success:hover {
    color: #ffffff;
    background-color: #6fd943 !important;
    border-color: #6fd943 !important;
}

.btn.btn-outline-success {
    color: #6fd943;
    border-color: #6fd943 !important;
}
</style>
<style>
.radio-button-group .radio-button {
    position: absolute;
    width: 1px;
    height: 1px;
    opacity: 0;
}
</style>
@endif

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-xl-3">
                <div class="card sticky-top setting-sidebar" style="top:30px">
                    <div class="list-group list-group-flush" id="useradd-sidenav">
                        {!! getCompanySettingMenu() !!}
                    </div>
                </div>
            </div>
            <div class="col-xl-9 setting-menu-div">
                {{-- Brand Settings --}}
                <div id="logo-settings" class="card">
                    <div class="card-header">
                        <h5>{{ __('Brand settings') }}</h5>
                    </div>
                    <form action="{{ route('admin.settings.store') }}" method="post" enctype="multipart/form-data"
                        class="needs-validation" novalidate>
                        @csrf
                        <div class="card-body">
                            <div class="row row-gap-1 mb-4">
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="card mb-0">
                                        <div class="card-header">
                                            <h5 class="small-title">{{ __('Dark Logo') }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="setting-card setting-logo-box">
                                                <a href="{{ isset($setting['dark_logo']) && checkFile($setting['dark_logo']) ? getFile($setting['dark_logo']) : getFile('uploads/logo/logo-dark.png') . '?' . time() }}"
                                                    target="_blank" class="logo-content">
                                                    <img id="blah2" alt="your image"
                                                        src="{{ isset($setting['dark_logo']) && checkFile($setting['dark_logo']) ? getFile($setting['dark_logo']) : getFile('uploads/logo/logo-dark.png') . '?' . time() }}"
                                                        width="150px" class="logo logo-sm">
                                                </a>
                                                <div class="choose-files mt-3">
                                                    <label for="logo" class="form-label d-block mb-0">
                                                        <div class="bg-primary m-auto">
                                                            <i
                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                            <input type="file" name="dark_logo" id="logo"
                                                                class="form-control file"
                                                                data-filename="company_logo_update"
                                                                onchange="document.getElementById('blah2').src = window.URL.createObjectURL(this.files[0])">
                                                        </div>
                                                    </label>
                                                    <!-- <p class="edit-logo"></p> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="card mb-0">
                                        <div class="card-header">
                                            <h5 class="small-title">{{ __('Light Logo') }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="setting-card setting-logo-box">
                                                <a href="{{ isset($setting['light_logo']) && checkFile($setting['light_logo']) ? getFile($setting['light_logo']) : getFile('uploads/logo/logo-light.png') . '?' . time() }}"
                                                    target="_blank" class="logo-content">
                                                    <img id="blah3" alt="your image"
                                                        src="{{ isset($setting['light_logo']) && checkFile($setting['light_logo']) ? getFile($setting['light_logo']) : getFile('uploads/logo/logo-light.png') . '?' . time() }}"
                                                        width="150px" class="logo logo-sm img_setting"
                                                        style="filter: drop-shadow(2px 3px 7px #011c4b);">
                                                </a>
                                                <div class="choose-files mt-3">
                                                    <label for="white_logo" class="form-label d-block mb-0">
                                                        <div class=" bg-primary m-auto">
                                                            <i
                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                            <input type="file" name="light_logo" id="white_logo"
                                                                class="form-control file"
                                                                data-filename="company_logo_update"
                                                                onchange="document.getElementById('blah3').src = window.URL.createObjectURL(this.files[0])">
                                                        </div>
                                                    </label>
                                                    <!-- <p class="edit-white_logo"></p> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="card mb-0">
                                        <div class="card-header">
                                            <h5 class="small-title">{{ __('Favicon') }}</h5>
                                        </div>
                                        <div class="card-body ">
                                            <div class="setting-card setting-logo-box">
                                                <a href="{{ isset($setting['favicon']) && checkFile($setting['favicon']) ? getFile($setting['favicon']) : getFile('uploads/logo/favicon.png') . '?' . time() }}"
                                                    target="_blank" class="logo-content">
                                                    <img id="blah" alt="your image"
                                                        src="{{ isset($setting['favicon']) && checkFile($setting['favicon']) ? getFile($setting['favicon']) : getFile('uploads/logo/favicon.png') . '?' . time() }}"
                                                        width="80px" class="big-logo img_setting">
                                                </a>
                                                <div class="choose-files mt-3">
                                                    <label for="favicon" class="form-label d-block mb-0">
                                                        <div class=" bg-primary m-auto">
                                                            <i
                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                            <input type="file" name="favicon" id="favicon"
                                                                class="form-control file"
                                                                data-filename="company_logo_update"
                                                                onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                                                        </div>
                                                    </label>
                                                    <!-- <p class="edit-favicon"></p> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row row-gap-1">
                                <div class="col-md-6 col-12">
                                    <div class="form-group mb-0">
                                        <label for="app_name" class="form-label">{{ __('App Name') }}</label>
                                        <input type="text" name="app_name" id="APP_NAME" class="form-control"
                                            placeholder="{{ __('App Name') }}"
                                            value="{{ isset($setting['app_name']) ? $setting['app_name'] : config('app.name') }}"
                                            required>

                                        @if ($errors->has('app_name'))
                                        <div class="text-danger my-2">
                                            {{ $errors->first('app_name') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group mb-0">
                                        <label for="footer_text" class="form-label">{{ __('Footer Text') }}</label>
                                        <input type="text" name="footer_text" id="footer_text" class="form-control"
                                            placeholder="{{ __('Footer Text') }}"
                                            value="{{ !empty($setting['footer_text']) ? $setting['footer_text'] : '' }}"
                                            required>

                                        @if ($errors->has('footer_text'))
                                        <div class="text-danger">
                                            {{ $errors->first('footer_text') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @php
                                $defaultSelectedLang = isset($setting['default_language'])
                                ? $setting['default_language']
                                : 'en';
                                @endphp
                                <div class="col-md-6 col-12">
                                    <div class="form-group mb-0">
                                        <label for="default_language"
                                            class="form-label">{{ __('Default Language') }}</label>
                                        <div class="changeLanguage">
                                            <select name="default_language" id="default_language" class="form-select"
                                                required>
                                                @foreach (languages() as $code => $language)
                                                <option @if ($defaultSelectedLang==$code) selected @endif
                                                    value="{{ $code }}">
                                                    {{ Str::ucfirst($language) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($errors->has('default_language'))
                                        <div class="text-danger">
                                            {{ $errors->first('default_language') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group mb-0">
                                        <label for="timezone" class="form-label">{{ __('Timezone') }}</label>
                                        <select name="timezone" id="timezone" class="form-control custom-select"
                                            required>
                                            <option value="">{{ __('Select Timezone') }}
                                            </option>
                                            @foreach ($timezones as $k => $timezone)
                                            <option
                                                {{ isset($setting['timezone']) && $setting['timezone'] == $k ? 'selected' : '' }}
                                                value="{{ $k }}">
                                                {{ $timezone }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('timezone'))
                                        <div class="text-danger my-2">
                                            {{ $errors->first('timezone') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="card-body border-1 border-top ">
                            <div class="setting-card setting-logo-box">
                                <h4 class="small-title h5 mb-3">{{ __('Theme Customizer') }}</h4>
                                <div class="row row-gap-1">
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="card h-100 mb-0">
                                            <div class="card-header">
                                                <h6 class="d-flex align-items-center gap-2 mb-0">
                                                    <i data-feather="credit-card"></i>{{ __('Primary color settings') }}
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="color-wrp m-0">
                                                    <div class="theme-color themes-color">
                                                        <a href="#!"
                                                            class="themes-color-change {{ $color == 'theme-1' ? 'active_color' : '' }}"
                                                            data-value="theme-1"></a>
                                                        <input type="radio" class="theme_color d-none" name="color"
                                                            value="theme-1" {{ $color == 'theme-1' ? 'checked' : '' }}>
                                                        <a href="#!"
                                                            class="themes-color-change {{ $color == 'theme-2' ? 'active_color' : '' }}"
                                                            data-value="theme-2"></a>
                                                        <input type="radio" class="theme_color d-none" name="color"
                                                            value="theme-2" {{ $color == 'theme-2' ? 'checked' : '' }}>
                                                        <a href="#!"
                                                            class="themes-color-change {{ $color == 'theme-3' ? 'active_color' : '' }}"
                                                            data-value="theme-3"></a>
                                                        <input type="radio" class="theme_color d-none" name="color"
                                                            value="theme-3" {{ $color == 'theme-3' ? 'checked' : '' }}>
                                                        <a href="#!"
                                                            class="themes-color-change {{ $color == 'theme-4' ? 'active_color' : '' }}"
                                                            data-value="theme-4"></a>
                                                        <input type="radio" class="theme_color d-none" name="color"
                                                            value="theme-4" {{ $color == 'theme-4' ? 'checked' : '' }}>
                                                        <a href="#!"
                                                            class="themes-color-change {{ $color == 'theme-5' ? 'active_color' : '' }}"
                                                            data-value="theme-5"></a>
                                                        <input type="radio" class="theme_color d-none" name="color"
                                                            value="theme-5" {{ $color == 'theme-5' ? 'checked' : '' }}>
                                                        <a href="#!"
                                                            class="themes-color-change {{ $color == 'theme-6' ? 'active_color' : '' }}"
                                                            data-value="theme-6"></a>
                                                        <input type="radio" class="theme_color d-none" name="color"
                                                            value="theme-6" {{ $color == 'theme-6' ? 'checked' : '' }}>
                                                        <a href="#!"
                                                            class="themes-color-change {{ $color == 'theme-7' ? 'active_color' : '' }}"
                                                            data-value="theme-7"></a>
                                                        <input type="radio" class="theme_color d-none" name="color"
                                                            value="theme-7" {{ $color == 'theme-7' ? 'checked' : '' }}>
                                                        <a href="#!"
                                                            class="themes-color-change {{ $color == 'theme-8' ? 'active_color' : '' }}"
                                                            data-value="theme-8"></a>
                                                        <input type="radio" class="theme_color d-none" name="color"
                                                            value="theme-8" {{ $color == 'theme-8' ? 'checked' : '' }}>
                                                        <a href="#!"
                                                            class="themes-color-change {{ $color == 'theme-9' ? 'active_color' : '' }}"
                                                            data-value="theme-9"></a>
                                                        <input type="radio" class="theme_color d-none" name="color"
                                                            value="theme-9" {{ $color == 'theme-9' ? 'checked' : '' }}>
                                                        <a href="#!"
                                                            class="themes-color-change {{ $color == 'theme-10' ? 'active_color' : '' }}"
                                                            data-value="theme-10"></a>
                                                        <input type="radio" class="theme_color d-none" name="color"
                                                            value="theme-10"
                                                            {{ $color == 'theme-10' ? 'checked' : '' }}>
                                                    </div>
                                                    <div class="color-picker-wrp ">
                                                        <input type="color" value="{{ $color ? $color : '' }}"
                                                            class="colorPicker {{ isset($flag) && $flag == 'true' ? 'active_color' : '' }}"
                                                            name="custom_color" id="color-picker">
                                                        <input type='hidden' name="color_flag"
                                                            value={{ isset($flag) && $flag == 'true' ? 'true' : 'false' }}>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="card h-100 mb-0">
                                            <div class="card-header">
                                                <h6 class="d-flex align-items-center mb-0 gap-2">
                                                    <i data-feather="layout"></i>{{ __('Sidebar settings') }}
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-check form-switch d-flex gap-2 flex-column p-0">
                                                    <label class="form-check-label f-w-600"
                                                        for="cust-theme-bg">{{ __('Transparent layout') }}
                                                    </label>

                                                    <input type="checkbox" class="form-check-input ms-0"
                                                        id="cust-theme-bg" name="cust_theme_bg"
                                                        {{ isset($setting['cust_theme_bg']) && $setting['cust_theme_bg'] == 'on' ? 'checked' : '' }} />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="card h-100 mb-0">
                                            <div class="card-header">
                                                <h6 class="d-flex align-items-center mb-0 gap-2">
                                                    <i data-feather="sun"></i>{{ __('Layout settings') }}
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-check form-switch d-flex gap-2 flex-column p-0">
                                                    <label class="form-check-label f-w-600"
                                                        for="cust-darklayout">{{ __('Dark Layout') }}
                                                    </label>
                                                    <input type="checkbox" class="form-check-input ms-0"
                                                        id="cust-darklayout" name="cust_darklayout"
                                                        {{ isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on' ? 'checked' : '' }} />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="card h-100 mb-0">
                                            <div class="card-header">
                                                <h6 class="d-flex align-items-center mb-0 gap-2">
                                                    <i class="ti ti-align-right"></i>Enable RTL
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-check form-switch d-flex gap-2 flex-column p-0">
                                                    <label class="form-check-label f-w-600" for="site_rtl">RTL
                                                        Layout</label>
                                                    <input type="checkbox" class="form-check-input ms-0" name="site_rtl"
                                                        id="site_rtl" value='on'
                                                        {{ isset($setting['site_rtl']) && $setting['site_rtl'] == 'on' ? 'checked="checked"' : '' }}>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="card h-100 mb-0">
                                            <div class="card-header">
                                                <h6 class="d-flex align-items-center mb-0 gap-2">
                                                    <i class="ti ti-align-right"></i>Enable FAQ
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-check form-switch d-flex gap-2 flex-column p-0">
                                                    <label class="form-check-label f-w-600" for="faq">FAQ
                                                    </label>
                                                    <input type="checkbox" class="form-check-input ms-0" name="faq"
                                                        id="faq"
                                                        {{ !empty($setting['faq']) && $setting['faq'] == 'on' ? 'checked="checked"' : '' }}>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-sm-6 col-12">
                                        <div class="card h-100 mb-0">
                                            <div class="card-header">
                                                <h6 class="d-flex align-items-center mb-0 gap-2">
                                                    <i class="ti ti-align-right"></i>Knowledge Base setting
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-check form-switch d-flex gap-2 flex-column p-0">
                                                    <label class="form-check-label f-w-600"
                                                        for="knowledge">{{ __('Knowledge Base') }}
                                                    </label>
                                                    <input type="checkbox" class="form-check-input ms-0"
                                                        name="knowledge_base" id="knowledge_base"
                                                        {{ isset($setting['knowledge_base']) && $setting['knowledge_base'] == 'on' ? 'checked="checked"' : '' }}>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                             

                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button class="btn btn-primary" type="submit">{{ __('Save Changes') }}</button>
                        </div>
                    </form>
                </div>

                {{-- Email Settings --}}
                <div id="email-settings" class="card">
                    <div class="card-header">
                        <h5 class="mb-2">{{ __('Email Settings') }}</h5>
                        <small>{{ __('Edit your Email settings') }}</small>
                    </div>
                    <form action="{{ route('admin.email.settings.store') }}" class="needs-validation" novalidate
                        method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row row-gap-1">
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label class="form-label">{{ __('Mail Driver') }}</label>
                                        <x-required></x-required>
                                        <input class="form-control" placeholder="{{ __('Mail Driver') }}"
                                            name="mail_driver" type="text"
                                            value="{{ isset($setting['mail_driver']) ? $setting['mail_driver'] : '' }}"
                                            id="mail_driver" required>

                                        @if ($errors->has('mail_driver'))
                                        <div class="text-danger my-2">
                                            {{ $errors->first('mail_driver') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label class="form-label">{{ __('Mail Host') }}</label>
                                        <x-required></x-required>
                                        <input class="form-control" placeholder="{{ __('Mail Host') }}" name="mail_host"
                                            type="text"
                                            value="{{ isset($setting['mail_host']) ? $setting['mail_host'] : '' }}"
                                            id="mail_host" required>
                                        @if ($errors->has('mail_host'))
                                        <div class="text-danger my-2">
                                            {{ $errors->first('mail_host') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label class="form-label">{{ __('Mail Port') }}</label>
                                        <x-required></x-required>
                                        <input class="form-control" placeholder="{{ __('Mail Port') }}" name="mail_port"
                                            type="text"
                                            value="{{ isset($setting['mail_port']) ? $setting['mail_port'] : '' }}"
                                            id="mail_port" required>
                                        @if ($errors->has('mail_port'))
                                        <div class="text-danger my-2">
                                            {{ $errors->first('mail_port') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label class="form-label">{{ __('Mail Username') }}</label>
                                        <x-required></x-required>
                                        <input class="form-control" placeholder="{{ __('Mail Username') }}"
                                            name="mail_username" type="text"
                                            value="{{ isset($setting['mail_username']) ? $setting['mail_username'] : '' }}"
                                            id="mail_username" required>
                                        @if ($errors->has('mail_username'))
                                        <div class="text-danger my-2">
                                            {{ $errors->first('mail_username') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label class="form-label">{{ __('Mail Password') }}</label>
                                        <x-required></x-required>
                                        <input class="form-control" placeholder="{{ __('Mail Password') }}"
                                            name="mail_password" type="text"
                                            value="{{ isset($setting['mail_password']) ? $setting['mail_password'] : '' }}"
                                            id="mail_password" required>
                                        @if ($errors->has('mail_password'))
                                        <div class="text-danger my-2">
                                            {{ $errors->first('mail_password') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label class="form-label">{{ __('Mail Encryption') }}</label>
                                        <x-required></x-required>
                                        <input class="form-control" placeholder="{{ __('Mail Encryption') }}"
                                            name="mail_encryption" type="text"
                                            value="{{ isset($setting['mail_encryption']) ? $setting['mail_encryption'] : '' }}"
                                            id="mail_encryption" required>
                                        @if ($errors->has('mail_encryption'))
                                        <div class="text-danger my-2">
                                            {{ $errors->first('mail_encryption') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label class="form-label">{{ __('Mail From Address') }}</label>
                                        <x-required></x-required>
                                        <input class="form-control" placeholder="{{ __('Mail From Address') }}"
                                            name="mail_from_address" type="text"
                                            value="{{ isset($setting['mail_from_address']) ? $setting['mail_from_address'] : '' }}"
                                            id="mail_from_address" required>
                                        @if ($errors->has('mail_from_address'))
                                        <div class="text-danger my-2">
                                            {{ $errors->first('mail_from_address') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label class="form-label">{{ __('Mail From Name') }}</label>
                                        <x-required></x-required>
                                        <input class="form-control" placeholder="{{ __('Mail From Name') }}"
                                            name="mail_from_name" type="text"
                                            value="{{ isset($setting['mail_from_name']) ? $setting['mail_from_name'] : '' }}"
                                            id="mail_from_name" required>
                                        @if ($errors->has('mail_from_name'))
                                        <div class="text-danger my-2">
                                            {{ $errors->first('mail_from_name') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end gap-3 align-items-center flex-wrap">
                            <div class="form-group mb-0">
                                <a href="#" data-url="{{ route('admin.test.email') }}"
                                    data-title="{{ __('Send Test Mail') }}" class="btn btn-primary send_email ">
                                    {{ __('Send Test Mail') }}
                                </a>
                            </div>
                            <div class="form-group mb-0">
                                <button class="btn btn-primary" type="submit">{{ __('Save Changes') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>


                {{-- Email Notification Setting --}}
                <div id="email-notification-settings" class="card">
                    <form action="{{ route('status.email.language') }}" method="post">
                        @csrf
                        <div class="col-md-12">
                            <div class="card-header ">
                                <h5>{{ __('Email Notification Settings') }}</h5>
                            </div>

                            <div class="card-body   pb-0">
                                <ul class="nav nav-pills gap-2 mb-3" id="pills-tab" role="tablist">
                                    @php
                                    $active = 'active';
                                    @endphp
                                    @foreach ($email_notification_modules as $e_module)
                                    @if (Laratrust::hasPermission($e_module . ' manage') ||
                                    Laratrust::hasPermission(strtolower($e_module) . ' manage') ||
                                    $e_module == 'General')
                                    <li class="nav-item">
                                        <a class="nav-link text-capitalize rounded-1 {{ $active }}"
                                            id="pills-{{ strtolower($e_module) }}-tab-email" data-bs-toggle="pill"
                                            href="#pills-{{ strtolower($e_module) }}-email" role="tab"
                                            aria-controls="pills-{{ strtolower($e_module) }}-email"
                                            aria-selected="true">{{ $e_module }}</a>
                                    </li>
                                    @php
                                    $active = '';
                                    @endphp
                                    @endif
                                    @endforeach
                                </ul>
                                <div class="tab-content " id="pills-tabContent">
                                    @foreach ($email_notification_modules as $e_module)
                                    <div class="tab-pane fade {{ $loop->index == 0 ? 'active' : '' }} show"
                                        id="pills-{{ strtolower($e_module) }}-email" role="tabpanel"
                                        aria-labelledby="pills-{{ strtolower($e_module) }}-tab-email">
                                        <div class="row">
                                            @foreach ($email_notify as $e_action)
                                            @if ($e_action->module == $e_module)
                                            <div class="col-lg-4 col-sm-6 col-12 mb-3">
                                                <div class="rounded-1 card   list_colume_notifi p-3 h-100 mb-0">
                                                    <div
                                                        class="card-body d-flex align-items-center justify-content-between gap-2 p-0">
                                                        <h6 class="mb-0">
                                                            <label for="{{ $e_action->action }}"
                                                                class="form-label mb-0">{{ $e_action->action }}</label>
                                                        </h6>
                                                        <div class="form-check form-switch d-inline-block text-end">
                                                            <input type="hidden"
                                                                name="mail_noti[{{ $e_action->action }}]" value="0" />
                                                            <input class="form-check-input"
                                                                {{ isset($setting[$e_action->action]) && $setting[$e_action->action] == true ? 'checked' : '' }}
                                                                id="mail_notificaation"
                                                                name="mail_noti[{{ $e_action->action }}]"
                                                                type="checkbox" value="1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="card-footer text-end pb-3">
                                <input class="btn btn-print-invoice  btn-primary" type="submit"
                                    value="{{ __('Save Changes') }}">
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Storage Settings --}}
                @php
                $local_storage_validation = $setting['local_storage_validation'];
                $local_storage_validations = explode(',', $local_storage_validation);

                $s3_storage_validation = isset($setting['s3_storage_validation'])
                ? $setting['s3_storage_validation']
                : '';
                $s3_storage_validations = explode(',', $s3_storage_validation);

                $wasabi_storage_validation = isset($setting['wasabi_storage_validation'])
                ? $setting['wasabi_storage_validation']
                : '';
                $wasabi_storage_validations = explode(',', $wasabi_storage_validation);
                @endphp
                <div id="storage-settings" class="card mb-3">
                    <form action="{{ route('storage.setting.store') }}" method="post" enctype="multipart/form-data"
                        class="needs-validation" novalidate>
                        @csrf
                        <div class="card-header">
                            <h5>{{ __('Storage Settings') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="pe-2">
                                    <input type="radio" class="btn-check" name="storage_setting" id="local-outlined"
                                        autocomplete="off" {{ $setting['storage_setting'] == 'local' ? 'checked' : '' }}
                                        value="local" checked>
                                    <label class="btn btn-outline-primary"
                                        for="local-outlined">{{ __('Local') }}</label>
                                </div>
                                <div class="pe-2">
                                    <input type="radio" class="btn-check" name="storage_setting" id="s3-outlined"
                                        autocomplete="off" {{ $setting['storage_setting'] == 's3' ? 'checked' : '' }}
                                        value="s3">
                                    <label class="btn btn-outline-primary" for="s3-outlined">
                                        {{ __('AWS S3') }}</label>
                                </div>

                                <div class="pe-2">
                                    <input type="radio" class="btn-check" name="storage_setting" id="wasabi-outlined"
                                        autocomplete="off"
                                        {{ $setting['storage_setting'] == 'wasabi' ? 'checked' : '' }} value="wasabi">
                                    <label class="btn btn-outline-primary"
                                        for="wasabi-outlined">{{ __('Wasabi') }}</label>
                                </div>
                            </div>
                            <div class="mt-3">
                                {{-- local storage --}}
                                <div
                                    class="local-setting row row-gap-1 {{ $setting['storage_setting'] == 'local' ? ' ' : 'd-none' }} ">
                                    <div class="form-group mb-0 col-lg-8 col-12 switch-width">
                                        <label for="local_storage_validation"
                                            class="form-label">{{ __('Only Upload Files') }}</label>
                                        <select name="local_storage_validation[]" class="form-control"
                                            id="choices-multiple-remove-button" multiple>
                                            @foreach ($file_type as $f)
                                            <option {{ in_array($f, $local_storage_validations) ? 'selected' : '' }}>
                                                {{ $f }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        <div class="form-group mb-0">
                                            <label class="form-label"
                                                for="local_storage_max_upload_size">{{ __('Max upload size ( In KB)') }}</label>
                                            <input type="number" name="local_storage_max_upload_size"
                                                class="form-control"
                                                value="{{ isset($setting['local_storage_max_upload_size']) ? $setting['local_storage_max_upload_size'] : '' }}"
                                                placeholder="{{ __('Max upload size') }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- AWS S3 Storage  --}}
                                <div
                                    class="s3-setting row row-gap-1 {{ $setting['storage_setting'] == 's3' ? ' ' : 'd-none' }}">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group mb-0">
                                            <label class="form-label" for="s3_key">{{ __('S3 Key') }}</label>
                                            <input type="text" name="s3_key" class="form-control"
                                                value="{{ isset($setting['s3_key']) ? $setting['s3_key'] : '' }}"
                                                placeholder="{{ __('S3 Key') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group mb-0">
                                            <label class="form-label" for="s3_secret">{{ __('S3 Secret') }}</label>
                                            <input type="text" name="s3_secret" class="form-control"
                                                value="{{ isset($setting['s3_secret']) ? $setting['s3_secret'] : '' }}"
                                                placeholder="{{ __('S3 Secret') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group mb-0">
                                            <label class="form-label" for="s3_region">{{ __('S3 Region') }}</label>
                                            <input type="text" name="s3_region" class="form-control"
                                                value="{{ isset($setting['s3_region']) ? $setting['s3_region'] : '' }}"
                                                placeholder="{{ __('S3 Region') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group mb-0">
                                            <label class="form-label" for="s3_bucket">{{ __('S3 Bucket') }}</label>
                                            <input type="text" name="s3_bucket" class="form-control"
                                                value="{{ isset($setting['s3_bucket']) ? $setting['s3_bucket'] : '' }}"
                                                placeholder="{{ __('S3 Bucket') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group mb-0">
                                            <label class="form-label" for="s3_url">{{ __('S3 URL') }}</label>
                                            <input type="text" name="s3_url" class="form-control"
                                                value="{{ isset($setting['s3_url']) ? $setting['s3_url'] : '' }}"
                                                placeholder="{{ __('S3 URL') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group mb-0">
                                            <label class="form-label" for="s3_endpoint">{{ __('S3 Endpoint') }}</label>
                                            <input type="text" name="s3_endpoint" class="form-control"
                                                value="{{ isset($setting['s3_endpoint']) ? $setting['s3_endpoint'] : '' }}"
                                                placeholder="{{ __('S3 Bucket') }}">
                                        </div>
                                    </div>
                                    <div class="form-group mb-0 col-md-6 col-12 switch-width">
                                        <div>
                                            <label class="form-label"
                                                for="s3_storage_validation">{{ __('Only Upload Files') }}</label>
                                        </div>
                                        <select class="form-control" name="s3_storage_validation[]"
                                            id="choices-multiple-remove-button1" placeholder="This is a placeholder"
                                            multiple>
                                            @foreach ($file_type as $f)
                                            <option {{ in_array($f, $s3_storage_validations) ? 'selected' : '' }}>
                                                {{ $f }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group mb-0">
                                            <label class="form-label"
                                                for="s3_max_upload_size">{{ __('Max upload size ( In KB)') }}</label>
                                            <input type="number" name="s3_max_upload_size" class="form-control"
                                                value="{{ isset($setting['s3_max_upload_size']) ? $setting['s3_max_upload_size'] : '' }}"
                                                placeholder="{{ __('Max upload size') }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- Wasabi Storage --}}
                                <div
                                    class="wasabi-setting row row-gap-1 {{ $setting['storage_setting'] == 'wasabi' ? ' ' : 'd-none' }}">
                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <label class="form-label" for="s3_key">{{ __('Wasabi Key') }}</label>
                                            <input type="text" name="wasabi_key" class="form-control"
                                                value="{{ isset($setting['wasabi_key']) ? $setting['wasabi_key'] : '' }}"
                                                placeholder="{{ __('Wasabi Key') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <label class="form-label" for="s3_secret">{{ __('Wasabi Secret') }}</label>
                                            <input type="text" name="wasabi_secret" class="form-control"
                                                value="{{ isset($setting['wasabi_secret']) ? $setting['wasabi_secret'] : '' }}"
                                                placeholder="{{ __('Wasabi Secret') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <label class="form-label" for="s3_region">{{ __('Wasabi Region') }}</label>
                                            <input type="text" name="wasabi_region" class="form-control"
                                                value="{{ isset($setting['wasabi_region']) ? $setting['wasabi_region'] : '' }}"
                                                placeholder="{{ __('Wasabi Region') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <label class="form-label"
                                                for="wasabi_bucket">{{ __('Wasabi Bucket') }}</label>
                                            <input type="text" name="wasabi_bucket" class="form-control"
                                                value="{{ isset($setting['wasabi_bucket']) ? $setting['wasabi_bucket'] : '' }}"
                                                placeholder="{{ __('Wasabi Bucket') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <label class="form-label" for="wasabi_url">{{ __('Wasabi URL') }}</label>
                                            <input type="text" name="wasabi_url" class="form-control"
                                                value="{{ isset($setting['wasabi_url']) ? $setting['wasabi_url'] : '' }}"
                                                placeholder="{{ __('Wasabi URL') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <label class="form-label" for="wasabi_root">{{ __('Wasabi Root') }}</label>
                                            <input type="text" name="wasabi_root" class="form-control"
                                                value="{{ isset($setting['wasabi_root']) ? $setting['wasabi_root'] : '' }}"
                                                placeholder="{{ __('Wasabi Bucket') }}">
                                        </div>
                                    </div>
                                    <div class="form-group mb-0 col-md-6 col-12 switch-width">
                                        <label for="wasabi_storage_validation"
                                            class="form-label">{{ __('Only Upload Files') }}</label>
                                        <select name="wasabi_storage_validation[]" class="form-control"
                                            id="choices-multiple-remove-button2" multiple>
                                            @foreach ($file_type as $f)
                                            <option {{ in_array($f, $wasabi_storage_validations) ? 'selected' : '' }}>
                                                {{ $f }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group mb-0">
                                            <label class="form-label"
                                                for="wasabi_root">{{ __('Max upload size ( In KB)') }}</label>
                                            <input type="number" name="wasabi_max_upload_size" class="form-control"
                                                value="{{ isset($setting['wasabi_max_upload_size']) ? $setting['wasabi_max_upload_size'] : '' }}"
                                                placeholder="{{ __('Max upload size') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer pb-3 text-end">
                            <input class="btn btn-print-invoice  btn-primary" type="submit"
                                value="{{ __('Save Changes') }}">
                        </div>
                    </form>
                </div>

                {{-- ReCaptcha Settings --}}
                <div id="recaptcha-settings" class="card ">
                    <form method="POST" action="{{ route('admin.recaptcha.settings.store') }}" accept-charset="UTF-8"
                        class="needs-validation" novalidate>
                        @csrf
                        <div class="card-header  d-flex align-items-center gap-2 justify-content-between flex-wrap">
                            <div class="d-flex flex-column">
                                <h5 class="mb-1">{{ __('ReCaptcha Settings') }}</h5>
                                <a href="https://phppot.com/php/how-to-get-google-recaptcha-site-and-secret-key/"
                                    target="_blank" class="text-blue">
                                    <small>({{ __('How to Get Google reCaptcha Site and Secret key') }})</small>
                                </a>
                            </div>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary" class=""
                                    name="recaptcha_module" id="recaptcha_module"
                                    {{ isset($setting['RECAPTCHA_MODULE']) && $setting['RECAPTCHA_MODULE'] == 'yes' ? 'checked="checked"' : '' }}
                                    value="yes">
                                <label class="custom-control-label" for="recaptcha_module"></label>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row row-gap-1">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-md-6 col-12 form-group mb-0">
                                            <label for="google_recaptcha_version"
                                                class="form-label">{{ __('Google Recaptcha Version') }}</label>
                                            <select id="google_recaptcha_version" name="google_recaptcha_version"
                                                class="form-control choices" required>
                                                @foreach ($google_recaptcha_version as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ isset($setting['google_recaptcha_version']) && $setting['google_recaptcha_version'] == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 form-group mb-0">
                                    <label for="google_recaptcha_key"
                                        class="form-label">{{ __('Google Recaptcha Key') }}</label>
                                    <input class="form-control" placeholder="{{ __('Enter Google Recaptcha Key') }}"
                                        name="google_recaptcha_key" type="text"
                                        value="{{ isset($setting['NOCAPTCHA_SITEKEY']) ? $setting['NOCAPTCHA_SITEKEY'] : '' }}"
                                        id="google_recaptcha_key" required>
                                </div>
                                <div class="col-md-6 col-12 form-group mb-0">
                                    <label for="google_recaptcha_secret"
                                        class="form-label">{{ __('Google Recaptcha Secret') }}</label>
                                    <input class="form-control " placeholder="{{ __('Enter Google Recaptcha Secret') }}"
                                        name="google_recaptcha_secret" type="text"
                                        value="{{ isset($setting['NOCAPTCHA_SECRET']) ? $setting['NOCAPTCHA_SECRET'] : '' }}"
                                        id="google_recaptcha_secret" required>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer pb-3 text-end">
                            <input type="submit" value="{{ __('Save Changes') }}"
                                class="btn btn-primary btn-block btn-submit text-white">
                        </div>
                    </form>
                </div>

                {{-- SEO Settings --}}
                <div id="seo-settings" class="card mb-3">
                    <form action="{{ route('seo.settings') }}" method="POST" enctype="multipart/form-data"
                        class="needs-validation" novalidate>
                        @csrf
                        <div class="card-header flex-wrap d-flex align-items-center gap-2 justify-content-between">
                            <h5>{{ __('SEO Settings') }}</h5>
                            @if (isset($setting['is_enabled']) && $setting['is_enabled'] == 'on')
                            <a class="btn btn-primary btn-sm float-end" href="#" data-size="lg"
                                data-ajax-popup-over="true" data-url="{{ route('generate', ['seo']) }}"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Generate"
                                data-title="Generate Content with AI">
                                <i class="fas fa-robot me-1"></i> {{ __('Generate with AI') }}
                            </a>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="meta_keywords"
                                            class="col-form-label">{{ __('Meta Keywords') }}</label>
                                        <input type="text" name="meta_keywords" id="meta_keywords" class="form-control"
                                            placeholder="Meta Keywords"
                                            value="{{ isset($setting['meta_keywords']) ? $setting['meta_keywords'] : '' }}"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="meta_description"
                                            class="form-label">{{ __('Meta Description') }}</label>
                                        <textarea name="meta_description" id="meta_description" class="form-control"
                                            rows="5" placeholder="Enter Meta Description"
                                            required>{{ isset($setting['meta_description']) ? $setting['meta_description'] : '' }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="meta_image" class="col-form-label">{{ __('Meta Image') }}</label>
                                        <div>
                                            <a href="{{ isset($setting['meta_image']) && checkFile($setting['meta_image']) ? getFile($setting['meta_image']) : getFile('uploads/metaevent/meta.png') . '?' . time() }}"
                                                target="_blank">
                                                <div class="logo-content">
                                                    <img src="{{ isset($setting['meta_image']) && checkFile($setting['meta_image']) ? getFile($setting['meta_image']) : getFile('uploads/metaevent/meta.png') . '?' . time() }}"
                                                        id="meta_image_pre" class="img_setting seo_image" />
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="choose-files mt-4">
                                        <label for="meta_image">
                                            <div class="bg-primary m-auto">
                                                <i class="ti ti-upload px-1"></i>{{ __('Select Image') }}
                                                <input style="margin-top: -40px;" type="file" class="file"
                                                    name="meta_image" id="meta_image" data-filename="meta_image"
                                                    onchange="document.getElementById('meta_image_pre').src = window.URL.createObjectURL(this.files[0])" />
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer  text-end">
                            <button class="btn-submit btn btn-primary abcd"
                                type="submit">{{ __('Save Changes') }}</button>
                        </div>
                    </form>
                </div>


                {{-- Cookie Settings --}}
                <div id="cookie-settings" class="card mb-3">
                    <form action="{{ route('cookie.setting') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        <div class="card-header flex-wrap d-flex align-items-center gap-3 justify-content-between">
                            <h5>{{ __('Cookie Settings') }}</h5>
                            <div class="d-flex align-items-center">
                                <label for="enable_cookie"
                                    class="col-form-label p-0 fw-bold me-3">{{ __('Enable cookie') }}</label>
                                <div class="custom-control custom-switch" onclick="enablecookie()">
                                    <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary"
                                        name="enable_cookie" class="form-check-input input-primary" id="enable_cookie"
                                        {{ isset($setting['enable_cookie']) && $setting['enable_cookie'] == 'on' ? 'checked' : '' }}>
                                    <label class="custom-control-label mb-1" for="enable_cookie"></label>
                                </div>
                            </div>
                        </div>
                        <div
                            class="card-body cookieDiv {{ isset($setting['enable_cookie']) && $setting['enable_cookie'] == 'off' ? 'disabledCookie' : '' }}">
                            @if (isset($setting['is_enabled']) && $setting['is_enabled'] == 'on')
                            <div class="d-flex justify-content-end mb-3">
                                <a class="btn btn-primary btn-sm" href="#" data-size="lg"
                                    data-ajax-popup-over="true" data-url="{{ route('generate', ['cookie']) }}"
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Generate"
                                    data-title="Generate Content with AI">
                                    <i class="fas fa-robot me-1"> </i>{{ __('Generate with AI') }}
                                </a>
                            </div>
                            @endif
                            <div class="row row-gap-1">
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="form-check form-switch custom-switch-v1" id="cookie_log">
                                                <input type="checkbox" name="cookie_logging"
                                                    class="form-check-input input-primary cookie_setting"
                                                    id="cookie_logging"
                                                    {{ isset($setting['cookie_logging']) && $setting['cookie_logging'] == 'on' ? 'checked' : '' }}>
                                                <label class="form-check-label" style="margin-left:5px"
                                                    for="cookie_logging">{{ __('Enable logging') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="cookie_title"
                                            class="col-form-label">{{ __('Cookie Title') }}</label>
                                        <input type="text" name="cookie_title" id="cookie_title"
                                            value="{{ old('cookie_title', isset($setting['cookie_title']) ? $setting['cookie_title'] : '') }}"
                                            class="form-control cookie_setting" required>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label for="cookie_description"
                                            class="form-label">{{ __('Cookie Description') }}</label>
                                        <textarea name="cookie_description" id="cookie_description"
                                            class="form-control cookie_setting" rows="3"
                                            required>{{ old('cookie_description', isset($setting['cookie_description']) ? $setting['cookie_description'] : '') }}</textarea>
                                        <small
                                            class="text-danger d-block mt-1">{{ __('Please avoid to use enter key for new line. You can use &lt;br&gt; for new Line') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="form-check form-switch custom-switch-v1">
                                                <input type="checkbox" name="necessary_cookies"
                                                    class="form-check-input input-primary" id="necessary_cookies"
                                                    checked onclick="return false">
                                                <label class="form-check-label" style="margin-left:5px"
                                                    for="necessary_cookies">{{ __('Strictly necessary cookies') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="strictly_cookie_title"
                                            class="col-form-label">{{ __('Strictly Cookie Title') }}</label>
                                        <input type="text" name="strictly_cookie_title" id="strictly_cookie_title"
                                            value="{{ old('strictly_cookie_title', isset($setting['strictly_cookie_title']) ? $setting['strictly_cookie_title'] : '') }}"
                                            class="form-control cookie_setting" required>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label for="strictly_cookie_description"
                                            class="form-label">{{ __('Strictly Cookie Description') }}</label>
                                        <textarea name="strictly_cookie_description" id="strictly_cookie_description"
                                            class="form-control cookie_setting" rows="3"
                                            required>{{ old('strictly_cookie_description', isset($setting['strictly_cookie_description']) ? $setting['strictly_cookie_description'] : '') }}</textarea>
                                        <small
                                            class="text-danger d-block mt-1">{{ __('Please avoid to use enter key for new line. You can use &lt;br&gt; for new Line') }}</small>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <h5 class="mb-0">{{ __('More Information') }}</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label for="more_information_description"
                                            class="col-form-label">{{ __('Contact Us Description') }}</label>
                                        <input type="text" name="more_information_description"
                                            id="more_information_description"
                                            value="{{ old('more_information_description', isset($setting['more_information_description']) ? $setting['more_information_description'] : '') }}"
                                            class="form-control cookie_setting" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label for="contactus_url"
                                            class="col-form-label">{{ __('Contact Us URL') }}</label>
                                        <input type="text" name="contactus_url" id="contactus_url"
                                            value="{{ old('contactus_url', isset($setting['contactus_url']) ? $setting['contactus_url'] : '') }}"
                                            class="form-control cookie_setting" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center gap-3 flex-wrap justify-content-between">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                @if (isset($setting['cookie_logging']) && $setting['cookie_logging'] == 'on')
                                <label for="file"
                                    class="form-label mb-0">{{ __('Download cookie accepted data') }}</label>
                                <a href="{{ getFile('uploads/sample/data.csv') }}" class="btn btn-primary mr-2">
                                    <i class="ti ti-download"></i>
                                </a>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                        </div>
                    </form>
                </div>


                {{-- Chat GPT Key Settings --}}
                <div id="chatgpt-settings" class="card mb-3">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <form action="{{ route('settings.chatgptkey') }}" method="POST" class="needs-validation"
                            novalidate>
                            @csrf
                            <div
                                class="card-header flex-column flex-lg-row d-flex align-items-lg-center gap-2 justify-content-between">
                                <h5>{{ __('Chat GPT Key Settings') }}</h5>
                                <div class="d-flex align-items-center">
                                    <div class="form-check custom-control custom-switch p-0">
                                        <input type="checkbox" class="form-check-input form-control" name="is_enabled"
                                            data-toggle="switchbutton" data-onstyle="primary" id="is_enabled"
                                            {{ isset($setting['is_enabled']) && $setting['is_enabled'] == 'on' ? 'checked' : '' }}>
                                        <label class="custom-control-label form-label" for="is_enabled"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row row-gap-1">
                                    <div class="form-group mb-0 col-md-12">
                                        <label for="chatgpt_key" class="col-form-label">{{ __('Chat GPT Key') }}</label>
                                        <input type="text" name="chatgpt_key" id="chatgpt_key"
                                            value="{{ isset($setting['chatgpt_key']) ? $setting['chatgpt_key'] : '' }}"
                                            class="form-control" placeholder="Enter Chatgpt Key Here" required>
                                    </div>
                                    <div class="form-group mb-0 col-md-12">
                                        <label for="chat_gpt_model"
                                            class="col-form-label">{{ __('Chat GPT Model Name') }}</label>
                                        <input type="text" name="chat_gpt_model" id="chat_gpt_model"
                                            value="{{ isset($setting['chat_gpt_model']) ? $setting['chat_gpt_model'] : '' }}"
                                            class="form-control" placeholder="Enter Chat GPT Modal Name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer pb-3 text-end">
                                <button class="btn btn-primary" type="submit">{{ __('Save Changes') }}</button>
                            </div>
                        </form>
                    </div>
                </div>



                {{-- Cache Setting --}}
                <div id="cache-settings" class="card mb-3">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <h5 class="mb-1">{{ __('Cache Setting') }}</h5>
                                <small class="text-secondary font-weight-bold">
                                    {{ __('This is a page meant for more advanced users, Simply Ignore it if you do not understand what cache is.') }}
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-0">
                                    <label for="size">{{ __('Current cache size') }}</label>
                                    <div class="input-group mt-2">
                                        <input id="size" name="size" type="text" class="form-control"
                                            value="{{ getCacheSize() }}" disabled>
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                {{ __('MB') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ route('cache.clear') }}"
                            class="btn btn-print-invoice btn-primary m-r-10">{{ __('Clear Cache') }}</a>
                    </div>
                </div>

                {{-- Pusher Settings --}}
                <div id="pusher-settings" class="card">
                    <form method="POST" action="{{ route('admin.pusher.settings.store') }}" accept-charset="UTF-8"
                        class="needs-validation" novalidate>
                        @csrf


                        <div class="card-header flex-wrap d-flex align-items-center gap-3 justify-content-between">
                            <div class="d-flex flex-column gap-1">
                                <h5>{{ __('Pusher Settings') }}</h5>
                                <small
                                    class="text-danger m-0">{{ __('Pusher settings provide real-time updates for ticket creation, replies, and live chat interactions') }}</small>
                            </div>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary" class=""
                                    name="enable_chat" id="enable_chat"
                                    {{ isset($setting['CHAT_MODULE']) && $setting['CHAT_MODULE'] == 'yes' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="enable_chat"></label>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row row-gap-1">
                                <div class="col-md-6 col-12 form-group mb-0">
                                    <label for="pusher_app_id" class="form-label">{{ __('Pusher App Id') }}</label>
                                    <input class="form-control" placeholder="Enter Pusher App Id" name="pusher_app_id"
                                        type="text"
                                        value="{{ isset($setting['PUSHER_APP_ID']) ? $setting['PUSHER_APP_ID'] : '' }}"
                                        id="pusher_app_id" required>
                                </div>
                                <div class="col-md-6 col-12 form-group mb-0">
                                    <label for="pusher_app_key" class="form-label">{{ __('Pusher App Key') }}</label>
                                    <input class="form-control " placeholder="Enter Pusher App Key"
                                        name="pusher_app_key" type="text"
                                        value="{{ isset($setting['PUSHER_APP_KEY']) ? $setting['PUSHER_APP_KEY'] : '' }}"
                                        id="pusher_app_key" required>
                                </div>
                                <div class="col-md-6 col-12 form-group mb-0">
                                    <label for="pusher_app_secret"
                                        class="form-label">{{ __('Pusher App Secret') }}</label>
                                    <input class="form-control " placeholder="Enter Pusher App Secret"
                                        name="pusher_app_secret" type="text"
                                        value="{{ isset($setting['PUSHER_APP_SECRET']) ? $setting['PUSHER_APP_SECRET'] : '' }}"
                                        id="pusher_app_secret" required>
                                </div>
                                <div class="col-md-6 col-12 form-group mb-0">
                                    <label for="pusher_app_cluster"
                                        class="form-label">{{ __('Pusher App Cluster') }}</label>
                                    <input class="form-control " placeholder="Enter Pusher App Cluster"
                                        name="pusher_app_cluster" type="text"
                                        value="{{ isset($setting['PUSHER_APP_CLUSTER']) ? $setting['PUSHER_APP_CLUSTER'] : '' }}"
                                        id="pusher_app_cluster" required>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer text-end ">
                            <button type="submit"
                                class="btn btn-primary btn-block btn-submit text-white">{{ __('Save Changes') }}</button>
                        </div>
                    </form>
                </div>
                {!! getCompanySetting() !!}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/repeater.js') }}"></script>
<script>
$('.colorPicker').on('click', function(e) {

    $('body').removeClass('custom-color');
    if (/^theme-\d+$/) {
        $('body').removeClassRegex(/^theme-\d+$/);
    }
    $('body').addClass('custom-color');
    $('.themes-color-change').removeClass('active_color');
    $(this).addClass('active_color');
    const input = document.getElementById("color-picker");
    setColor();
    input.addEventListener("input", setColor);

    function setColor() {
        $(':root').css('--color-customColor', input.value);
    }

    $(`input[name='color_flag`).val('true');
});

$('.themes-color-change').on('click', function() {

    $(`input[name='color_flag`).val('false');

    var color_val = $(this).data('value');
    $('body').removeClass('custom-color');
    if (/^theme-\d+$/) {
        $('body').removeClassRegex(/^theme-\d+$/);
    }
    $('body').addClass(color_val);
    $('.theme-color').prop('checked', false);
    $('.themes-color-change').removeClass('active_color');
    $('.colorPicker').removeClass('active_color');
    $(this).addClass('active_color');
    $(`input[value=${color_val}]`).prop('checked', true);
});

$.fn.removeClassRegex = function(regex) {
    return $(this).removeClass(function(index, classes) {
        return classes.split(/\s+/).filter(function(c) {
            return regex.test(c);
        }).join(' ');
    });
};
</script>
<script>
var scrollSpy = new bootstrap.ScrollSpy(document.body, {
    target: '#useradd-sidenav',
    offset: 300,
})

$(".list-group-item").click(function() {
    $('.list-group-item').filter(function() {
        // return this.href == id;
    }).parent().removeClass('text-primary');
});
</script>
<script>
function myFunction() {
    var copyText = document.getElementById("myInput");
    copyText.select();
    copyText.setSelectionRange(0, 99999)
    document.execCommand("copy");
    show_toastr('Success', "{{ __('Link copied') }}", 'success');
}


function check_theme(color_val) {
    $('#theme_color').prop('checked', false);
    $('input[value="' + color_val + '"]').prop('checked', true);
}
var scrollSpy = new bootstrap.ScrollSpy(document.body, {
    target: '#useradd-sidenav',
    offset: 300
})
</script>

<script>
var multipleCancelButton = new Choices(
    '#choices-multiple-remove-button', {
        removeItemButton: true,
    }
);

var multipleCancelButton = new Choices(
    '#choices-multiple-remove-button1', {
        removeItemButton: true,
    }
);

var multipleCancelButton = new Choices(
    '#choices-multiple-remove-button2', {
        removeItemButton: true,
    }
);
</script>
<script>
var scrollSpy = new bootstrap.ScrollSpy(document.body, {
    target: '#useradd-sidenav',
    offset: 300,
})
$(".list-group-item").click(function() {
    $('.list-group-item').filter(function() {
        return this.href == id;
    }).parent().removeClass('text-primary');
});

function check_theme(color_val) {
    $('#theme_color').prop('checked', false);
    $('input[value="' + color_val + '"]').prop('checked', true);
}

$(document).on('change', '[name=storage_setting]', function() {
    if ($(this).val() == 's3') {
        $('.needs-validation').removeClass('was-validated');
        $('.s3-setting').removeClass('d-none').find('input,select').not('.choices__input').attr('required',
            'required');
        $('.wasabi-setting').addClass('d-none').find('input,select').removeAttr('required');
        $('.local-setting').addClass('d-none').find('input,select').removeAttr('required');
    } else if ($(this).val() == 'wasabi') {
        $('.needs-validation').removeClass('was-validated');
        $('.s3-setting').addClass('d-none').find('input,select').removeAttr('required');
        $('.local-setting').addClass('d-none').find('input,select').removeAttr('required');
        $('.wasabi-setting').removeClass('d-none').find('input,select').not('.choices__input').attr(
            'required', 'required');
    } else {
        $('.needs-validation').removeClass('was-validated');
        $('.s3-setting').addClass('d-none').find('input,select').removeAttr('required');
        $('.wasabi-setting').addClass('d-none').find('input,select').removeAttr('required');
        $('.local-setting').removeClass('d-none').find('input,select').not('.choices__input').attr(
            'required', 'required');
    }
});
</script>

<script>
$(document).on("click", '.send_email', function(e) {

    e.preventDefault();
    var title = $(this).attr('data-title');

    var size = 'md';
    var url = $(this).attr('data-url');
    if (typeof url != 'undefined') {
        $("#commonModal .modal-title").html(title);
        $("#commonModal .modal-dialog").addClass('modal-' + size);
        $("#commonModal").modal('show');

        $.post(url, {
            mail_driver: $("#mail_driver").val(),
            mail_host: $("#mail_host").val(),
            mail_port: $("#mail_port").val(),
            mail_username: $("#mail_username").val(),
            mail_password: $("#mail_password").val(),
            mail_encryption: $("#mail_encryption").val(),
            mail_from_address: $("#mail_from_address").val(),
            mail_from_name: $("#mail_from_name").val(),
        }, function(data) {
            $('#commonModal .modal-body').html(data);
        });
    }
});
$(document).on('submit', '#test_email', function(e) {
    e.preventDefault();
    $("#email_sending").show();
    var post = $(this).serialize();
    var url = $(this).attr('action');
    $.ajax({
        type: "post",
        url: url,
        data: post,
        cache: false,
        beforeSend: function() {
            $('#test_email .btn-create').attr('disabled', 'disabled');
        },
        success: function(data) {
            if (data.is_success) {
                show_toastr('Success', data.message, 'success');
            } else {
                show_toastr('Error', data.message, 'error');
            }
            $("#email_sending").hide();
        },
        complete: function() {
            $('#test_email .btn-create').removeAttr('disabled');
        },
    });
});
</script>

<script type="text/javascript">
function enablecookie() {
    const element = $('#enable_cookie').is(':checked');
    $('.cookieDiv').addClass('disabledCookie');
    if (element == true) {
        $('.cookieDiv').removeClass('disabledCookie');
        $("#cookie_logging").attr('checked', true);
    } else {
        $('.cookieDiv').addClass('disabledCookie');
        $("#cookie_logging").attr('checked', false);
    }
}
</script>

<script type="text/javascript">
$(document).on("click", ".email-template-checkbox", function() {
    var chbox = $(this);
    $.ajax({
        url: chbox.attr('data-url'),
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            status: chbox.val()
        },
        type: 'post',
        success: function(response) {
            if (response.is_success) {
                show_toastr('Success', response.success, 'success');
                if (chbox.val() == 1) {
                    $('#' + chbox.attr('id')).val(0);
                } else {
                    $('#' + chbox.attr('id')).val(1);
                }
            } else {
                show_toastr('Error', response.error, 'error');
            }
        },
        error: function(response) {
            response = response.responseJSON;
            if (response.is_success) {
                show_toastr('Error', response.error, 'error');
            } else {
                show_toastr('Error', response, 'error');
            }
        }
    })
});
</script>


<script>
var custdarklayout = document.querySelector("#cust-darklayout");
custdarklayout.addEventListener("click", function() {
    var lightLogo = document.getElementById("blah3").src;
    var site_rtl = @json(isset($setting['site_rtl']) && $setting['site_rtl'] ? $setting['site_rtl'] : '');

    var darkLogo = document.getElementById("blah2").src;

    if (custdarklayout.checked) {
        if (site_rtl == 'on') {
            document
                .querySelector("#main-style-link")
                .setAttribute("href", "{{ asset('assets/css/style-dark-rtl.css') }}");

        } else {
            document
                .querySelector("#main-style-link")
                .setAttribute("href", "{{ asset('assets/css/style-dark.css') }}");
        }
        document.body.style.background = 'linear-gradient(141.55deg, #22242C 3.46%, #22242C 99.86%)';

        document
            .querySelector(".m-header > .b-brand > .logo-lg")
            .setAttribute("src", lightLogo);
    } else {
        if (site_rtl == 'on') {
            document
                .querySelector("#main-style-link")
                .setAttribute("href", "{{ asset('assets/css/style-rtl.css') }}");
        } else {
            document
                .querySelector("#main-style-link")
                .setAttribute("href", "{{ asset('assets/css/style.css') }}");
        }
        document
            .querySelector(".m-header > .b-brand > .logo-lg")
            .setAttribute("src", darkLogo);
        document.body.style.setProperty('background',
            'linear-gradient(141.55deg, rgba(240, 244, 243, 0) 3.46%, #f0f4f3 99.86%)', 'important');
    }
});


var custthemebg = document.querySelector("#cust-theme-bg");
custthemebg.addEventListener("click", function() {
    if (custthemebg.checked) {
        document.querySelector(".dash-sidebar").classList.add("transprent-bg");
        document
            .querySelector(".dash-header:not(.dash-mob-header)")
            .classList.add("transprent-bg");
        setting - menu - div
            .querySelector(".dash-header:not(.dash-mob-header)")
            .classList.remove("transprent-bg");
    } else {
        document.querySelector(".dash-sidebar").classList.remove("transprent-bg");
        document
            .querySelector(".dash-header:not(.dash-mob-header)")
            .classList.remove("transprent-bg");
    }
});
</script>
@endpush