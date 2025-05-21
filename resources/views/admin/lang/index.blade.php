@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Languages') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Languages') }}</li>
@endsection
@section('multiple-action-button')
    @permission('language delete')
        @if ($currantLang != (!empty($settings['default_language']) ? $settings['default_language'] : 'en'))
            <div class="action-btn btn btn-sm btn-danger btn-icon m-1 float-end ms-2">
                <form method="POST" action="{{ route('admin.lang.destroy', $currantLang) }}" id="delete-form-{{ $currantLang }}">
                    @csrf
                    @method('DELETE')
                    <a class="mx-3 bg-danger btn btn-sm  align-items-center bs-pass-para show_confirm" data-bs-toggle="tooltip"
                        title="" data-bs-original-title="Delete" aria-label="Delete"
                        data-confirm="{{ __('Are You Sure?') }}"
                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                        data-confirm-yes="delete-form-{{ $currantLang }}"><i class="ti ti-trash text-white text-white"></i></a>
                </form>
            </div>
        @endif
    @endpermission

    @permission('language enable/disable')
        @if ($currantLang != (!empty($settings['default_language']) ? $settings['default_language'] : 'en'))
            <div class="form-check form-switch custom-switch-v1 float-end" style="padding-top: 7px;">
                <input type="hidden" name="disable_lang" value="off">
                <input type="checkbox" class="form-check-input input-primary" name="disable_lang" data-bs-placement="top"
                    title="{{ __('Enable/Disable') }}" id="disable_lang" data-bs-toggle="tooltip"
                    {{ !in_array($currantLang, $disabledLang) ? 'checked' : '' }}>
                <label class="form-check-label" for="disable_lang"></label>
            </div>
        @endif
    @endpermission
@endsection

@section('content')
    <!-- <div class="section-body">
        <div class="row">
            <div class="col-lg-2">
                <div class="card sticky-top" style="top:30px">
                    <div class="list-group list-group-flush" id="useradd-sidenav">
                        @foreach ($languages as $code => $lang)
                            <a href="{{ route('admin.lang.index', [$code]) }}"
                                class="list-group-item list-group-item-action border-0 @if ($currantLang == $code) active @endif">{{ ucFirst($lang) }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-10">
                <div class="card  p-3">
                    <div class="card-body">
                        <ul class="nav nav-pills nav-fill my-4 lang-tab">
                            <li class="nav-item">
                                <a data-href="#labels" class="nav-link active">{{ __('Labels') }}</a>
                            </li>
                            <li class="nav-item">
                                <a data-toggle="tab" data-href="#messages" class="nav-link">{{ __('Messages') }} </a>
                            </li>
                        </ul>
                        <form method="post" action="{{ route('admin.lang.store.data', [$currantLang]) }}">
                            @csrf
                            <div class="tab-content">
                                <div class="tab-pane active" id="labels">
                                    <div class="row">
                                        @foreach ($arrLabel as $label => $value)
                                            <div class="col-lg-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label text-dark">{{ $label }}</label>
                                                    <input type="text" class="form-control"
                                                        name="label[{{ $label }}]" value="{{ $value }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="tab-pane" id="messages">
                                    @foreach ($arrMessage as $fileName => $fileValue)
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h6>{{ ucfirst($fileName) }}</h6>
                                            </div>
                                            @foreach ($fileValue as $label => $value)
                                                @if (is_array($value))
                                                    @foreach ($value as $label2 => $value2)
                                                        @if (is_array($value2))
                                                            @foreach ($value2 as $label3 => $value3)
                                                                @if (is_array($value3))
                                                                    @foreach ($value3 as $label4 => $value4)
                                                                        @if (is_array($value4))
                                                                            @foreach ($value4 as $label5 => $value5)
                                                                                <div class="col-lg-6">
                                                                                    <div class="form-group mb-3">
                                                                                        <label
                                                                                            style="overflow-wrap: break-word;width: 100%;"
                                                                                            class="form-label text-dark">{{ $fileName }}.{{ $label }}.{{ $label2 }}.{{ $label3 }}.{{ $label4 }}.{{ $label5 }}</label>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            name="message[{{ $fileName }}][{{ $label }}][{{ $label2 }}][{{ $label3 }}][{{ $label4 }}][{{ $label5 }}]"
                                                                                            value="{{ $value5 }}">
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        @else
                                                                            <div class="col-lg-6">
                                                                                <div class="form-group mb-3">
                                                                                    <label
                                                                                        style="overflow-wrap: break-word;width: 100%;"
                                                                                        class="form-label text-dark">{{ $fileName }}.{{ $label }}.{{ $label2 }}.{{ $label3 }}.{{ $label4 }}</label>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        name="message[{{ $fileName }}][{{ $label }}][{{ $label2 }}][{{ $label3 }}][{{ $label4 }}]"
                                                                                        value="{{ $value4 }}">
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    <div class="col-lg-6">
                                                                        <div class="form-group mb-3">
                                                                            <label
                                                                                style="overflow-wrap: break-word;width: 100%;"
                                                                                class="form-label text-dark">{{ $fileName }}.{{ $label }}.{{ $label2 }}.{{ $label3 }}</label>
                                                                            <input type="text" class="form-control"
                                                                                name="message[{{ $fileName }}][{{ $label }}][{{ $label2 }}][{{ $label3 }}]"
                                                                                value="{{ $value3 }}">
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            <div class="col-lg-6">
                                                                <div class="form-group mb-3">
                                                                    <label style="overflow-wrap: break-word;width: 100%;"
                                                                        class="form-label text-dark">{{ $fileName }}.{{ $label }}.{{ $label2 }}</label>
                                                                    <input type="text" class="form-control"
                                                                        name="message[{{ $fileName }}][{{ $label }}][{{ $label2 }}]"
                                                                        value="{{ $value2 }}">
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <div class="col-lg-6">
                                                        <div class="form-group mb-3">
                                                            <label style="overflow-wrap: break-word;width: 100%;"
                                                                class="form-label text-dark">{{ $fileName }}.{{ $label }}</label>
                                                            <input type="text" class="form-control"
                                                                name="message[{{ $fileName }}][{{ $label }}]"
                                                                value="{{ $value }}">
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="text-end">
                                <input type="submit" value="{{ __('Save Changes') }}" class="btn btn-primary">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <div class="row">
        <div class="card align-middle p-3">
            <ul class="nav nav-pills pb-3" id="pills-tab" role="tablist">
                <li class="nav-item px-1">
                    <a class="nav-link text-capitalize  {{ ( $module == 'general') ? ' active' : '' }} " href="{{ route('admin.lang.index',[$currantLang]) }}">{{ __('General') }}</a>
                </li>
                @foreach ($modules as $item)
                    @php
                    @endphp
                    <li class="nav-item px-1">
                        <a class="nav-link text-capitalize  {{ ( $module == ($item)) ? ' active' : '' }} " href="{{ route('admin.lang.index',[$currantLang,$item]) }}">{{ moduleAliasName($item) }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="col-lg-2">
            <div class="card">
                <div class="card-body">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        @foreach ($languages as $key => $language)
                            <a href="{{ route('admin.lang.index', [$key,$module]) }}"
                                class="nav-link my-1 font-weight-bold @if ($key ==$currantLang) active @endif">
                                <i class="d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">{{ Str::ucfirst($language)}}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-10">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-end">
                        <div class="col-xl-10">
                            <div class="row">
                                <div class="col-xl-4">
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 month">
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
                                    <div class="btn-box">
                                        <input type="text" id="letter" placeholder="{{__('Enter a letter to filter')}}" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="row">
                                <div class="col-auto">
                                    <button id="filter-btn" class="btn btn-sm btn-primary me-1" data-bs-toggle="tooltip" title="{{ __('Apply') }}"><i class="ti ti-search"></i></button>
                                    <button id="reset-btn" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="{{ __('Reset') }}"><i class="ti ti-refresh"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($module == 'general' || $module == '')
                <div class="card px-3">
                    <ul class="nav nav-pills nav-fill my-4 lang-tab">
                        <li class="nav-item">
                            <a data-href="#labels" class="nav-link active">{{ __('Labels') }}</a>
                        </li>

                        <li class="nav-item">
                            <a data-toggle="tab" data-href="#messages" class="nav-link">{{ __('Messages') }} </a>
                        </li>
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('admin.lang.store.data', [$currantLang, $module]) }}">
                        @csrf
                        <div class="tab-content">
                            <div class="tab-pane active" id="labels">
                                <div class="row" id="labels-container">
                                    @foreach ($arrLabel as $label => $value)
                                        <div class="col-lg-6 label-item">
                                            <div class="form-group mb-3">
                                                <label class="form-label text-dark">{{ $label }}</label>
                                                <input type="text" class="form-control" name="label[{{ $label }}]" value="{{ $value }}">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @if($module == 'general' || $module == '')
                                <div class="tab-pane" id="messages">
                                    @foreach ($arrMessage as $fileName => $fileValue)
                                        <div class="row">
                                            <div class="col-lg-12 label-item">
                                                <h6>{{ ucfirst($fileName) }}</h6>
                                            </div>
                                            @foreach ($fileValue as $label => $value)
                                                @if (is_array($value))
                                                    @foreach ($value as $label2 => $value2)
                                                        @if (is_array($value2))
                                                            @foreach ($value2 as $label3 => $value3)
                                                                @if (is_array($value3))
                                                                    @foreach ($value3 as $label4 => $value4)
                                                                        @if (is_array($value4))
                                                                            @foreach ($value4 as $label5 => $value5)
                                                                                <div class="col-lg-6 label-item">
                                                                                    <div class="form-group mb-3">
                                                                                        <label
                                                                                            class="form-label text-dark">{{ $fileName }}.{{ $label }}.{{ $label2 }}.{{ $label3 }}.{{ $label4 }}.{{ $label5 }}</label>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            name="message[{{ $fileName }}][{{ $label }}][{{ $label2 }}][{{ $label3 }}][{{ $label4 }}][{{ $label5 }}]"
                                                                                            value="{{ $value5 }}">
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        @else
                                                                            <div class="col-lg-6 label-item">
                                                                                <div class="form-group mb-3">
                                                                                    <label
                                                                                        class="form-label text-dark">{{ $fileName }}.{{ $label }}.{{ $label2 }}.{{ $label3 }}.{{ $label4 }}</label>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        name="message[{{ $fileName }}][{{ $label }}][{{ $label2 }}][{{ $label3 }}][{{ $label4 }}]"
                                                                                        value="{{ $value4 }}">
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    <div class="col-lg-6 label-item">
                                                                        <div class="form-group mb-3">
                                                                            <label
                                                                                class="form-label text-dark">{{ $fileName }}.{{ $label }}.{{ $label2 }}.{{ $label3 }}</label>
                                                                            <input type="text" class="form-control"
                                                                                name="message[{{ $fileName }}][{{ $label }}][{{ $label2 }}][{{ $label3 }}]"
                                                                                value="{{ $value3 }}">
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            <div class="col-lg-6 label-item">
                                                                <div class="form-group mb-3">
                                                                    <label
                                                                        class="form-label text-dark">{{ $fileName }}.{{ $label }}.{{ $label2 }}</label>
                                                                    <input type="text" class="form-control"
                                                                        name="message[{{ $fileName }}][{{ $label }}][{{ $label2 }}]"
                                                                        value="{{ $value2 }}">
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <div class="col-lg-6 label-item">
                                                        <div class="form-group mb-3">
                                                            <label
                                                                class="form-label text-dark">{{ $fileName }}.{{ $label }}</label>
                                                            <input type="text" class="form-control"
                                                                name="message[{{ $fileName }}][{{ $label }}]"
                                                                value="{{ $value }}">
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="text-end">
                            <input type="submit" value="{{ __('Save Changes') }}" class="btn btn-primary btn-block btn-submit">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#filter-btn').on('click', function () {
                var letter = $('#letter').val().toLowerCase();

                if (!letter) {
                    toastrs('Error', 'Please enter at least one letter' , 'error')
                            setTimeout(function() {
                                location.reload(true);
                            }, 1500);
                    return;
                }

                $('.label-item').each(function () {
                    var label = $(this).find('label').text().toLowerCase();
                    if (label.includes(letter)) {
                        $(this).show();
                    } else {
                        $(this).hide(); 
                    }
                });
            });
        });

        $(document).ready(function () {
            $('#reset-btn').on('click', function () {
                location.reload();
            });
        });
    </script>

    <script>
        $(document).on('click', '.lang-tab .nav-link', function() {
            $('.lang-tab .nav-link').removeClass('active');
            $('.tab-pane').removeClass('active');
            $(this).addClass('active');
            var id = $('.lang-tab .nav-link.active').attr('data-href');
            $(id).addClass('active');
        });
    </script>


    <script>
        $(document).on('change', '#disable_lang', function() {
            var val = $(this).prop("checked");
            if (val == true) {
                var langMode = 'on';
            } else {
                var langMode = 'off';
            }
            $.ajax({
                type: 'POST',
                url: "{{ route('disablelanguage') }}",
                datType: 'json',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "mode": langMode,
                    "lang": "{{ $currantLang }}"
                },
                success: function(data) {
                    if (data.status == 'success') {
                        show_toastr('Success', data.message, 'success')
                    } else {
                        show_toastr('Error', data.message, 'error')
                    }
                }
            });
        });
    </script>
@endpush
