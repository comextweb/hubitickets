@extends('layouts.admin')

@section('page-title')
    {{ __('Edit Profile') }} ({{ $user->name }})
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">{{ __('Users') }}</a></li>
    <li class="breadcrumb-item">{{ __('Edit') }}</li>
@endsection
@push('scripts')
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        })
    </script>
@endpush
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top" style="top:30px">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            <a href="#useradd-1"
                                class="list-group-item list-group-item-action border-0">{{ __('Personal Info') }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#useradd-2"
                                class="list-group-item list-group-item-action border-0">{{ __('Change Password') }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            @if(!Auth::user()->hasRole('customer') )
                            <a href="#authentication-sidenav" class="list-group-item border-0 list-group-item-action">
                                {{ __('Two Factor Authentication') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                @stack('out_of_office_sidebar')
                            </a>
                            @endif
                        </div>
                    </div>
                </div>


                <div class="col-xl-9">
                    <div id="useradd-1">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">{{ __('Personal Information') }}</h5>
                                <small> {{ __('Details about your personal information') }}</small>
                            </div>
                            <form action="{{ route('update.profile', $user->id) }}" class="needs-validation" method="POST" enctype="multipart/form-data" novalidate>
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 mb-3">
                                        <div class="form-group edit-avatar-wrp">
                                            <img src="{{ !empty($user->avatar) && checkFile($user->avatar) ? getFile($user->avatar) : getFile('uploads/users-avatar/avatar.png') . '?' . time() }}"
                                                id="myAvatar" alt="user-image" class="img-thumbnail m-2"
                                                style="height:120px">
                                            <div class="choose-files mt-3">
                                                <label for="file">
                                                    <div class=" bg-primary "> <i class="ti ti-upload px-1"></i>Choose file
                                                        here</div>
                                                    <input type="file"
                                                        accept="image/png, image/gif, image/jpeg,  image/jpg"
                                                        class="form-control" name="avatar" id="file"
                                                        data-filename="avatar-logo">
                                                </label>
                                            </div>
                                        </div>
                                        <small class="">{{__('Please upload a valid image file. Size of image should not be
                                            more than 2MB.')}}</small>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="name" class="form-label">{{ __('Full Name') }}</label>
                                            <input class="form-control @error('name') is-invalid @enderror" name="name"
                                                type="text" id="fullname" placeholder="{{ __('Enter Your Name') }}"
                                                value="{{ $user->name }}" required autocomplete="name">
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="email" class="form-label">{{ __('Email') }}</label>
                                            <input class="form-control @error('email') is-invalid @enderror" name="email"
                                                type="text" id="email"
                                                placeholder="{{ __('Enter Your Email Address') }}"
                                                value="{{ $user->email }}" required autocomplete="email">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="email" class="form-label">{{ __('Mobile Number') }}</label>
                                            <input class="form-control @error('mobile_number') is-invalid @enderror" name="mobile_number"
                                                type="text" id="mobile_number"
                                                placeholder="{{ __('Enter Your Mobile Number') }}"
                                                value="{{ $user->mobile_number ?? '' }}" required autocomplete="mobile_number" pattern="^\+\d{1,3}\d{9,13}$">
                                            @error('mobile_number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class=" text-xs text-danger">
                                                {{ __('Please use with country code. (ex. +91)') }}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <input type="submit" class="btn btn-primary" value="{{__('Save Changes')}}">
                            </div>
                            </form>
                        </div>
                    </div>

                    <div id="useradd-2">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-2">{{ __('Change Password') }}</h5>
                                <small> {{ __('Details about your account password change') }}</small>
                            </div>
                            <form action="{{ route('update.password', $user->id) }}" class="needs-validation mt-3" method="POST" novalidate>
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="current_password" class="col-form-label">{{ __('Current Password') }}</label>
                                            <input type="password" name="current_password" class="form-control" placeholder="{{ __('Enter Current Password') }}" required>
                                            @error('current_password')
                                                <span class="invalid-current_password" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="new_password" class="col-form-label">{{ __('New Password') }}</label>
                                            <input type="password" name="new_password" class="form-control" required placeholder="{{ __('Enter New Password')}}">
                                            @error('new_password')
                                                <span class="invalid-new_password" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="confirm_password" class="col-form-label">{{ __('New Confirm Password') }}</label>
                                            <input type="password" name="confirm_password" class="form-control" required placeholder="{{ __('Enter New Confirm Password')}}">
                                            @error('confirm_password')
                                                <span class="invalid-confirm_password" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <input type="submit" class="btn btn-primary" value="{{ __('Save Changes')}}">
                            </div>
                            </form>
                        </div>
                    </div>
                    @if(!Auth::user()->hasRole('customer') )
                    <div class="card" id="authentication-sidenav">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Two Factor Authentication') }}</h5>
                        </div>
                        <div class="card-body">
                            <p>{{ __('Two factor authentication (2FA) strengthens access security by requiring two methods (also referred to as factors) to verify your identity. Two factor authentication protects against phishing, social engineering and password brute force attacks and secures your logins from attackers exploiting weak or stolen credentials.') }}
                            </p>
                            @if ($data['user']->google2fa_secret == null)
                                <form class="form-horizontal" method="POST" action="{{ route('generate2faSecret') }}">
                                    {{ csrf_field() }}
                                    <div class="col-lg-12 text-center">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __(' Generate Secret Key to Enable 2FA') }}
                                        </button>
                                    </div>
                                </form>
                            @elseif($data['user']->google2fa_enable == 0 && $data['user']->google2fa_secret != null)
                                1. {{ __('Install “Google Authentication App” on your') }} <a
                                    href="https://apps.apple.com/us/app/google-authenticator/id388497605" target="_black">
                                    {{ __('IOS') }}</a> {{ __('or') }} <a
                                    href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2"
                                    target="_black">{{ __('Android phone.') }}</a><br />
                                2. {{ __('Open the Google Authentication App and scan the below QR code.') }}<br />
                                @php
                                    $f = finfo_open();
                                    $mime_type = finfo_buffer($f, $data['google2fa_url'], FILEINFO_MIME_TYPE);
                                @endphp
                                @if ($mime_type == 'text/plain')
                                    <img src="{{ $data['google2fa_url'] }}" alt="">
                                @else
                                    {!! $data['google2fa_url'] !!}
                                @endif
                                <br /><br />
                                {{ __('Alternatively, you can use the code:') }} <code>{{ $data['secret'] }}</code>.<br />
                                3. {{ __('Enter the 6-digit Google Authentication code from the app') }}<br /><br />
                                <form class="form-horizontal needs-validation" novalidate method="POST"
                                    action="{{ route('enable2fa') }}">
                                    {{ csrf_field() }}
                                    <div class="form-group{{ $errors->has('verify-code') ? ' has-error' : '' }}">
                                        <label for="secret"
                                            class="col-form-label">{{ __('Authenticator Code') }}</label>
                                        <input id="secret" type="password" class="form-control" name="secret"
                                            required="required">
                                    </div>
                                    <div class="col-lg-12 text-center">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Enable 2FA') }}
                                        </button>
                                    </div>
                                </form>
                            @elseif($data['user']->google2fa_enable == 1 && $data['user']->google2fa_secret != null)
                                <div class="alert alert-success">
                                    {{ __('2FA is currently') }} <strong>{{ __('Enabled') }}</strong>
                                    {{ __('on your account.') }}
                                </div>
                                <p>{{ __('If you are looking to disable Two Factor Authentication. Please confirm your password and Click Disable 2FA Button.') }}
                                </p>

                                <form class="form-horizontal needs-validation" novalidate method="POST"
                                    action="{{ route('disable2fa') }}">
                                    {{ csrf_field() }}

                                    <div class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">
                                        <label for="change-password"
                                            class="col-form-label">{{ __('Current Password') }}</label>
                                        <input id="current-password" type="password" class="form-control"
                                            name="current-password" required="required">
                                        @if ($errors->has('current-password'))
                                            <span class="help-block">
                                                <strong
                                                    class="text-danger">{{ $errors->first('current-password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-lg-12 text-center">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Disable 2FA') }}
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                    @endif

                    @stack('out_of_office_sidebar_div')

                </div>
            </div>
        </div>
    </div>
@endsection
