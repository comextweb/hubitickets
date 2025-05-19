@extends('layouts.admin')

@section('page-title')
{{ __('Notification Templates') }}
@endsection

@section('title')
<div class="d-inline-block">
    <h5 class="h4 d-inline-block font-weight-400 mb-0">{{ __('Notification Templates') }}</h5>
</div>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ __('Notification Templates') }}</li>
@endsection

@php
$activeModule = '';
foreach ($notifications as $key => $value) {
$txt = moduleIsActive($key);
if ($txt == true) {
$activeModule = $key;
break;
}
}
@endphp

@section('content')
   
<div class="information-tab-wrp ms-auto my-md-4 mb-4 mt-2">
                    <ul class="nav nav-pills nav-fill cust-nav information-tab p-2" id="pills-tab" role="tablist">
                        @foreach ($notifications as $key => $value)
                            @if (moduleIsActive($key) && $key == 'Slack')
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="slack" data-bs-toggle="pill" data-bs-target="#slack-tab"
                                    type="button">{{ __('Slack') }}</button>
                            </li>
                            @endif
                            @if (moduleIsActive($key) && $key == 'Telegram')
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="telegram" data-bs-toggle="pill"
                                    data-bs-target="#telegram-tab" type="button">{{ __('Telegram') }}</button>
                            </li>
                            @endif
                            @if (moduleIsActive($key) && $key == 'Twilio')
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="twilio" data-bs-toggle="pill"
                                    data-bs-target="#twilio-tab" type="button">{{ __('Twilio') }}</button>
                            </li>
                            @endif
                            @if (moduleIsActive($key) && $key == 'Whatsapp')
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="whatsapp" data-bs-toggle="pill"
                                    data-bs-target="#whatsapp-tab" type="button">{{ __('Whatsapp') }}</button>
                            </li>
                            @endif
                            @if (moduleIsActive($key) && $key == 'WhatsAppAPI')
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="whatsappapi" data-bs-toggle="pill"
                                    data-bs-target="#whatsappapi-tab" type="button">{{ __('WhatsApp API') }}</button>
                            </li>
                            @endif
                            @if (moduleIsActive($key) && $key == 'SMS')
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="sms" data-bs-toggle="pill"
                                    data-bs-target="#sms-tab" type="button">{{ __('SMS') }}</button>
                            </li>
                            @endif
                            @if (moduleIsActive($key) && $key == 'Discord')
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="discord" data-bs-toggle="pill"
                                    data-bs-target="#discord-tab" type="button">{{ __('Discord') }}</button>
                            </li>
                            @endif
                            @if (moduleIsActive($key) && $key == 'Plivo')
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="plivo" data-bs-toggle="pill"
                                    data-bs-target="#plivo-tab" type="button">{{ __('Plivo') }}</button>
                            </li>
                            @endif
                            @if (moduleIsActive($key) && $key == 'RocketChat')
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="rocketchat" data-bs-toggle="pill"
                                    data-bs-target="#rocketchat-tab" type="button">{{ __('RocketChat') }}</button>
                            </li>
                            @endif
                            @if (moduleIsActive($key) && $key == 'Zitasms')
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="zitasms" data-bs-toggle="pill"
                                    data-bs-target="#zitasms-tab" type="button">{{ __('Zitasms') }}</button>
                            </li>
                            @endif
                            @if (moduleIsActive($key) && $key == 'Msg91')
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="msg91" data-bs-toggle="pill"
                                    data-bs-target="#msg91-tab" type="button">{{ __('Msg91') }}</button>
                            </li>
                            @endif
                            @if (moduleIsActive($key) && $key == 'SinchSMS')
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="sinchsms" data-bs-toggle="pill"
                                    data-bs-target="#sinchsms-tab" type="button">{{ __('SinchSMS') }}</button>
                            </li>
                            @endif
                            @if (moduleIsActive($key) && $key == 'BulkSMS')
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="bulksms" data-bs-toggle="pill"
                                    data-bs-target="#bulksms-tab" type="button">{{ __('BulkSMS') }}</button>
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card overflow-auto">
                <div class="card-body">
                    @if($activeModule == '')
                    <div class="text-center">
                        <h5 class="text-danger">{{ __('Make sure to activate at least one notification add-on. A notification template will be visible after that.') }}</h5>
                    </div>
                    @endif
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show " id="slack-tab" role="tabpanel"
                            aria-labelledby="pills-user-tab-1">
                            <table class="table mb-0 pc-dt-simple" id="slack-notify">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Module') }}</th>
                                        @if (Laratrust::hasPermission('notification-template view'))
                                            <th class="text-end">{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($notifications as $key => $notification)
                                    @if (moduleIsActive($key) && $key == 'Slack')
                                    @foreach ($notification as $value)
                                    <tr>
                                        <td>{{ $value->action }}</td>
                                        <td class="text-capitalize">{{ moduleAliasName($value->module) }}
                                        </td>
                                        @permission('notification-template view')
                                            <td class="text-end">
                                                <div class="text-end">
                                                    <div class="action-btn me-2">
                                                        <a href="{{ route('manage.notification.language', [$value->id, getActiveLanguage()]) }}"
                                                            class="mx-3 bg-warning btn btn-sm d-inline-flex align-items-center"
                                                            data-toggle="tooltip" title="{{ __('Manage Your Slack Message') }}">
                                                            <span class="text-white"><i class="ti ti-eye"></i></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        @endpermission
                                    </tr>
                                    @endforeach
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade show " id="telegram-tab" role="tabpanel"
                            aria-labelledby="pills-user-tab-1">
                            <table class="table mb-0 pc-dt-simple" id="telegram-notify">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Module') }}</th>
                                        @if (Laratrust::hasPermission('notification-template view'))
                                            <th class="text-end">{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($notifications as $key => $notification)
                                    @if (moduleIsActive($key) && $key == 'Telegram')
                                    @foreach ($notification as $value)
                                    <tr>
                                        <td>{{ $value->action }}</td>
                                        <td class="text-capitalize">{{ moduleAliasName($value->module) }}
                                        </td>
                                        @permission('notification-template view')
                                            <td class="text-end">
                                                <div class="text-end">
                                                    <div class="action-btn me-2">
                                                        <a href="{{ route('manage.notification.language', [$value->id, getActiveLanguage()]) }}"
                                                            class="mx-3 bg-warning btn btn-sm d-inline-flex align-items-center"
                                                            data-toggle="tooltip" title="{{ __('Manage Your Telegram Message') }}">
                                                            <span class="text-white"><i class="ti ti-eye"></i></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        @endpermission
                                    </tr>
                                    @endforeach
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade show " id="twilio-tab" role="tabpanel"
                            aria-labelledby="pills-user-tab-1">
                            <table class="table mb-0 pc-dt-simple" id="twilio-notify">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Module') }}</th>
                                        @if (Laratrust::hasPermission('notification-template view'))
                                            <th class="text-end">{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($notifications as $key => $notification)
                                    @if (moduleIsActive($key) && $key == 'Twilio')
                                    @foreach ($notification as $value)
                                    <tr>
                                        <td>{{ $value->action }}</td>
                                        <td class="text-capitalize">{{ moduleAliasName($value->module) }}
                                        </td>
                                        @permission('notification-template view')                                    
                                            <td class="text-end">
                                                <div class="text-end">
                                                    <div class="action-btn me-2">
                                                        <a href="{{ route('manage.notification.language', [$value->id, getActiveLanguage()]) }}"
                                                            class="mx-3 bg-warning btn btn-sm d-inline-flex align-items-center"
                                                            data-toggle="tooltip" title="{{ __('Manage Your Twilio Message') }}">
                                                            <span class="text-white"><i class="ti ti-eye"></i></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        @endpermission
                                    </tr>
                                    @endforeach
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade show " id="whatsapp-tab" role="tabpanel"
                            aria-labelledby="pills-user-tab-1">
                            <table class="table mb-0 pc-dt-simple" id="whatsapp-notify">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Module') }}</th>
                                        @if (Laratrust::hasPermission('notification-template view'))
                                            <th class="text-end">{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($notifications as $key => $notification)
                                    @if (moduleIsActive($key) && $key == 'Whatsapp')
                                    @foreach ($notification as $value)
                                    <tr>
                                        <td>{{ $value->action }}</td>
                                        <td class="text-capitalize">{{ moduleAliasName($value->module) }}
                                        </td>
                                        @permission('notification-template view')                                    
                                            <td class="text-end">
                                                <div class="text-end">
                                                    <div class="action-btn me-2">
                                                        <a href="{{ route('manage.notification.language', [$value->id, getActiveLanguage()]) }}"
                                                            class="mx-3 bg-warning btn btn-sm d-inline-flex align-items-center"
                                                            data-toggle="tooltip" title="{{ __('Manage Your Whatsapp Message') }}">
                                                            <span class="text-white"><i class="ti ti-eye"></i></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        @endpermission
                                    </tr>
                                    @endforeach
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade show " id="whatsappapi-tab" role="tabpanel"
                            aria-labelledby="pills-user-tab-1">
                            <table class="table mb-0 pc-dt-simple" id="whatsappapi-notify">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Module') }}</th>
                                        @if (Laratrust::hasPermission('notification-template view'))
                                            <th class="text-end">{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($notifications as $key => $notification)
                                    @if (moduleIsActive($key) && $key == 'WhatsAppAPI')
                                    @foreach ($notification as $value)
                                    <tr>
                                        <td>{{ $value->action }}</td>
                                        <td class="text-capitalize">{{ moduleAliasName($value->module) }}
                                        </td>
                                        @permission('notification-template view')                                    
                                            <td class="text-end">
                                                <div class="text-end">
                                                    <div class="action-btn me-2">
                                                        <a href="{{ route('manage.notification.language', [$value->id, getActiveLanguage()]) }}"
                                                            class="mx-3 bg-warning btn btn-sm d-inline-flex align-items-center"
                                                            data-toggle="tooltip" title="{{ __('Manage Your WhatsApp API Message') }}">
                                                            <span class="text-white"><i class="ti ti-eye"></i></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        @endpermission
                                    </tr>
                                    @endforeach
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade show " id="sms-tab" role="tabpanel"
                            aria-labelledby="pills-user-tab-1">
                            <table class="table mb-0 pc-dt-simple" id="sms-notify">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Module') }}</th>
                                        @if (Laratrust::hasPermission('notification-template view'))
                                            <th class="text-end">{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($notifications as $key => $notification)
                                    @if (moduleIsActive($key) && $key == 'SMS')
                                    @foreach ($notification as $value)
                                    <tr>
                                        <td>{{ $value->action }}</td>
                                        <td class="text-capitalize">{{ moduleAliasName($value->module) }}
                                        </td>
                                        @permission('notification-template view')                                    
                                            <td class="text-end">
                                                <div class="text-end">
                                                    <div class="action-btn me-2">
                                                        <a href="{{ route('manage.notification.language', [$value->id, getActiveLanguage()]) }}"
                                                            class="mx-3 bg-warning btn btn-sm d-inline-flex align-items-center"
                                                            data-toggle="tooltip" title="{{ __('Manage Your SMS Message') }}">
                                                            <span class="text-white"><i class="ti ti-eye"></i></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        @endpermission
                                    </tr>
                                    @endforeach
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade show " id="discord-tab" role="tabpanel"
                            aria-labelledby="pills-user-tab-1">
                            <table class="table mb-0 pc-dt-simple" id="discord-notify">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Module') }}</th>
                                        @if (Laratrust::hasPermission('notification-template view'))
                                            <th class="text-end">{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($notifications as $key => $notification)
                                    @if (moduleIsActive($key) && $key == 'Discord')
                                    @foreach ($notification as $value)
                                    <tr>
                                        <td>{{ $value->action }}</td>
                                        <td class="text-capitalize">{{ moduleAliasName($value->module) }}
                                        </td>
                                        @permission('notification-template view')                                    
                                            <td class="text-end">
                                                <div class="text-end">
                                                    <div class="action-btn me-2">
                                                        <a href="{{ route('manage.notification.language', [$value->id, getActiveLanguage()]) }}"
                                                            class="mx-3 bg-warning btn btn-sm d-inline-flex align-items-center"
                                                            data-toggle="tooltip" title="{{ __('Manage Your Discord Message') }}">
                                                            <span class="text-white"><i class="ti ti-eye"></i></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        @endpermission
                                    </tr>
                                    @endforeach
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade show " id="plivo-tab" role="tabpanel"
                            aria-labelledby="pills-user-tab-1">
                            <table class="table mb-0 pc-dt-simple" id="plivo-notify">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Module') }}</th>
                                        @if (Laratrust::hasPermission('notification-template view'))
                                            <th class="text-end">{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($notifications as $key => $notification)
                                    @if (moduleIsActive($key) && $key == 'Plivo')
                                    @foreach ($notification as $value)
                                    <tr>
                                        <td>{{ $value->action }}</td>
                                        <td class="text-capitalize">{{ moduleAliasName($value->module) }}
                                        </td>
                                        @permission('notification-template view')                                    
                                            <td class="text-end">
                                                <div class="text-end">
                                                    <div class="action-btn me-2">
                                                        <a href="{{ route('manage.notification.language', [$value->id, getActiveLanguage()]) }}"
                                                            class="mx-3 bg-warning btn btn-sm d-inline-flex align-items-center"
                                                            data-toggle="tooltip" title="{{ __('Manage Your Plivo Message') }}">
                                                            <span class="text-white"><i class="ti ti-eye"></i></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        @endpermission
                                    </tr>
                                    @endforeach
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade show " id="rocketchat-tab" role="tabpanel"
                            aria-labelledby="pills-user-tab-1">
                            <table class="table mb-0 pc-dt-simple" id="rocketchat-notify">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Module') }}</th>
                                        @if (Laratrust::hasPermission('notification-template view'))
                                            <th class="text-end">{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($notifications as $key => $notification)
                                    @if (moduleIsActive($key) && $key == 'RocketChat')
                                    @foreach ($notification as $value)
                                    <tr>
                                        <td>{{ $value->action }}</td>
                                        <td class="text-capitalize">{{ moduleAliasName($value->module) }}
                                        </td>
                                        @permission('notification-template view')                                    
                                            <td class="text-end">
                                                <div class="text-end">
                                                    <div class="action-btn me-2">
                                                        <a href="{{ route('manage.notification.language', [$value->id, getActiveLanguage()]) }}"
                                                            class="mx-3 bg-warning btn btn-sm d-inline-flex align-items-center"
                                                            data-toggle="tooltip" title="{{ __('Manage Your RocketChat Message') }}">
                                                            <span class="text-white"><i class="ti ti-eye"></i></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        @endpermission
                                    </tr>
                                    @endforeach
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade show " id="zitasms-tab" role="tabpanel"
                            aria-labelledby="pills-user-tab-1">
                            <table class="table mb-0 pc-dt-simple" id="zitasms-notify">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Module') }}</th>
                                        @if (Laratrust::hasPermission('notification-template view'))
                                            <th class="text-end">{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($notifications as $key => $notification)
                                    @if (moduleIsActive($key) && $key == 'Zitasms')
                                    @foreach ($notification as $value)
                                    <tr>
                                        <td>{{ $value->action }}</td>
                                        <td class="text-capitalize">{{ moduleAliasName($value->module) }}
                                        </td>
                                        @permission('notification-template view')                                    
                                            <td class="text-end">
                                                <div class="text-end">
                                                    <div class="action-btn me-2">
                                                        <a href="{{ route('manage.notification.language', [$value->id, getActiveLanguage()]) }}"
                                                            class="mx-3 bg-warning btn btn-sm d-inline-flex align-items-center"
                                                            data-toggle="tooltip" title="{{ __('Manage Your Zitasms Message') }}">
                                                            <span class="text-white"><i class="ti ti-eye"></i></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        @endpermission
                                    </tr>
                                    @endforeach
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade show " id="msg91-tab" role="tabpanel"
                            aria-labelledby="pills-user-tab-1">
                            <table class="table mb-0 pc-dt-simple" id="msg91-notify">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Module') }}</th>
                                        @if (Laratrust::hasPermission('notification-template view'))
                                            <th class="text-end">{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($notifications as $key => $notification)
                                    @if (moduleIsActive($key) && $key == 'Msg91')
                                    @foreach ($notification as $value)
                                    <tr>
                                        <td>{{ $value->action }}</td>
                                        <td class="text-capitalize">{{ moduleAliasName($value->module) }}
                                        </td>
                                        @permission('notification-template view')                                    
                                            <td class="text-end">
                                                <div class="text-end">
                                                    <div class="action-btn me-2">
                                                        <a href="{{ route('manage.notification.language', [$value->id, getActiveLanguage()]) }}"
                                                            class="mx-3 bg-warning btn btn-sm d-inline-flex align-items-center"
                                                            data-toggle="tooltip" title="{{ __('Manage Your Msg91 Message') }}">
                                                            <span class="text-white"><i class="ti ti-eye"></i></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        @endpermission
                                    </tr>
                                    @endforeach
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade show " id="sinchsms-tab" role="tabpanel"
                            aria-labelledby="pills-user-tab-1">
                            <table class="table mb-0 pc-dt-simple" id="sinchsms-notify">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Module') }}</th>
                                        @if (Laratrust::hasPermission('notification-template view'))
                                            <th class="text-end">{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($notifications as $key => $notification)
                                    @if (moduleIsActive($key) && $key == 'SinchSMS')
                                    @foreach ($notification as $value)
                                    <tr>
                                        <td>{{ $value->action }}</td>
                                        <td class="text-capitalize">{{ moduleAliasName($value->module) }}
                                        </td>
                                        @permission('notification-template view')                                    
                                            <td class="text-end">
                                                <div class="text-end">
                                                    <div class="action-btn me-2">
                                                        <a href="{{ route('manage.notification.language', [$value->id, getActiveLanguage()]) }}"
                                                            class="mx-3 bg-warning btn btn-sm d-inline-flex align-items-center"
                                                            data-toggle="tooltip" title="{{ __('Manage Your SinchSMs Message') }}">
                                                            <span class="text-white"><i class="ti ti-eye"></i></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        @endpermission
                                    </tr>
                                    @endforeach
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade show " id="bulksms-tab" role="tabpanel"
                            aria-labelledby="pills-user-tab-1">
                            <table class="table mb-0 pc-dt-simple" id="bulksms-notify">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Module') }}</th>
                                        @if (Laratrust::hasPermission('notification-template view'))
                                            <th class="text-end">{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($notifications as $key => $notification)
                                    @if (moduleIsActive($key) && $key == 'BulkSMS')
                                    @foreach ($notification as $value)
                                    <tr>
                                        <td>{{ $value->action }}</td>
                                        <td class="text-capitalize">{{ moduleAliasName($value->module) }}
                                        </td>
                                        @permission('notification-template view')                                    
                                            <td class="text-end">
                                                <div class="text-end">
                                                    <div class="action-btn me-2">
                                                        <a href="{{ route('manage.notification.language', [$value->id, getActiveLanguage()]) }}"
                                                            class="mx-3 bg-warning btn btn-sm d-inline-flex align-items-center"
                                                            data-toggle="tooltip" title="{{ __('Manage Your BulkSMS Message') }}">
                                                            <span class="text-white"><i class="ti ti-eye"></i></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        @endpermission
                                    </tr>
                                    @endforeach
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var moduleName = '{{ $activeModule }}';
        if (moduleName == 'Slack') {
            $('#slack').addClass('active');
            $('#slack-tab').addClass('active');
        } else if (moduleName == 'Telegram') {
            $('#telegram').addClass('active');
            $('#telegram-tab').addClass('active');
        } else if (moduleName == 'Twilio') {
            $('#twilio').addClass('active');
            $('#twilio-tab').addClass('active');
        } else if (moduleName == 'Whatsapp') {
            $('#whatsapp').addClass('active');
            $('#whatsapp-tab').addClass('active');
        } else if (moduleName == 'WhatsAppAPI') {
            $('#whatsappapi').addClass('active');
            $('#whatsappapi-tab').addClass('active');
        } else if (moduleName == 'SMS') {
            $('#sms').addClass('active');
            $('#sms-tab').addClass('active');
        } else if (moduleName == 'Discord') {
            $('#discord').addClass('active');
            $('#discord-tab').addClass('active');
        } else if (moduleName == 'Plivo') {
            $('#plivo').addClass('active');
            $('#plivo-tab').addClass('active');
        } else if (moduleName == 'RocketChat') {
            $('#rocketchat').addClass('active');
            $('#rocketchat-tab').addClass('active');
        } else if (moduleName == 'Zitasms') {
            $('#zitasms').addClass('active');
            $('#zitasms-tab').addClass('active');
        } else if (moduleName == 'Msg91') {
            $('#msg91').addClass('active');
            $('#msg91-tab').addClass('active');
        } else if (moduleName == 'SinchSMS') {
            $('#sinchsms').addClass('active');
            $('#sinchsms-tab').addClass('active');
        } else if (moduleName == 'BulkSMS') {
            $('#bulksms').addClass('active');
            $('#bulksms-tab').addClass('active');
        }
    });
</script>
@endpush