@extends('layouts.admin')
@stack('whatsappchatbot')
@section('page-title')
    {{ __('Create Ticket') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Create Ticket') }}</li>
@endsection
@push('css-page')
    <link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
@endpush

@section('content')
    <form action="{{ route('admin.tickets.store') }}" class="needs-validation mt-3" method="POST" enctype="multipart/form-data"
        novalidate>
        @csrf
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="card">
                    <div
                        class="card-header flex-column flex-lg-row  d-flex align-items-lg-center gap-2 justify-content-between">
                        <h5>{{ __('Ticket Information') }}</h5>
                        @if (isset($settings['is_enabled']) && $settings['is_enabled'] == 'on')
                            <a class="btn btn-primary btn-sm float-end ms-2" href="#" data-size="lg"
                                data-ajax-popup-over="true" data-url="{{ route('generate', ['support']) }}"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}"
                                data-title="{{ __('Generate Content with AI') }}"><i class="fas fa-robot me-1">
                                    </i>{{ __('Generate with AI') }}</a>
                        @endif
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="require form-label">{{ __('Name') }}</label><x-required></x-required>
                                <input class="form-control  @error('name') is-invalid @enderror" type="text"
                                    name="name" placeholder="{{ __('Name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label class="require form-label">{{ __('Email') }}</label><x-required></x-required>
                                <input class="form-control @error('email') is-invalid @enderror" type="email"
                                    name="email" placeholder="{{ __('Email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label class="require form-label">{{ __('Category') }}</label><x-required></x-required>
                                <select id="category" class="form-control @error('category') is-invalid @enderror"
                                    name="category" required>
                                    <option value="">{{ __('Select Category') }}</option>
                                    @foreach ($categoryTree as $category)
                                        <option value="{{ $category['id'] }}"
                                            {{ old('category') == $category['id'] ? 'selected' : '' }}>
                                            {!! $category['name'] !!}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            @if (moduleIsActive('OutOfOffice'))
                                @stack('is_available')
                            @else
                                <div class="form-group col-md-6">
                                    <label class="require form-label">{{ __('Agent') }}</label><x-required></x-required>
                                    <select id="agent" class="form-control @error('agent') is-invalid @enderror"
                                        name="agent" required>
                                        <option value="">{{ __('Select Agent') }}</option>

                                        @foreach ($users as $agent)
                                            <option value="{{ $agent->id }}"
                                                {{ old('agent') == $agent->id ? 'selected' : '' }}>{{ $agent->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('agent')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            @endif

                            <div class="form-group col-md-6">
                                <label class="require form-label">{{ __('Status') }}</label><x-required></x-required>
                                <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                                    <option value="">{{ __('Select Status') }}</option>
                                    <option value="New Ticket">{{ __('New Ticket') }}</option>
                                    <option value="In Progress">{{ __('In Progress') }}</option>
                                    <option value="On Hold">{{ __('On Hold') }}</option>
                                    <option value="Closed">{{ __('Closed') }}</option>
                                    <option value="Resolved">{{ __('Resolved') }}</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label class="require form-label">{{ __('Subject') }}</label><x-required></x-required>
                                <input class="form-control @error('subject') is-invalid @enderror" type="text"
                                    name="subject" placeholder="{{ __('Subject') }}" required>
                                @error('subject')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label class="require form-label">{{ __('Priority') }}</label><x-required></x-required>
                                <select class="form-control @error('priority') is-invalid @enderror" name="priority"
                                    required>
                                    <option value="">{{ __('Select Priority') }}</option>
                                    @foreach ($priorities as $priority)
                                        <option value="{{ $priority->id }}">{{ $priority->name }}</option>
                                    @endforeach
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            {{-- <x-mobile divClass="col-md-6"></x-mobile> --}}

                            <div class="form-group col-md-6">
                                <label class="require form-label">{{ __('Attachments') }}
                                    <small>({{ __('You can select multiple files') }})</small> </label>
                                <div class="choose-file form-group">
                                    <label for="file" class="form-label d-block">
                                        <input type="file" name="attachments[]" id="file"
                                            class="form-control  @error('attachments') is-invalid @enderror"
                                            multiple="" data-filename="multiple_file_selection"
                                            onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])" >
                                        <img src="" id="blah" width="100px" class="mt-3"/>
                                        @error('attachments')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </label>
                                </div>
                                <p class="multiple_file_selection mb-0"></p>
                            </div>



                            <div class="form-group col-md-12 mb-0">
                                <label class="require form-label">{{ __('Description') }}</label>
                                <textarea name="description" id="description" class="form-control summernote-simple" required></textarea>
                                <p class="text-danger summernote_text"></p>
                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            @if (!$customFields->isEmpty())
                                @include('admin.customFields.formBuilder')
                            @endif
                        </div>
                        <div class="d-flex justify-content-end text-end">
                            <button class="btn btn-secondary custom-cancel-btn btn-submit me-2" type="button"
                                onclick="window.location='{{ route('admin.new.chat') }}'">{{ __('Cancel') }}</button>
                            <button class="btn btn-primary btn-block btn-submit">{{ __('Create') }}</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('scripts')
    <script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>
@endpush
