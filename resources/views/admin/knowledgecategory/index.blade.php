@extends('layouts.admin')

@section('page-title')
    {{ __('Manage KnowledgeBase Category') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.knowledge') }}">{{ __('Knowledge') }}</a></li>
    <li class="breadcrumb-item">{{ __('Category') }}</li>
@endsection

@section('multiple-action-button')
    {{-- Add Button Hook --}}
    @stack('addButtonHook')
    @permission('knowledgebase-category create')
        <a href="#" class="btn btn-sm btn-primary btn-icon" title="{{ __('Create Knowledgebase Category') }}"
            data-bs-toggle="tooltip" data-bs-placement="top" data-ajax-popup="true"
            data-title="{{ __('Create Knowledgebase Category') }}" data-url="{{ route('admin.knowledgecategory.create') }}"
            data-size="md"><i class="ti ti-plus"></i></a>
    @endpermission
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="pc-dt-simple" class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th class="w-50">{{ __('Title') }}</th>
                                    @if (Laratrust::hasPermission('knowledgebase-category edit') || Laratrust::hasPermission('knowledgebase-category delete'))
                                        <th class="text-end me-3">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($knowledges_category as $index => $knowledge)
                                    <tr>
                                        <th scope="row">{{ ++$index }}</th>
                                        <td><span class="font-weight-bold white-space">{{ $knowledge->title }}</span></td>
                                        <td class="text-end">
                                            @permission('knowledgebase-category edit')
                                                <div class="action-btn me-2">
                                                    <a href="#" class="btn btn-sm btn-info btn-icon"
                                                        title="{{ __('Edit Knowledgebase Category') }}"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" data-ajax-popup="true"
                                                        data-title="{{ __('Edit Knowledgebase Category') }}"
                                                        data-url="{{ route('admin.knowledgecategory.edit', $knowledge->id) }}"
                                                        data-size="md"><i class="ti ti-pencil"></i></a>
                                                </div>
                                            @endpermission
                                            @permission('knowledgebase-category delete')
                                                <div class="action-btn">
                                                    <form method="POST"
                                                        action="{{ route('admin.knowledgecategory.destroy', $knowledge->id) }}"
                                                        id="user-form-{{ $knowledge->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input name="_method" type="hidden" value="DELETE">
                                                        <a class="mx-3 bg-danger btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="Delete" aria-label="Delete"
                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                            data-confirm-yes="delete-form-{{ $knowledge->id }}"><i
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
