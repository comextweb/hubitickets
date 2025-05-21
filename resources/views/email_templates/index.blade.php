@extends('layouts.admin')
@push('script-page')
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
                        toastr('Success', response.success, 'success');
                        if (chbox.val() == 1) {
                            $('#' + chbox.attr('id')).val(0);
                        } else {
                            $('#' + chbox.attr('id')).val(1);
                        }
                    } else {
                        toastr('Error', response.error, 'error');
                    }
                },
                error: function(response) {
                    response = response.responseJSON;
                    if (response.is_success) {
                        toastr('Error', response.error, 'error');
                    } else {
                        toastr('Error', response, 'error');
                    }
                }
            })
        });
    </script>
@endpush
@section('page-title')
    {{ __('Email Templates') }}
@endsection
@section('title')
    <div class="d-inline-block">

        <h5 class="h4 d-inline-block font-weight-400 mb-0">{{ __('Email Templates') }}</h5>

    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>

    <li class="breadcrumb-item active" aria-current="page">{{ __('Email Template') }}</li>
@endsection
@section('action-btn')
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    
                    <div class="table-responsive">
                        <table class="table" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th scope="col" class="sort" data-sort="name"> {{ __('Name') }}</th>
                                    <th scope="col" class="sort" data-sort="name"> {{ __('Module') }}</th>
                                    @if (Laratrust::hasPermission('email-template view'))
                                        <th class="text-end">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($emailTemplates as $emailTemplate)
                                    <tr>
                                        <td>{{ $emailTemplate->action }}</td>
                                        <td>{{ $emailTemplate->module }}</td>
                                        <td>
                                            @permission('email-template view')
                                                <div class="dt-buttons">
                                                    <div class="text-end">
                                                        <div class="action-btn me-2">
                                                            <a href="{{ route('manage.email.language', [$emailTemplate->id, \Auth::user()->lang]) }}"
                                                                class="mx-3 bg-warning btn btn-sm d-inline-flex align-items-center"
                                                                data-toggle="tooltip" title="{{ __('View') }}">
                                                                <span class="text-white"><i class="ti ti-eye"></i></span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endpermission
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
