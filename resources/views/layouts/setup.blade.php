<div class="card sticky-top" style="top:30px">
    <div class="list-group list-group-flush" id="useradd-sidenav">
        @permission('category manage')
            <a href="{{ route('admin.category') }}"
                class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'admin.category' ? ' active' : '' }}">{{ __('Category') }}
                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
            </a>
        @endpermission

        @permission('priority manage')
        <a href="{{ route('admin.priority.index') }}"
            class="list-group-item list-group-item-action border-0 {{ Request::route()->getName() == 'admin.priority.index' ? 'active' : '' }}">{{ __('Priority') }}
            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
        </a>
        @endpermission
    </div>
</div>
