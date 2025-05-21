@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Roles') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Roles') }}</li>
@endsection
@section('multiple-action-button')
    @permission('user create')
        <a href="#" data-url="{{ route('admin.role.create') }}" data-ajax-popup="true" data-size="xl"
            class="bg-primary btn btn-sm d-inline-flex align-items-center login_enable me-2"
            data-title="{{ __('Create New Role') }}" data-bs-toggle="tooltip"
            data-bs-original-title="{{ __('Create New Role') }}"> <span class="text-white">
                <i class="ti ti-plus text-white"></i></a>
    @endpermission
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table id="pc-dt-simple" class="table role-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Role') }}</th>
                                    <th>{{ __('Permissions') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $key => $role)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td class="permission">
                                            @foreach ($role->permissions as $permission)
                                                <span
                                                    class="badge rounded p-2 m-1 px-3 bg-primary">{{ $permission->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            @permission('role edit')
                                                <div class="action-btn me-2">
                                                    <a href="#"
                                                        class="mx-3 bg-info btn btn-sm d-inline-flex align-items-center"
                                                        data-size="xl"
                                                        data-url="{{ route('admin.role.edit', ['roleId' => $role->id]) }}"
                                                        data-ajax-popup="true" data-title="{{ __('Edit Role') }}"
                                                        data-toggle="tooltip" title="{{ __('Edit Role') }}">
                                                        <span class="text-white"> <i class="ti ti-pencil"></i> </span>
                                                    </a>
                                                </div>
                                            @endpermission
                                            @if (!in_array($role->name, \App\Models\User::$nonEditableRoles))
                                                @permission('role delete')
                                                    <div class="action-btn">
                                                        <form method="POST"
                                                            action="{{ route('admin.role.delete', $role->id) }}"
                                                            id="delete-form-{{ $role->id }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input name="_method" type="hidden" value="DELETE">

                                                            <a class="mx-3 bg-danger btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Delete" aria-label="Delete"
                                                                data-confirm="{{ __('Are You Sure?') }}"
                                                                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                data-confirm-yes="delete-form-{{ $role->id }}"><i
                                                                    class="ti ti-trash text-white text-white"></i></a>
                                                        </form>
                                                    </div>
                                                @endpermission
                                            @endif
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
@push('scripts')
    <script>
        function Checkall(module = null) {

            var ischecked = $("#checkall-" + module).prop('checked');
            if (ischecked == true) {
                $('.checkbox-' + module).prop('checked', true);
            } else {
                $('.checkbox-' + module).prop('checked', false);
            }

            // Get all checkboxes with IDs that start with 'module_checkbox_' and include the module
            var checkboxes = document.querySelectorAll('input[id^="module_checkbox_"]');


            // Check or uncheck all checkboxes based on the 'checkall' checkbox state
            checkboxes.forEach(function(checkbox) {
                var check = $("#checkall-" + module).prop('checked');
                if (checkbox.id.includes(module)) {
                    checkbox.checked = check
                }
            });

            // Call CheckModule to update the module checkbox state
            CheckModule('module_checkbox_' + module);
        }

        function CheckModule(cl = null) {
            var ischecked = $("#" + cl).prop('checked');
            if (ischecked == true) {
                $('.' + cl).find("input[type=checkbox]").prop('checked', true);
            } else {
                $('.' + cl).find("input[type=checkbox]").prop('checked', false);
            }
        }


        function CheckPermission(cl = null, module = null) {
            var ischecked = $("#" + cl).prop('checked');
            var allChecked = true;

            // Check if all permissions for the given module are checked
            $('.' + module).find("input[type=checkbox]").each(function() {
                if (!$(this).prop('checked')) {
                    allChecked = false;
                    return false; // Exit the loop
                }
            });

            // Update the module checkbox based on the state of permissions
            if (allChecked) {
                $('#' + module).prop('checked', true);
            } else {
                $('#' + module).prop('checked', false);
            }
        }

        $(document).ready(function() {
            // Attach the CheckPermission function to all permission checkboxes
            $(document).on('change', 'input[type=checkbox]', function() {
                var id = $(this).attr('id');
                var module = $(this).data('module');
                CheckPermission(id, module);
            });
        });
    </script>
@endpush
