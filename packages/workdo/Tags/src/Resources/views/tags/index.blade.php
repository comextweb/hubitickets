@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Tags') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Tags') }}</li>
@endsection

@section('multiple-action-button')
            @permission('tags create')
                <a href="#" data-url="{{ route('tags.create') }}" data-ajax-popup="true" data-size="md"
                    class="bg-primary btn btn-sm d-inline-flex align-items-center login_enable"
                    data-title="{{ __('Create Tags') }}" data-bs-toggle="tooltip"
                    data-bs-original-title="{{ __('Create Tags') }}"> <span class="text-white">
                        <i class="ti ti-plus text-white"></i></a>
            @endpermission
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table id="pc-dt-simple" class="table">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ __('Name') }}</th>
                                @if (Laratrust::hasPermission('tags edit') || Laratrust::hasPermission('tags delete'))
                                    <th class="text-end me-3">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tags as $tag)
                                <tr>
                                    <td> <span class="badge badge-white p-2 px-3 fix_badge"
                                            style="background: {{ isset($tag['color']) ? $tag['color'] : '' }};">
                                            {{ isset($tag['name']) ? $tag['name'] : '' }}  ({{ $tag['count'] ?? '0' }})
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        @permission('tags edit')
                                            <div class="action-btn me-2">
                                                <a href="#"
                                                    class="mx-3 bg-info btn btn-sm d-inline-flex align-items-center"
                                                    data-size="md" data-url="{{ route('tags.edit', $tag['id']) }}"
                                                    data-ajax-popup="true" data-title="{{ __('Edit Tags') }}"
                                                    data-toggle="tooltip" title="{{ __('Edit Tags') }}">
                                                    <span class="text-white"> <i class="ti ti-pencil"></i> </span>
                                                </a>
                                            </div>
                                        @endpermission
                                        @permission('tags delete')
                                            <div class="action-btn">
                                                <form method="POST" action="{{ route('tags.destroy', $tag['id']) }}"
                                                    id="user-form-{{ $tag['id'] }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input name="_method" type="hidden" value="DELETE">
                                                    <a class="mx-3 bg-danger btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                        aria-label="Delete" data-confirm="{{ __('Are You Sure?') }}"
                                                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                        data-confirm-yes="delete-form-{{ $tag['id'] }}"><i
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
