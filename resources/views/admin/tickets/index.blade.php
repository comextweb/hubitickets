@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Tickets') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Tickets') }}</li>
@endsection

@section('multiple-action-button')
    <div class="row justify-content-end">
        <div class="col-auto">
            @permission('ticket export')
                <div class="btn btn-sm btn-primary btn-icon me-2" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="{{ __('Export Tickets CSV file') }}">
                    <a href="{{ route('tickets.export') }}" class=""><i class="ti ti-file-export text-white"></i></a>
                </div>
            @endpermission
            @permission('ticket create')
                <div class="btn btn-sm btn-primary btn-icon float-end " data-bs-toggle="tooltip" data-bs-placement="top"
                    title="{{ __('Create Ticket') }}">
                    <a href="{{ route('admin.tickets.create') }}" class=""><i class="ti ti-plus text-white"></i></a>
                </div>
            @endpermission

        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            @if (session()->has('ticket_id') || session()->has('smtp_error'))
                <div class="alert alert-info">
                    @if (session()->has('ticket_id'))
                        {!! Session::get('ticket_id') !!}
                        @if (session()->has('smtp_error'))
                            {!! Session::get('smtp_error') !!}
                            {{ Session::forget('smtp_error') }}
                        @endif
                        {{ Session::forget('ticket_id') }}
                    @endif
                </div>
            @endif
        </div>

        <div class="col-sm-12">
            <div class="mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.tickets.index') }}" method="GET" enctype="multipart/form-data" id="ticket_index">
                        @csrf
                        <div class="row align-items-center justify-content-end">
                            <div class="col-xl-5">
                                <div class="row">

                                    <div class="col-xl-4 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            <label for="category" class="form-label">{{ __('Category') }}</label>
                                            <select name="category" id="category" class="form-control select">
                                                <option value="">Select a category</option>
                                                @foreach ($categories as $category)
                                                    <option
                                                        value="{{ $category->id }}"{{ isset($_GET['category']) && $_GET['category'] == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }} </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            <label for="status" class="form-label">{{ __('Select Status') }}</label>
                                            <select name="status" class="form-control select" id="">
                                                <option value="">{{ __('Select Status') }}</option>
                                                @foreach ($statues as $item)
                                                    <option
                                                        {{ isset($_GET['status']) && $_GET['status'] == $item ? 'selected' : '' }}
                                                        value="{{ $item }}">{{ $item }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            <label for="priority" class="form-label">Priority</label>
                                            <select name="priority" id="priority" class="form-control select">
                                                <option value="">Select a priority</option>
                                                @foreach ($priorities as $priority)
                                                    <option value="{{ $priority->id }}"
                                                        @if (isset($_GET['priority']) && $_GET['priority'] == $priority->id) selected @endif>
                                                        {{ $priority->name }}
                                                    </option>
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
                                            onclick="document.getElementById('ticket_index').submit(); return false;"
                                            data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                            data-original-title="{{ __('apply') }}">
                                            <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                        </a>
                                        <a href="{{ route('admin.tickets.index') }}" class="btn btn-sm btn-danger "
                                            data-bs-toggle="tooltip" title="{{ __('Reset') }}"
                                            data-original-title="{{ __('Reset') }}">
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



        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table id="pc-dt-simple" class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ __('Ticket ID') }}</th>
                                    <th class="w-10">{{ __('Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Assign To') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Priority') }}</th>
                                    <th>{{ __('Created By') }}</th>
                                    <th>{{ __('Created') }}</th>
                                    @if (Laratrust::hasPermission('ticket reply') || Laratrust::hasPermission('ticket delete'))
                                        <th class="text-end me-3">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tickets as $ticket)
                                    <tr>
                                        <td class="Id sorting_1">
                                            <a class="btn btn-outline-primary"
                                                href="{{ route('admin.tickets.edit', $ticket->id) }}">
                                                {{ isset($isTicketNumberActive) && $isTicketNumberActive ? Workdo\TicketNumber\Entities\TicketNumber::ticketNumberFormat($ticket->id) : $ticket->ticket_id }}
                                            </a>
                                        </td>
                                        <td><span class="white-space">{{ $ticket->name }}</span></td>
                                        <td>{{ $ticket->email }}</td>
                                        <td> <span class="badge badge-white p-2 px-3 fix_badge"
                                                style="background: {{ isset($ticket->getCategory) ? $ticket->getCategory->color : '' }};">
                                                {{ isset($ticket->getCategory) ? $ticket->getCategory->name : '---' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if (isset($ticket->getAgentDetails))
                                                <span
                                                    class="badge badge-danger bg-primary p-2 px-3 fix_badge">{{ $ticket->getAgentDetails->name }}</span>
                                            @else
                                                <span
                                                    class="badge badge-danger bg-danger p-2 px-3 fix_badge">{{ 'Not Assign' }}</span>
                                            @endif
                                        </td>


                                        <td><span
                                                class="badge fix_badge
                                             @if ($ticket->status == 'New Ticket') bg-secondary
                                             @elseif($ticket->status == 'In Progress')bg-info
                                             @elseif($ticket->status == 'On Hold') bg-warning
                                             @elseif($ticket->status == 'Closed') bg-primary
                                             @else bg-success @endif  p-2 px-3">
                                                {{ __(isset($ticket->status) ? $ticket->status : '---') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge  p-2 px-3  fix_badge"
                                                style="background: {{ isset($ticket->getPriority) ? $ticket->getPriority->color : '' }}">
                                                {{ isset($ticket->getPriority) ? $ticket->getPriority->name : '---' }}
                                            </span>
                                        </td>
                                        <td>{{ isset($ticket->getTicketCreatedBy) ? $ticket->getTicketCreatedBy->name : '---' }}
                                        </td>
                                        <td>{{ $ticket->created_at->diffForHumans() }}</td>
                                        <td class="text-end">
                                            @permission('ticket reply')
                                                <div class="action-btn me-2">
                                                    <a href="{{ route('admin.tickets.edit', $ticket->id) }}"
                                                        class="mx-3 bg-primary btn btn-sm d-inline-flex align-items-center"
                                                        data-bs-toggle="tooltip" title="{{ __('Reply') }}"> <span
                                                            class="text-white"> <i
                                                                class="ti ti-corner-up-left"></i></span></a>
                                                </div>
                                            @endpermission
                                            @permission('ticket delete')
                                                <div class="action-btn">
                                                    <form method="POST"
                                                        action="{{ route('admin.tickets.destroy', $ticket->id) }}"
                                                        id="user-form-{{ $ticket->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input name="_method" type="hidden" value="DELETE">
                                                        <a class="mx-3 bg-danger btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="Delete" aria-label="Delete"
                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                            data-confirm-yes="delete-form-{{ $ticket->id }}"><i
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
