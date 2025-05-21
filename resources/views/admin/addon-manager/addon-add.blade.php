@extends('layouts.admin')
@section('page-title')
    {{ __('Add New Addons') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.addon.list') }}">{{ __('AddOns') }}</a></li>
    <li class="breadcrumb-item">{{ __('Add New AddOns') }}</li>
@endsection
@push('css-page')
    <link rel="stylesheet" href="{{ asset('public/libs/dropzone/dist/dropzone.css') }}" type="text/css" />
@endpush
@section('content')
    <div class="row justify-content-center">
        <div class="col-sm-12 col-md-10 col-xxl-8">
            <div class="card">
                <div class="card-body">
                    <section>
                        <div id="dropzone">
                            <form class="dropzone needsclick" id="addon-upload">
                                <div class="dz-message needsclick">
                                    {{ __('Drop files here or click here to upload and install.') }}<br>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
            <div class="col-lg-12 text-center">
                <a href="{{ route('admin.addon.list') }}" class="btn btn-primary"> {{ __('Back To Add-on Manager') }}</a>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('public/libs/dropzone/dist/dropzone.js') }}"></script>

    <script>
        Dropzone.autoDiscover = false;
        var dropzone = new Dropzone('#addon-upload', {
            thumbnailHeight: 120,
            thumbnailWidth: 120,
            maxFilesize: 500,
            acceptedFiles: '.zip',
            url: "{{ route('admin.addon.install') }}",
            success: function(file, response) {
                if (response.flag == 1) {
                    show_toastr('Success', response.msg, 'success');
                    setTimeout(() => {
                        window.location.href = "{{ route('admin.addon.list') }}";
                    }, 5000);
                } else {
                    show_toastr('Error', response.msg, 'error');
                    setTimeout(() => {
                        window.location.href = "{{ route('admin.addon.list') }}";
                    }, 2000);
                }
            }
        });
        dropzone.on('sending', function(file, xhr, formData) {
            formData.append('_token', "{{ csrf_token() }}");
        });
    </script>
@endpush
