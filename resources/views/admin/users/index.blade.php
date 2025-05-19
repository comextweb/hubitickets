@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Users') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Users') }}</li>
@endsection
@section('multiple-action-button')
    @permission('user create')
    <a href="{{ route('admin.users.create') }}" class="me-2">
        <div class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-placement="top"
            title="{{ __('Create User') }}">
            <i class="ti ti-plus text-white"></i>
        </div>
    </a>
    @endpermission
    @permission('userlog manage')
    <a href="{{ route('userlog') }}" class="btn btn-sm btn-primary btn-icon" title="{{ __('User Login History') }}"
        data-bs-toggle="tooltip" data-bs-placement="top">
        <i class="ti ti-user-check"></i>
    </a>
    @endpermission
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table id="pc-dt-simple" class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">{{ __('Picture') }}</th>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Email') }}</th>
                                    <th scope="col">{{ __('Role') }}</th>
                                    @if(moduleIsActive('OutOfOffice'))
                                        <th scope="col">{{ __('Available') }}</th>
                                    @endif
                                    <th scope="col" class="text-end me-3">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $index => $user)
                                    <tr>
                                        <th scope="row">{{ ++$index }}</th>
                                        <td>
                                            <a href="{{ !empty($user->avatar) && checkFile($user->avatar) ? getFile($user->avatar) : getFile('uploads/users-avatar/avatar.png') }}"
                                                target="_blank">
                                                <img src="{{ !empty($user->avatar) && checkFile($user->avatar) ? getFile($user->avatar) : getFile('uploads/users-avatar/avatar.png') }}"
                                                    class="rounded border-2 border border-primary" width="35" id="blah3"
                                                    style="border-color: #0CAF60 !important; ">
                                            </a>
                                        </td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge bg-primary p-2 text-center role-badge">
                                                {{ $user->type }}
                                            </span>
                                        </td>
                                        @if(isset($isUserActive) && $isUserActive)
                                            <td>
                                                @if(isset($user->is_available) && $user->is_available == '1')
                                                    <span class="badge bg-primary p-2 role-badge">
                                                        {{__('Available')}}
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger p-2 role-badge">
                                                        {{__('Unavailable')}}
                                                    </span>
                                                @endif
                                            </td>
                                        @endif
                                        <td class="text-end me-3">
                                            @permission('user login manage')
                                            @if ($user->is_enable_login == 1)
                                                <div class="action-btn me-2">
                                                    <a href="{{ route('users.login', \Crypt::encrypt($user->id)) }}"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center bg-danger"
                                                        data-bs-toggle="tooltip" data-bs-original-title="{{ __('Login Disable') }}">
                                                        <span class="text-white"><i class="ti ti-road-sign"></i></a>
                                                </div>
                                            @elseif ($user->is_enable_login == 0 && $user->password == null)
                                                <div class="action-btn me-2">
                                                    <a href="#" data-url="{{ route('user.reset', \Crypt::encrypt($user->id)) }}"
                                                        data-ajax-popup="true" data-size="md"
                                                        class="mx-3 bg-secondary btn btn-sm d-inline-flex align-items-center login_enable"
                                                        data-title="{{ __('New Password') }}" data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('New Password') }}"> <span
                                                            class="text-white"><i class="ti ti-road-sign"></i></a>
                                                </div>
                                            @else
                                                <div class="action-btn me-2">
                                                    <a href="{{ route('users.login', \Crypt::encrypt($user->id)) }}"
                                                        class="mx-3 bg-success btn btn-sm d-inline-flex align-items-center login_enable"
                                                        data-bs-toggle="tooltip" data-bs-original-title="{{ __('Login Enable') }}">
                                                        <span class="text-white"> <i class="ti ti-road-sign"></i>
                                                    </a>
                                                </div>
                                            @endif
                                            @endpermission
                                            @permission('user reset password')
                                            <div class="action-btn me-2">
                                                <a href="#" class="mx-3 bg-warning btn btn-sm d-inline-flex align-items-center"
                                                    data-size="md"
                                                    data-url="{{ route('user.reset', \Crypt::encrypt($user->id)) }}"
                                                    data-ajax-popup="true" data-title="{{ __('Reset Password') }}"
                                                    data-toggle="tooltip" title="{{ __('Reset Password') }}">
                                                    <span class="text-white"> <i class="ti ti-key"></i> </span>
                                                </a>
                                            </div>
                                            @endpermission
                                            @permission('user edit')
                                            <div class="action-btn me-2">
                                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                                    class="mx-3 bg-info btn btn-sm d-inline-flex align-items-center"
                                                    data-toggle="tooltip" title="{{ __('Edit') }}"> <span class="text-white"> <i
                                                            class="ti ti-pencil"></i></span></a>
                                            </div>
                                            @endpermission
                                            @permission('user delete')
                                            <div class="action-btn">
                                                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                                                    id="delete-form-{{ $user->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input name="_method" type="hidden" value="DELETE">

                                                    <a class="mx-3 bg-danger btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                        aria-label="Delete" data-confirm="{{ __('Are You Sure?') }}"
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
    </div>
@endsection