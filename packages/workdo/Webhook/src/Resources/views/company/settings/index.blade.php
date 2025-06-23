<link rel="stylesheet" href="{{ asset('packages/workdo/Webhook/src/Resources/assets/style.css') }}">
@permission('webhook manage')
    <div class="card table-card" id="webhook-sidenav">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-10 ">
                    <h5 class="">{{ __('Webhook Settings') }}</h5>
                    <small>{{ __('Edit your Webhook settings') }}</small>
                </div>
                @permission('webhook create')
                    <div class="col-sm-2  text-end">
                        <a class="btn btn-sm btn-primary btn-icon" data-ajax-popup="true" data-size="md"
                            data-title="{{ __('Create New Webhook') }}" data-url="{{ route('webhook.create') }}"
                            data-bs-toggle="tooltip" data-bs-original-title="{{ __('Create') }}" data-bs-placement="bottom">
                            <i class="ti ti-plus text-white"></i>
                        </a>
                    </div>
                @endpermission
            </div>
        </div>

        <div class="card-body p-3" style="max-height: 270px; overflow:auto">
            <div class="table-responsive">
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th>{{ __('Add-On') }}</th>
                            <th>{{ __('Module') }}</th>
                            <th>{{ __('Method') }}</th>
                            <th>{{ __('Url') }}</th>
                            @if (Laratrust::hasPermission('webhook edit') || Laratrust::hasPermission('webhook delete'))
                                <th class="">{{ __('Action') }}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($webhook_module as $key => $module)
                            @if (!empty($module->module) && (moduleIsActive($module->module->module) || $module->module->module == 'general'))
                                <tr>
                                    <td class="text-capitalize">{{ moduleAliasName($module->module->module) }}</td>
                                    <td>{{ $module->module->submodule }}</td>
                                    <td>{{ $module->method }}</td>
                                    <td>{{ $module->url }}</td>
                                    <td class="">
                                        @permission('webhook edit')
                                            <div class="action-btn me-2">
                                                <a href="#"
                                                    class="mx-3 bg-info btn btn-sm d-inline-flex align-items-center"
                                                    data-size="md" data-url="{{ route('webhook.edit', $module->id) }}"
                                                    data-ajax-popup="true" data-title="{{ __('Edit Webhook') }}"
                                                    data-toggle="tooltip" title="{{ __('Edit') }}" data-bs-placement="bottom">
                                                    <span class="text-white"> <i class="ti ti-pencil"></i> </span>
                                                </a>
                                            </div>
                                        @endpermission
                                        @permission('webhook delete')
                                            <div class="action-btn">
                                                <form method="POST" action="{{ route('webhook.destroy', $module->id) }}"
                                                    id="user-form-{{ $module->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input name="_method" type="hidden" value="DELETE">
                                                    <a class="mx-3 bg-danger btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                        data-bs-toggle="tooltip" title="" data-bs-original-title="{{__('Delete')}}" data-bs-placement="bottom"
                                                        aria-label="Delete" data-confirm="{{ __('Are You Sure?') }}"
                                                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                        data-confirm-yes="delete-form-{{ $module->id }}"><i
                                                            class="ti ti-trash text-white text-white"></i></a>
                                                </form>
                                            </div>
                                        @endpermission
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endpermission
