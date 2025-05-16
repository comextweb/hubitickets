@extends('layouts.admin')
@section('page-title')
    {{ __('Add-on Manager') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Add-on Manager') }}</li>
@endsection
@push('css')
    <style>
        .system-version h5 {
            position: absolute;
            bottom: -44px;
            right: 27px;
        }

        .center-text {
            display: flex;
            flex-direction: column;
        }

        .center-text .text-primary {
            font-size: 14px;
            margin-top: 5px;
        }

        .theme-main {
            display: flex;
            align-items: center;
        }

        .theme-main .theme-avtar {
            margin-right: 15px;
        }

        @media only screen and (max-width: 575px) {
            .system-version h5 {
                position: unset;
                margin-bottom: 0px;
            }

            .system-version {
                text-align: center;
                margin-bottom: -22px;
            }
        }
    </style>
@endpush
@section('multiple-action-button')
    @if (Auth::user()->hasRole('admin'))
        <div>
            <a href="{{ route('admin.addon.add') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title=""
                data-bs-original-title="{{ __('ModuleSetup') }}">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    @endif
@endsection

@section('content')
    <div class="row justify-content-center px-0">
        <div class=" col-12">
            <div class="add-on-banner mb-4">
                <img src="{{ asset('assets/images/addon-manager/add-on-banner-layer.png') }}" class="banner-layer"
                    alt="banner-layer">
                <div class="row  row-gap align-items-center">
                    <div class="col-xxl-4 col-md-6 col-12">
                        <div class="add-on-banner-image">
                            <img src="{{ asset('assets/images/addon-manager/add-on-banner-image.png') }}"
                                alt="banner-image">
                        </div>
                    </div>
                    <div class="col-xxl-4 col-md-6 col-12">
                        <div class="add-on-banner-content text-center ">
                            <a href="https://workdo.io/product-category/ticketgo-addon/?utm_source=demo&utm_medium=ticketgo&utm_campaign=btn"
                                class="btn btn-light mb-md-3 mb-2" target="new">
                                <img src="https://workdo.io/wp-content/uploads/2023/03/favicon.jpg" alt="">
                                <span>{{ __('Click Here') }}</span>
                            </a>
                            <h2>{{ __('Buy More Add-on') }}</h2>
                            <p>+{{ count($exploreAddons) }}<span>{{ __('Premium Add-on') }}</span></p>
                        </div>
                    </div>
                    <div class="col-xxl-4 col-12">
                        <div
                            class="add-on-btn d-flex flex-wrap align-items-center justify-content-xxl-end justify-content-center gap-2">
                            <a class="btn btn-primary"
                                href="https://workdo.io/product-category/ticketgo-addon/?utm_source=demo&utm_medium=ticketgo&utm_campaign=btn"
                                target="new">
                                {{ __('Buy More Add-on') }}
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <div class="event-cards row px-0">
            <h2 class="mb-4">{{ __('Installed Add-on') }}</h2>

            @foreach ($modules as $module)
                @php
                    $id = strtolower(preg_replace('/\s+/', '_', $module->name));
                @endphp
                @if (!isset($module->display) || $module->display == true)
                    <div class="col-xxl-2 col-xl-3 col-lg-3 col-md-4 col-sm-6 product-card ">
                        <div class="card {{ $module->isEnabled() ? 'enable_module' : 'disable_module' }}">
                            <div class="product-img">
                                <div class="theme-main">
                                    <div class="theme-avtar">
                                        <img src="{{ $module->image }}" alt="{{ $module->name }}" class="img-user"
                                            style="max-width: 100%">
                                    </div>
                                    <div class="center-text">
                                        <small class="text-muted">
                                            @if ($module->isEnabled())
                                                <span class="badge bg-success">{{ __('Enable') }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ __('Disable') }}</span>
                                            @endif
                                        </small>
                                        <small
                                            class="text-primary">{{ __('V') }}{{ sprintf('%.1f', $module->version) }}</small>
                                    </div>
                                </div>
                                <div class="checkbox-custom">
                                    <div class="btn-group card-option">
                                        <button type="button" class="btn p-0" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end" style="">
                                            @if ($module->isEnabled())
                                                <a href="#!" class="dropdown-item module_change"
                                                    data-id="{{ $id }}">
                                                    <span>{{ __('Disable') }}</span>
                                                </a>
                                            @else
                                                <a href="#!" class="dropdown-item module_change"
                                                    data-id="{{ $id }}">
                                                    <span>{{ __('Enable') }}</span>
                                                </a>
                                            @endif
                                            <form action="{{ route('admin.addon.enable') }}" method="POST"
                                                id="form_{{ $id }}">
                                                @csrf
                                                <input type="hidden" name="name" value="{{ $module->name }}">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="product-content">
                                <h4 class="text-capitalize mb-0"> {{ $module->alias }}</h4>
                                <p class="text-muted text-sm mt-2 mb-0">
                                    {{ $module->description ?? '' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>


        <div class="col-12">
            <h2 class="mb-4 mt-2">{{ __('Explore Add-on') }}</h2>

            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Premium Add-on') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($exploreAddons as $module)
                            @php
                                $id = strtolower(preg_replace('/\s+/', '_', $module->name));
                            @endphp

                            <div class="col-xxl-3 col-lg-4 col-sm-6 col-12">
                                <div class="product-card">
                                    <a href="#">
                                        <div class="addon-card">
                                            <div class="product-img">
                                                <div class="theme-main">
                                                    <div class="theme-avtar">
                                                        <img src="{{ $module->image }}" alt="{{ $module->name }}"
                                                            class="img-user" style="max-width: 100%">
                                                    </div>
                                                </div>
                                                <h5 class="text-capitalize mb-0">{{ $module->name }}</h5>
                                            </div>
                                            <div class="product-content">
                                                <a class="btn btn-light-primary w-100" href="{{ $module->url }}"
                                                    target="_blank">View Details</a>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="system-version">
        @php
            $version = config('verification.system_version');
        @endphp
        <h5 class="text-muted">{{ !empty($version) ? 'V' . $version : '' }}</h5>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).on('click', '.module_change', function() {
            var id = $(this).attr('data-id');
            $('#form_' + id).submit();
        });
    </script>
@endpush
