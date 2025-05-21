@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Knowledge') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Knowledge') }}</li>
@endsection
@push('css-page')
    <link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
@endpush


@section('multiple-action-button')
    {{-- Add Button Hook --}}
    @stack('addButtonHook')
    @permission('knowledgebase create')
        <a href="#" class="btn btn-sm btn-primary btn-icon" title="{{ __('Create Knowledge') }}" data-bs-toggle="tooltip"
            data-bs-placement="top" data-ajax-popup="true" data-title="{{ __(key: 'Create Knowledge') }}"
            data-url="{{ route('admin.knowledge.create') }}" data-size="lg"><i class="ti ti-plus"></i></a>
    @endpermission

    @permission('knowledgebase-category manage')
        <div class="btn btn-sm btn-primary btn-icon float-end ms-2" data-bs-toggle="tooltip" data-bs-placement="top"
            title="{{ __('Create Knowledge Category') }}">
            <a href="{{ route('admin.knowledgecategory') }}" class=""><i class="ti ti-vector-bezier text-white"></i></a>
        </div>
    @endpermission
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive knowledge-table-wrapper">
                        <table id="pc-dt-simple" class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th class="w-25">{{ __('Title') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th class="text-end me-3">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($knowledges as $index => $knowledge)
                                    <tr>
                                        <th scope="row">{{ ++$index }}</th>
                                        <td><span class="font-weight-bold white-space">{{ $knowledge->title }}</span>
                                        </td>
                                        <td>
                                            <span class="font-weight-normal">
                                                {{ !empty($knowledge->getCategoryInfo) ? $knowledge->getCategoryInfo->title : '-' }}
                                            </span>
                                        </td>

                                        <td class="text-end">
                                            @permission('knowledgebase show')
                                                <div class="action-btn me-2">
                                                    <a href="#" class="btn btn-sm bg-warning btn-icon text-white"
                                                        title="{{ __('Show Knowledge') }}" data-bs-toggle="tooltip"
                                                        data-url="{{ route('admin.show.knowledgebase', $knowledge->id) }}"
                                                        data-bs-placement="top" data-ajax-popup="true"
                                                        data-title="{{ __('Show Knowledge') }}" data-size="lg"> <i
                                                            class="ti ti-eye"></i></a>
                                                </div>
                                            @endpermission
                                            @permission('knowledgebase edit')
                                                <div class="action-btn me-2">
                                                    <a href="#" class="btn btn-sm bg-info btn-icon text-white"
                                                        title="{{ __('Edit Knowledge') }}" data-bs-toggle="tooltip"
                                                        data-url="{{ route('admin.knowledge.edit', $knowledge->id) }}"
                                                        data-bs-placement="top" data-ajax-popup="true"
                                                        data-title="{{ __('Edit Knowledge') }}" data-size="lg"> <i
                                                            class="ti ti-pencil"></i></a>
                                                </div>
                                            @endpermission
                                            @permission('knowledgebase delete')
                                                <div class="action-btn">
                                                    <form method="POST"
                                                        action="{{ route('admin.knowledge.destroy', $knowledge->id) }}"
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

@push('scripts')
    <script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>
@endpush