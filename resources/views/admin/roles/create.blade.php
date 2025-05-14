<form action="{{ route('admin.role.store') }}" method="POST" class="needs-validation" novalidate>
    @csrf
    <div class="modal-body p-0">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="name" class="form-label">{{ __('Name') }}</label><x-required></x-required>
                    <input type="text" name="name" class="form-control"
                        placeholder="{{ __('Enter Role Name') }}" required>
                    @error('name')
                        <small class="invalid-name" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </small>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <!-- [ sample-page ] start -->
            @if (!empty($permissions))
                <div class="col-sm-12 col-md-10 col-xxl-12 col-md-12">
                    <div class="card mb-3">
                        <ul class="nav nav-pills nav-fill p-3 role-tab" id="pills-tab" role="tablist">
                            @foreach ($modules as $module)
                                @if (moduleIsActive($module) || $module == 'General')
                                    <li class="nav-item text-start" role="presentation">
                                        <button
                                            class="nav-link text-capitalize w-auto {{ $loop->index == 0 ? 'active' : '' }}"
                                            id="pills-{{ strtolower($module) }}-tab" data-bs-toggle="pill"
                                            data-bs-target="#pills-{{ strtolower($module) }}"
                                            type="button">{{ $module }}
                                        </button>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    <div class="px-0 card-body">
                        <div class="tab-content" id="pills-tabContent">
                            @foreach ($modules as $module)
                                @if (moduleIsActive($module) || $module == 'General')
                                    <div class="tab-pane text-capitalize fade show {{ $loop->index == 0 ? 'active' : '' }}"
                                        id="pills-{{ strtolower($module) }}" role="tabpanel"
                                        aria-labelledby="pills-{{ strtolower($module) }}-tab">
                                        <input type="checkbox" class="form-check-input pointer"
                                            name="checkall-{{ strtolower($module) }}"
                                            id="checkall-{{ strtolower($module) }}"
                                            onclick="Checkall('{{ strtolower($module) }}')">
                                        <small class="text-muted mx-2">
                                            <label for="checkall-{{ strtolower($module) }}"
                                                class="form-check-label pointer">
                                                {{ 'Assign ' . $module . ' Permission to Roles' }}
                                            </label>

                                        </small>
                                        <div class="table-responsive">
                                            <table class="table table-striped mb-0  mt-3" id="dataTable-1">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>{{ __('Module') }} </th>
                                                        <th>{{ __('Permissions') }} </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $permissions = getPermissionsByModule($module);
                                                        $m_permissions = array_column(
                                                            $permissions->toArray(),
                                                            'name',
                                                        );
                                                        $module_list = [];
                                                        foreach ($m_permissions as $key => $value) {
                                                            array_push($module_list, strtok($value, ' '));
                                                        }
                                                        $module_list = array_unique($module_list);
                                                    @endphp
                                                    @foreach ($module_list as $key => $list)
                                                        <tr>
                                                            <td><input type="checkbox"
                                                                    class="form-check-input ischeck pointer"
                                                                    onclick="CheckModule('module_checkbox_{{ $key }}_{{ $list }}_{{ strtolower($module) }}')"
                                                                    id="module_checkbox_{{ $key }}_{{ $list }}_{{ strtolower($module) }}">
                                                            </td>
                                                            <td>
                                                                <label for=""
                                                                    name="module_checkbox_' . $key . '_' . $list . '_' . strtolower($module)"
                                                                    class="form-check-label pointer">{{ strtolower($list) }}</label>
                                                            </td>
                                                            <td
                                                                class="module_checkbox_{{ $key }}_{{ $list }}_{{ strtolower($module) }}">
                                                                <div class="row">
                                                                    @foreach ($permissions as $mkey => $permission)
                                                                        @php
                                                                            $check = strtok($permission->name, ' ');
                                                                            $name = str_replace(
                                                                                $check,
                                                                                '',
                                                                                $permission->name,
                                                                            );
                                                                        @endphp
                                                                        @if ($list == $check)
                                                                            <div
                                                                                class="col-lg-3 col-md-6 form-check mb-2">
                                                                                <input type="checkbox"
                                                                                    name="permissions[]"
                                                                                    value="{{ $permission->id }}"
                                                                                    class="form-check-input checkbox-{{ strtolower($module) }}"
                                                                                    id="permission_{{ $mkey }}_{{ $permission->id }}"
                                                                                    data-module="module_checkbox_{{ $key }}_{{ $list }}_{{ strtolower($module) }}"
                                                                                    onclick="CheckPermission('permission_{{ $mkey }}_{{ $permission->id }}', 'module_checkbox_{{ $key }}_{{ $list }}_{{ strtolower($module) }}')">
                                                                                <label
                                                                                    for="permission_{{ $mkey }}_{{ $permission->id }}"
                                                                                    class="form-check-label">
                                                                                    {{ $name }}
                                                                                </label>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <!-- [ Main Content ] end -->
    </div>
    <div class="modal-footer p-0 pt-3">
        <input type="button" value="{{ __('Cancel') }}" class="btn  btn-secondary" data-bs-dismiss="modal">
        <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">
    </div>
</form>
