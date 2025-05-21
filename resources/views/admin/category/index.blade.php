@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Category') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Category') }}</li>
@endsection
@section('multiple-action-button')
    @permission('category create')
                <a href="#" class="btn btn-sm btn-primary btn-icon" title="{{ __('Create') }}" data-bs-toggle="tooltip"
                    data-bs-placement="top" data-ajax-popup="true" data-title="{{ __('Create Category') }}"
                    data-url="{{ route('admin.category.create') }}" data-size="lg"><i class="ti ti-plus"></i></a>
    @endpermission
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3">
            @include('layouts.setup')
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <ul class="category-menu">
                        @foreach ($categoryTree as $category)
                            @include('admin.category.recursive', ['category' => $category])
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
