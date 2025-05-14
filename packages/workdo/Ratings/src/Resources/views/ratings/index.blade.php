@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Ticket Ratings') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Ticket Ratings') }}</li>
@endsection

@push('css-page')
<link rel="stylesheet" href="{{ asset('packages/workdo/Ratings/src/Resources/assets/css/custom.css') }}">
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table id="pc-dt-simple" class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ __('Ticket ID') }}</th>
                                    <th>{{ __('Rating Date') }}</th>
                                    <th>{{ __('Customer Name') }}</th>
                                    <th>{{ __('Customer Email') }}</th>
                                    <th>{{ __('Assign To') }}</th>
                                    <th>{{ __('Ratings') }}</th>
                                    @if (Laratrust::hasPermission('ratings edit') || Laratrust::hasPermission('ratings delete'))
                                        <th class="text-end me-3">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ticketRatings as $rating)
                                    <tr>
                                        <td class="Id sorting_1">
                                            <span class="btn btn-outline-primary rating-btn">
                                                {{ moduleIsActive('TicketNumber') ? Workdo\TicketNumber\Entities\TicketNumber::ticketNumberFormat(isset($rating->getTicketDetails) ? $rating->getTicketDetails->id : '-') : (isset($rating->getTicketDetails) ? $rating->getTicketDetails->ticket_id : '-') }}
                                            </span>
                                        </td>
                                        <td>{{ $rating->rating_date }}</td>
                                        <td>{{ $rating->customer }}</td>
                                        <td>{{ $rating->getTicketDetails->email }}</td>
                                        <td>{{ isset($rating->getAgentDetails) ? $rating->getAgentDetails->name : '-' }}</td>
                                        <td>                
                                        <div class="rating-stars admin element">
                                            <ul id='stars'>
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <li class='star {{ $rating->rating >= $i ? 'selected' : '' }}'
                                                        title='{{ $i }}' data-value='{{ $i }}'>
                                                        <i class='fa fa-star fa-fw'></i>
                                                    </li>
                                                @endfor
                                            </ul>
                                            <div class="popover">
                                            {{ $rating->description ?? 'Review Not Found' }}
                                        </div>
                                        </div>
                                       
                                        </td>
                                        <td class="text-end">
                                            @permission('ratings edit')
                                                <div class="action-btn me-2">
                                                    <a href="#" class="btn btn-sm btn-info btn-icon" title="{{ __('Edit') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-ajax-popup="true" data-title="{{ __('Edit Rating') }}"
                                                    data-url="{{ route('rating.edit', $rating->id) }}" data-size="md"><i class="ti ti-pencil"></i></a>
                                                </div>
                                            @endpermission
                                            @permission('ratings delete')
                                                <div class="action-btn">
                                                    <form method="POST"
                                                        action="{{ route('rating.destroy', $rating->id) }}"
                                                        id="user-form-{{ $rating->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input name="_method" type="hidden" value="DELETE">
                                                        <a class="mx-3 bg-danger btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="Delete" aria-label="Delete"
                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                            data-confirm-yes="delete-form-{{ $rating->id }}"><i
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
