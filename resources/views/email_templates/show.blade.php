@extends('layouts.admin')

@section('page-title')
    {{ __('Email Templates') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Email Templates') }}</li>
@endsection

@section('action-button')
    <div class="row justify-content-end">
        <div class="col-auto">
            <a href="{{ route('email_template.index') }}" class="btn btn-sm btn-primary"
                data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Return"><i
                    class="ti ti-arrow-back-up"></i>
            </a>
        </div>
    </div>
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
@endpush

@section('content')
    <div class="row invoice-row row-gap-1">
        <div class="col-md-4  col-12">
            <div class="card mb-0 h-100">
                <div class="card-body">

                    <form action="{{ route('email_template.update', $emailTemplate->id) }}" class="needs-validation"
                        method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label name="name" class="col-form-label text-dark">{{ __('Name') }}</label>
                                <input type="text" name="name" value="{{ $emailTemplate->action }}"
                                    class="form-control font-style" disabled>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="from" class="col-form-label text-dark">{{ __('From') }}</label>
                                <input type="text" name="from" class="form-control font-style" required
                                    placeholder="{{ __('Enter From Name ') }}" value="{{ $emailTemplate->from }}">
                            </div>
                            <input type="hidden" name="lang" value="{{ $currEmailTempLang->lang }}">
                            <div class="col-12 text-end">
                                <input type="submit" value="{{ __('Save') }}" class="btn btn-print-invoice  btn-primary">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-12">
            <div class="card h-100">
                <div class=" card-body">

                    <div class="row text-xs">
                        <h6 class="font-weight-bold mb-4">{{ __('Place Holders') }}</h6>
                        @php
                            $variables = json_decode($currEmailTempLang->variables);
                        @endphp
                        @if(!empty($variables) > 0)
                            @foreach  ($variables as $key => $var)
                                <div class="col-6 pb-1">
                                    <p class="mb-1">{{__($key)}} : <span class="pull-right text-primary">{{ '{' . $var . '}' }}</span>
                                    </p>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="row">
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#variablesModal">
                            <i class="ti ti-edit"></i> {{ __('Editar Variables JSON') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <h5></h5>
            <div class="row row-gap-1">
                <div class="col-sm-3 col-md-3 col-lg-3 col-xl-3">
                    <div class="card sticky-top language-sidebar email-sidebar mb-0">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            @foreach ($languages as $key => $lang)
                                <a class="list-group-item list-group-item-action border-0 {{ $currEmailTempLang->lang == $key ? 'active' : '' }}"
                                    href="{{ route('manage.email.language', [$emailTemplate->id, $key]) }}">
                                    {{ Str::ucfirst($lang) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-lg-9 col-md-9 col-sm-9">
                    <div class="card p-3">
                        <form action="{{ route('store.email.language', $currEmailTempLang->parent_id) }}"
                            class="needs-validation mt-3" method="POST" enctype="multipart/form-data" novalidate>
                            @csrf
                            <div class="form-group col-12">
                                <label for="subject" class="col-form-label text-dark">{{ __('Subject') }}</label>
                                <input type="text" name="subject" class="form-control font-style" required
                                    value="{{ $currEmailTempLang->subject }}">
                            </div>
                            <div class="form-group col-12">
                                <label class="col-form-label text-dark" for="content">{{ __('Email Message') }}</label>
                                <textarea name="content" class="summernote-simple" id="contenrt"
                                    required>{{ $currEmailTempLang->content }}</textarea>
                                <p class="text-danger summernote_text"></p>
                            </div>

                            <div class="col-md-12 text-end mb-3">
                                <input type="hidden" name="lang" value="{{ $currEmailTempLang->lang }}">
                                <input type="submit" value="{{ __('Save') }}" class="btn btn-print-invoice  btn-primary">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- Modal de edición de variables -->
<div class="modal fade" id="variablesModal" tabindex="-1" aria-labelledby="variablesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('update.email.variables', $currEmailTempLang->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="variablesModalLabel">{{ __('Editar Variables JSON') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="variables" class="form-label">{{ __('Variables JSON') }}</label>
                        <textarea class="form-control" id="variables" name="variables" rows="10" style="font-family: monospace;">@json($variables, JSON_PRETTY_PRINT)</textarea>
                        <small class="text-muted">{{ __('Edita el JSON directamente para agregar o eliminar variables.') }}</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancelar') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Actualizar Variables') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
