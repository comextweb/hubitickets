@extends('layouts.auth')

@section('page-title')
{{ __('Ticket Number') }} -
{{ isset($isTicketNumberActive) && $isTicketNumberActive ? Workdo\TicketNumber\Entities\TicketNumber::ticketNumberFormat($ticket['id']) : $ticket['ticket_id'] }}
@endsection

@push('css-page')
<link rel="stylesheet" href="{{asset('css/summernote/summernote-bs4.css')}}">
@endpush

@section('style')
<style>
@media (max-width: 767px) {
    .auth-layout-wrap .auth-content {
        min-width: 100%;
    }
}

@media (min-width: 768px) {
    .auth-layout-wrap .auth-content {
        min-width: 90%;
    }
}

@media (min-width: 1024px) {
    .auth-layout-wrap .auth-content {
        min-width: 50%;
    }
}
</style>
@endsection

@push('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{asset('css/summernote/summernote-bs4.js')}}"></script>

<script src="{{ asset('public/custom/libs/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
<script>
function show_toastr(title, message, type) {
    var o, i;
    var icon = '';
    var cls = '';
    if (type == 'success') {
        icon = 'fas fa-check-circle';
        // cls = 'success';
        cls = 'primary';
    } else {
        icon = 'fas fa-times-circle';
        cls = 'danger';
    }
    $.notify({
        icon: icon,
        title: " " + title,
        message: message,
        url: ""
    }, {
        element: "body",
        type: cls,
        allow_dismiss: !0,
        placement: {
            from: 'top',
            align: 'right'
        },
        offset: {
            x: 15,
            y: 15
        },
        spacing: 10,
        z_index: 1080,
        delay: 2500,
        timer: 2000,
        url_target: "_blank",
        mouse_over: !1,
        animate: {
            enter: o,
            exit: i
        },
        // danger
        template: '<div class="toast text-white bg-' + cls +
            ' fade show" role="alert" aria-live="assertive" aria-atomic="true">' +
            '<div class="d-flex">' +
            '<div class="toast-body"> ' + message + ' </div>' +
            '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>' +
            '</div>' +
            '</div>'
        // template: '<div class="alert alert-{0} alert-icon alert-group alert-notify" data-notify="container" role="alert"><div class="alert-group-prepend alert-content"><span class="alert-group-icon"><i data-notify="icon"></i></span></div><div class="alert-content"><strong data-notify="title">{1}</strong><div data-notify="message">{2}</div></div><button type="button" class="close" data-notify="dismiss" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'
    });
}
</script>


@endpush

@section('content')
<div class="auth-wrapper auth-v1">
    <div class="auth-content ticket-auth-content">

        <div class="bg-primary ticket-auth-head">
            <h5 class="text-white mb-0">{{ __('Ticket') }} -
                {{ isset($isTicketNumberActive) && $isTicketNumberActive ? Workdo\TicketNumber\Entities\TicketNumber::ticketNumberFormat($ticket['id']) : $ticket['ticket_id'] }}
            </h5>
        </div>

        <div class="card ticket-auth-card p-md-4 p-3">
            @csrf
            <div class="card mb-3">
                <div class="card-header p-3 p-3">
                    <h5 class="mb-0">{{$ticket->name}} <small>({{$ticket->created_at->diffForHumans()}})</small>
                    </h5>
                </div>
                <div class="card-body p-3">
                    <div>
                        <p class="mb-0">{!! $ticket->description !!}</p>
                    </div>
                    @php
                    $attachments=json_decode($ticket->attachments);
                    @endphp
                    @if(!is_null($attachments) && count($attachments)>0)
                    <div class="ticket-attachments-wrp">
                        <b class="mb-2 d-block">{{ __('Attachments') }} :</b>
                        <ul class="list-group list-group-flush">
                            @foreach($attachments as $index => $attachment)
                            <li class="list-group-item p-0">
                                {{basename($attachment)}}
                                <a download=""
                                    href="{{(!empty($attachment) && checkFile($attachment) ? getFile($attachment) : getFile('uploads/default-images/image_not_available.png'))}}"
                                    class="edit-icon py-1 ml-2" title="{{ __('Download') }}"><i
                                        class="fa fa-download ms-2"></i></a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
            <div class="conversion-container">
                @foreach($ticket->conversions as $conversion)
                <div class="card mb-3">
                    <div class="card-header p-3">
                        <h5 class="mb-0">{{$conversion->replyBy()->name}}
                            <small>({{$conversion->created_at->diffForHumans()}})</small>
                        </h5>
                    </div>
                    <div class="card-body w-100 p-3">
                        <div>{!! $conversion->description !!}</div>
                        @php
                        $attachments=json_decode($conversion->attachments);
                        @endphp
                        @if(isset($attachments))
                        <div class="m-1">
                            <b>{{ __('Attachments') }} :</b>
                            <ul class="list-group list-group-flush">

                                @foreach($attachments as $index => $attachment)
                                <li class="list-group-item px-0">
                                    {{basename($attachment)}}
                                    <a download=""
                                        href="{{(!empty($attachment) && checkFile($attachment) ? getFile($attachment) : getFile('uploads/default-images/image_not_available.png'))}}"
                                        class="edit-icon py-1 ml-2" title="{{ __('Download') }}"><i
                                            class="fa fa-download ms-2"></i></a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            @if($ticket->status != 'Closed')
            <div class="card mb-0">
                <div class="card-header p-3">
                    <h5 class="mb-0">{{ __('Description') }}</h5>
                </div>
                <div class="card-body w-100 p-3">
                    <form method="post" action="{{route('home.reply',encrypt($ticket->ticket_id))}}"
                        enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-12">
                                <textarea name="reply_description"
                                    class="form-control summernote-simple {{ $errors->has('reply_description') ? ' is-invalid' : '' }}">{{old('reply_description')}}</textarea>
                                <p class="text-danger summernote_text"></p>
                                <div class="invalid-feedback">
                                    {{ $errors->first('reply_description') }}
                                </div>
                            </div>
                            <div class="form-group col-md-12 file-group mb-0">
                                {{-- <label class="require form-label">{{ __('Attachments') }}</label>
                                <label
                                    class="form-label"><small>({{__('You can select multiple files')}})</small></label>
                                --}}
                                {{-- <div class="choose-file form-group">
                                                <label for="file" class="form-label">
                                                    <div>{{ __('Choose File Here') }}
                            </div>
                            <input type="file"
                                class="form-control {{ $errors->has('reply_attachments') ? 'is-invalid' : '' }}"
                                multiple="" name="reply_attachments[]" id="file"
                                data-filename="multiple_reply_file_selection">
                            <div class="invalid-feedback">
                                {{ $errors->first('reply_attachments') }}
                            </div>
                            </label>
                            <p class="multiple_reply_file_selection"></p>
                        </div> --}}
                        <label class="form-label form-bottom-content mb-3">{{ __('Attachments') }}
                            <b>({{ __('You can select multiple files') }})</b></label>
                        <div class="choose-file form-group mb-0">
                            <label for="file" class="form-label">
                                <div class="mb-2">{{ __('Choose File Here') }}</div>
                                <div class="file-upload">
                                    <div class="file-select">
                                        <div class="file-select-button btn btn-primary btn-block" id="fileName">
                                            Choose File
                                        </div>
                                        <div class="file-select-name" id="noFile">No file chosen...</div>
                                        <input type="file"
                                            class="form-control {{ $errors->has('reply_attachments.') ? 'is-invalid' : '' }}"
                                            multiple="" name="reply_attachments[]" id="chooseFile"
                                            data-filename="multiple_file_selection">
                                    </div>
                                </div>
                            </label>
                            <p class="multiple_file_selection"></p>
                        </div>
                </div>
            </div>

            <div class="form-group col-md-12 mb-0">
                <div class="text-center">
                    <input type="hidden" name="status" value="New Ticket" />
                    <button
                        class="btn ticket-auth-btn btn-submit btn-primary btn-block mt-2">{{ __('Submit') }}</button>
                </div>
            </div>
            </form>
        </div>
    </div>
    @else
    <div class="card">
        <div class="card-body">
            <p class="text-blue font-weight-bold text-center mb-0">{{ __('Ticket is closed you cannot replay.') }}
            </p>
        </div>
    </div>
    @endif

</div>
</div>

</div>
</div>

<div class="position-fixed top-0 end-0 p-3" style="z-index: 99999">
    <div id="liveToast" class="toast text-white fade" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>
</div>

@endsection


@push('scripts')
<script>
// for Choose file
$(document).on('change', 'input[type=file]', function() {
    var names = '';
    var files = $('input[type=file]')[0].files;

    for (var i = 0; i < files.length; i++) {
        names += files[i].name + '<br>';
    }
    $('.' + $(this).attr('data-filename')).html(names);
});
</script>
@if (isset($settings['CHAT_MODULE']) && $settings['CHAT_MODULE'] == 'yes')
<script>
Pusher.logToConsole = false;

var pusher = new Pusher('{{ isset($settings['PUSHER_APP_KEY']) && $settings['PUSHER_APP_KEY'] ? $settings['PUSHER_APP_KEY'] : '' }}', {
    cluster: '{{ isset($settings['PUSHER_APP_CLUSTER']) && $settings['PUSHER_APP_CLUSTER'] ? $settings['PUSHER_APP_CLUSTER'] : '' }}',
    forceTLS: true
});



var ticket_id = ("{{ isset($ticket) ? $ticket['ticket_id']  : ''}}");

// Subscribe to the Pusher channel after getting the ticket reply
var channel = pusher.subscribe('ticket-reply-send-' + ticket_id);

channel.bind('ticket-reply-send-event-' + ticket_id, function(data) {

    if (ticket_id == data.ticket_number) {

        const messageList = $('.conversion-container');

        var newMessage = `
                        <div class="card mb-3">
                                <div class="card-header p-3"><h5 class="mb-0">${data.sender_name} <small>(${data.timestamp})</small></h5></div>
                                <div class="card-body w-100 p-3">
                                    <div>${data.new_message}</div>
                                    ${data.attachments ? `
                                            <div  class="m-1" >
                                                    <h6>{{ __('Attachments') }} : </h6>
                                                        <ul class="list-group list-group-flush">
                                                                ${data.attachments.map(function(attachment) {
                                                                    var filename = attachment.split('/').pop(); // Extract filename
                                                                    var fullUrl = data.baseUrl + attachment;
                                                                    return `
                                                                        <li class="list-group-item px-0">
                                                                            ${filename}
                                                                            <a download href="${fullUrl}" class="edit-icon py-1 ml-2" title="Download">
                                                                                <i class="fa fa-download ms-2"></i>
                                                                            </a>
                                                                        </li>
                                                                                `;
                                                                }).join('')}
                                                        </ul>
                                            </div>
                                    ` : ''}
                                </div>
                        </div>
                    
                    `;

        messageList.append(newMessage);
        $('.card ticket-auth-card').scrollTop($(
            '.card ticket-auth-card')[0].scrollHeight);

        $.ajax({
            url: "{{ url('/admin/readmessge') }}" + '/' + data.ticket_id,
            type: 'GET',
            cache: false,
            success: function(data) {
                if (data.status == 'error') {
                    show_toastr('Error', data.message, 'error');
                }
            }
        });
    }

});
</script>
@endif
@endpush