@extends('layouts.admin')
@section('page-title')
    {{ __('Manage User Log') }}
@endsection



@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">{{ __('Users') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('User Log') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('userlog') }}" id="userlogin_filter" method="GET">
                        @csrf
                        <div class="row align-items-center justify-content-end">
                            <div class="col-xl-5">
                                <div class="row">

                                    <div class="col-xl-6 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            <label for="select_month" class="form-label">{{ __('Select Month') }}</label>
                                            <input type="month" name="month" value="{{ isset($_GET['month']) ? $_GET['month'] : date('Y-m') }}"  class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-xl-6 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            <label for="user_id" class="form-label">{{ __('Select User') }}</label>
                                            <select name="user" class="form-control select" id="user_id">
                                                @foreach($usersList as $key => $value)
                                                    <option value="{{ $key }}" {{ isset($_GET['user']) && $_GET['user'] == $key ? 'selected' : '' }}>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-auto">
                                <div class="row">
                                    <div class="col-auto mt-4">
                                        <a href="#" class="btn btn-sm btn-primary me-2"
                                            onclick="document.getElementById('userlogin_filter').submit(); return false;"
                                            data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                            data-original-title="{{ __('apply') }}">
                                            <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                        </a>
                                        <a href="{{ route('userlog') }}" class="btn btn-sm btn-danger"
                                            data-bs-toggle="tooltip" title="" data-bs-original-title="Reset">
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
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table id="pc-dt-simple" class="table">
                        <thead class="thead-light">
                            <tr>
                                <th> {{ __('Name') }}</th>
                                <th>{{ __('Role') }}</th>
                                <th> {{ __('Email') }}</th>
                                <th> {{ __('Ip') }}</th>
                                <th> {{ __('Last Login') }}</th>
                                <th>{{ __('Country') }}</th>
                                <th> {{ __('Device Type') }}</th>
                                <th>{{ __('OS Name') }}</th>
                                @if (Laratrust::hasPermission('userlog show') || Laratrust::hasPermission('userlog delete'))
                                    <th class="text-right">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    @php
                                        $data = json_decode($user->details);
                                    @endphp
                                        <td>{{ $user->user_name }}</td>
                                        <th>
                                            <div class="badge bg-primary p-2 role-badge">{{ $user->role }}
                                            </div>
                                        </th>
                                        <td>{{ $user->user_email }}</td>
                                        <td>{{ $user->ip }}</td>
                                        <td>{{ $user->date }}</td>
                                        <td>{{ isset($data->country) ? $data->country : '' }}</td>
                                        <td>{{ $data->device_type }}</td>
                                        <td>{{ $data->os_name }}</td>
                                        <td>
                                            @permission('userlog show')
                                                <div class="action-btn me-2">
                                                    <a href="#" data-url="{{ route('userlog.display', $user->id) }}"
                                                        class="mx-3 bg-warning btn btn-sm d-inline-flex align-items-center"
                                                        title="{{ __('View Log Details') }}" data-ajax-popup="true"
                                                        data-size="xs" data-title="{{ __('View User Log') }}">
                                                        <span class="text-white"><i class="ti ti-eye"></i></span>
                                                    </a>
                                                </div>
                                            @endpermission

                                            @permission('userlog delete')
                                                <div class="action-btn">
                                                    <form method="POST" action="{{ route('userlog.destroy', $user->id) }}"
                                                        id="user-form-{{ $user->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input name="_method" type="hidden" value="DELETE">
                                                        <a class="mx-3 bg-danger btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="Delete" aria-label="Delete"
                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                            data-confirm-yes="delete-form-{{ $user->id }}"><i
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
@endsection
