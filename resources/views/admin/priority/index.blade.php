@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Priority') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Priority') }}</li>
@endsection
@section('action-button')
    @permission('priority create')
        <div class="float-end">
            <div class="col-auto">
                <a href="#" class="btn btn-sm btn-primary btn-icon" title="{{ __('Create') }}" data-bs-toggle="tooltip"
                    data-bs-placement="top" data-ajax-popup="true" data-title="{{ __('Create Priority') }}"
                    data-url="{{ route('admin.priority.create') }}" data-size="md"><i class="ti ti-plus"></i></a>
            </div>
        </div>
    @endpermission
@endsection
@section('content')
    <div class="row">
        <div class="col-3">
            @include('layouts.setup')
        </div>
        <div class="col-9">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="pc-dt-simple" class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Color') }}</th>
                                    @if (Laratrust::hasPermission('priority edit') || Laratrust::hasPermission('priority delete'))  
                                    <th scope="col" class="text-end me-3">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($priority as $index => $priorities)
                                    <tr>
                                        <th scope="row">{{ ++$index }}</th>
                                        <td>{{ $priorities->name }}</td>
                                        <td><span class="badge"
                                                style="background: {{ $priorities->color }}">&nbsp;&nbsp;&nbsp;</span></td>

                                        <td class="text-end">
                                            @permission('priority edit')
                                            <div class="action-btn me-2">
                                                <a href="#"
                                                    class="mx-3 bg-info btn btn-sm d-inline-flex align-items-center"
                                                    title="{{ __('Edit') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    data-url="{{ route('admin.priority.edit', $priorities->id) }}"
                                                    data-ajax-popup="true" data-title="{{ __('Edit Priority') }}"
                                                    data-size="md"><span class="text-white"><i
                                                            class="ti ti-pencil"></i></span></a>

                                            </div>
                                            @endpermission

                                            @permission('priority delete')
                                            <div class="action-btn me-2">
                                                <form method="POST"
                                                    action="{{ route('admin.priority.destroy', $priorities->id) }}"
                                                    id="">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input name="_method" type="hidden" value="DELETE">
                                                    <a class="mx-3 bg-danger btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                    data-bs-toggle="tooltip" title=""
                                                    data-bs-original-title="Delete" aria-label="Delete"
                                                    data-confirm="{{ __('Are You Sure?') }}"
                                                    data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                    data-confirm-yes="delete-form-{{ $priorities->id }}"><i
                                                        class="ti ti-trash text-white text-white"></i></a>
                                                </form>
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
