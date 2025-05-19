@extends('layouts.auth')

@section('page-title')
    {{ __('Ticket Number') }} -
    {{ isset($isTicketNumberActive) && $isTicketNumberActive ? Workdo\TicketNumber\Entities\TicketNumber::ticketNumberFormat($ticket['id']) : $ticket['ticket_id'] }}
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{asset('css/summernote/summernote-bs4.css')}}">
@endpush
@section('content')
    <div class="auth-wrapper auth-v1">
        <div class="auth-content ticket-auth-content">
            <div class="card ticket-auth-card">
                @csrf
                <div class="card-header p-md-4 p-3 mb-4">
                    <h2 class="h3 mb-3">{{ __('Ticket') }} -
                        {{ isset($isTicketNumberActive) && $isTicketNumberActive ? Workdo\TicketNumber\Entities\TicketNumber::ticketNumberFormat($ticket['id']) : $ticket['ticket_id'] }}
                    </h2>
                    <div class="card-header-bottom d-flex align-items-center gap-2 flex-wrap justify-content-between">
                        <ul class="d-flex align-items-center gap-3 list-unstyled mb-0 flex-wrap">
                            <li class="d-flex">
                                <span class="status-badge">{{$ticket->status}}</span>
                            </li>
                            @php
                                $priorityColor = isset($ticket->getPriority) ? $ticket->getPriority->color : '---';
                                $priority = isset($ticket->getPriority) ? $ticket->getPriority->name : '---';
                            @endphp
                            <li class="d-flex">
                                <span class="priority-badge"
                                    style="background-color: {{ $priorityColor }};">{{$priority}}</span>
                            </li>
                            <li class="d-flex align-items-center gap-1">
                                <i class="ti ti-clock fs-5"></i> {{ $ticket->created_at->diffForHumans() }}
                            </li>
                        </ul>
                        <div class="ticket-category d-flex gap-2 align-items-center">
                            <span><svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12.5764 0.804446C12.0438 0.273273 11.317 -0.0169845 10.5648 0.00103587L2.87181 0.197115C1.4098 0.233585 0.233109 1.41006 0.196639 2.87229L0.000774043 10.5651C-0.0170319 11.3172 0.273011 12.044 0.80397 12.5769L11.4236 23.1961C12.4966 24.2668 14.2337 24.2668 15.3068 23.1961L23.1956 15.3072C24.268 14.235 24.268 12.4963 23.1956 11.4238L12.5764 0.804446ZM22.419 14.5306L14.5302 22.4193C13.8864 23.0618 12.844 23.0618 12.2002 22.4193L1.58078 11.8001C1.2622 11.4803 1.08801 11.0443 1.09873 10.593L1.2946 2.90018C1.31669 2.02297 2.02249 1.31696 2.8997 1.29507L10.5925 1.09899C10.6064 1.09878 10.6199 1.09856 10.6339 1.09856C11.0709 1.09921 11.4899 1.27276 11.7994 1.58125L22.419 12.2006C23.0613 12.8444 23.0613 13.8868 22.419 14.5306Z"
                                        fill="black" />
                                    <path
                                        d="M7.17895 4.53503C5.71865 4.53503 4.53488 5.7188 4.53467 7.1791C4.53467 8.6394 5.71865 9.82317 7.17873 9.82317C8.63903 9.82317 9.8228 8.6394 9.8228 7.1791C9.82108 5.71966 8.63839 4.53675 7.17895 4.53503ZM7.17895 8.72478C6.32512 8.72478 5.63327 8.03271 5.63306 7.1791C5.63306 6.32549 6.32512 5.63342 7.17873 5.63342C8.03234 5.63342 8.72441 6.32549 8.72441 7.1791C8.72356 8.03228 8.03213 8.72371 7.17895 8.72478Z"
                                        fill="black" />
                                </svg>
                            </span>
                            <span
                                class="fs-5 f-w-600">{{isset($ticket->getCategory) ? $ticket->getCategory->name : '---'}}</span>
                        </div>
                    </div>
                </div>
                <div class="ticket-auth-card-inner px-md-4 px-3">
                <div class="card mb-3 message-card">
                    <div class="card-header d-flex align-items-center gap-3">
                        <div class="user-img">
                            <img src="{{ $ticket->name }}" alt="{{ $ticket->name }}" loading="lazy"
                                avatar="{{ $ticket->name }}">
                        </div>
                        <div class="user-info">
                            <h3 class="mb-1 fs-5">{{$ticket->name}}</h3>
                            <span>({{$ticket->created_at->diffForHumans()}})</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div>
                            <p class="mb-0">{!! $ticket->description !!}</p>
                        </div>
                        @php
                            $attachments = json_decode($ticket->attachments);
                        @endphp
                        @if(!is_null($attachments) && count($attachments) > 0)
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
                        <div class="card message-card mb-0">
                            <div class="card-header d-flex align-items-center gap-3">
                                <div class="user-img">
                                    <img src="{{ $conversion->replyBy()->name }}" alt="{{ $conversion->replyBy()->name }}"
                                        loading="lazy" avatar="{{ $conversion->replyBy()->name }}">
                                </div>
                                <div class="user-info">
                                    <h3 class="mb-2 fs-5 d-flex gap-1">{{$conversion->replyBy()->name}}
                                        @if ($conversion->sender != 'user')
                                            <span class="staff-badge">{{$conversion->replyBy()->type}}</span>
                                        @endif
                                    </h3>
                                    <span>({{$conversion->created_at->diffForHumans()}})</span>
                                </div>
                            </div>
                            <div class="card-body w-100">
                                <div>{!! $conversion->description !!}</div>
                                @php
                                    $attachments = json_decode($conversion->attachments);
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
            </div>

                @if($ticket->status != 'Closed')
                <div class="reply-wrp p-md-4 p-3 pt-0">
                    <h3 class="mb-3 f-w-400 h5">{{ __('reply to this ticket') }}</h3>
                    <div class="card mb-0 message-card overflow-hidden">
                        <div class="card-body p-0">
                            <form method="post" action="{{route('home.reply', encrypt($ticket->ticket_id))}}"
                                enctype="multipart/form-data" class="needs-validation" novalidate>
                                @csrf
                                <div class="form-group">
                                    <textarea required name="reply_description"
                                        class="form-control summernote-simple {{ $errors->has('reply_description') ? ' is-invalid' : '' }}">{{old('reply_description')}}</textarea>
                                    <p class="text-danger summernote_text"></p>
                                    <div class="invalid-feedback">
                                        {{ $errors->first('reply_description') }}
                                    </div>
                                </div>
                                <div class="form-footer p-3 d-flex gap-2 flex-wrap justify-content-between">
                                    <div class="form-group mb-0">
                                        <label class="file-upload">
                                            <input class="{{ $errors->has('reply_attachments.') ? 'is-invalid' : '' }}"
                                                type="file" id="fileInput" multiple name="reply_attachments[]"
                                                id="chooseFile" />
                                            <span>
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M22.7674 8.73571L10.5702 20.7974C8.54925 22.7962 5.27228 22.7962 3.25129 20.7974C1.2303 18.7987 1.2303 15.5586 3.25129 13.5599L14.2293 2.70444C15.5769 1.37222 17.7607 1.37222 19.1083 2.70444C20.456 4.03662 20.456 6.19729 19.1083 7.52947L8.1303 18.3849C7.45692 19.0515 6.3646 19.0515 5.69036 18.3849C5.01699 17.7193 5.01699 16.639 5.69036 15.9724L15.4484 6.32319L14.2284 5.11695L4.47128 14.767C3.12366 16.0992 3.12366 18.2599 4.47128 19.592C5.81889 20.9243 8.00268 20.9243 9.35029 19.592L20.3283 8.73657C22.3493 6.73787 22.3493 3.49776 20.3283 1.49902C18.3073 -0.499674 15.0304 -0.499674 13.0094 1.49902L1.42135 12.9576L1.46334 12.9996C-0.641633 15.6759 -0.456583 19.5449 2.03134 22.0045C4.51926 24.4642 8.43016 24.6484 11.1374 22.5657L11.1794 22.6077L23.9865 9.94281L22.7674 8.73571Z"
                                                        fill="black" />
                                                </svg>
                                            </span>{{ __('Attach Files') }}
                                        </label>
                                        <div class="file-names" id="fileNames"></div>
                                    </div>

                                    <div class="text-center">
                                        <input type="hidden" name="status" value="New Ticket" />
                                        <button
                                            class="btn ticket-auth-btn btn-submit btn-primary btn-block">{{ __('Submit') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                    <div class="card">
                        <div class="card-body">
                            <p class="text-blue font-weight-bold text-center mb-0">
                                {{ __('Ticket is closed you cannot replay.') }}
                            </p>
                        </div>
                    </div>
                @endif
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
        $(document).ready(function (){
            const ticketAuthCard = $('.ticket-auth-card-inner');
            ticketAuthCard.scrollTop(ticketAuthCard[0].scrollHeight);
            window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
        });
    </script>
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
        <script src="{{ asset('js/letter.avatar.js') }}"></script>
        <script>
            // for Choose file
            $(document).on('change', 'input[type=file]', function () {
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
    
            const pusher = new Pusher('{{ $settings['PUSHER_APP_KEY'] ?? '' }}', {
                cluster: '{{ $settings['PUSHER_APP_CLUSTER'] ?? '' }}',
                forceTLS: true
            });
    
            const ticket_id = "{{ isset($ticket) ? $ticket['ticket_id'] : '' }}";
            const channel = pusher.subscribe(`ticket-reply-send-${ticket_id}`);
    
            channel.bind(`ticket-reply-send-event-${ticket_id}`, function(data) {
                if (ticket_id === data.ticket_number) {
                    const avatarSrc = LetterAvatar(data.sender_name, 100);
                    const messageList = $('.conversion-container');
                    const staffBadge = data.converstation.sender !== 'user' 
                        ? `<span class="staff-badge">${data.replyByRole}</span>`  : '';
    
                    const newMessage = `
                        <div class="card message-card mb-0">
                            <div class="card-header d-flex align-items-center gap-3">
                                <div class="user-img">
                                    <img src="${avatarSrc}" alt="${avatarSrc}" loading="lazy" avatar="${avatarSrc}">
                                </div>
                                <div class="user-info">
                                    <h3 class="mb-2 fs-5 d-flex gap-1">
                                        ${data.sender_name}
                                        ${staffBadge}
                                    </h3>
                                    <span>(${data.timestamp})</span>
                                </div>
                            </div>
                            <div class="card-body w-100">
                                <div>${data.new_message}</div>
                                ${data.attachments ? `
                                    <div class="m-1">
                                        <h6>{{ __('Attachments') }} :</h6>
                                        <ul class="list-group list-group-flush">
                                            ${data.attachments.map(function(attachment) {
                                                const filename = attachment.split('/').pop();
                                                const fullUrl = data.baseUrl + attachment;
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
                    const ticketAuthCard = $('.ticket-auth-card-inner');
                    ticketAuthCard.scrollTop(ticketAuthCard[0].scrollHeight);
                    window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
                    LetterAvatar.transform();
                }
            });
        </script>
        <script>
            const input = document.getElementById('fileInput');
            const fileNamesDisplay = document.getElementById('fileNames');
    
            input.addEventListener('change', function() {
                const files = Array.from(input.files);
    
                if (files.length > 0) {
                    const names = files.map(file => `• ${file.name}`).join('<br>');
                    fileNamesDisplay.innerHTML = names;
                    fileNamesDisplay.classList.add('has-files');
                } else {
                    fileNamesDisplay.innerHTML = 'No files selected';
                    fileNamesDisplay.classList.remove('has-files');
                }
            });
        </script>
        @endif
    @endpush