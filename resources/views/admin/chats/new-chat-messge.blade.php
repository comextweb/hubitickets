@push('css-page')
    <link rel="stylesheet" href="{{ asset('css/main-style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
@endpush
<div class="chat-top-content">
    <div class="chat-container">
        {{-- Chat First Message --}}
        <div class="chat-container-wrp">
            @if ($ticket->type != 'Instagram' && $ticket->type != 'Facebook')
                {{-- customer login --}}
                @if(moduleIsActive('CustomerLogin') && \Auth::user()->type == 'customer')
                    <div class="msg right-msg">
                        <div class="msg-box">

                            <div class="msg-box-content">
                                <p>{!! $ticket->description !!}</p>
                                @php $attachments = json_decode($ticket->attachments); @endphp
                                @if (isset($attachments))
                                    <div class="attachments-wrp mb-1">
                                        <h6>{{ __('Ticket Attachments') }} :</h6>
                                        <ul class="attachments-list">
                                            @foreach ($attachments as $index => $attachment)
                                                <li>
                                                    <span> {{ basename($attachment) }} </span>
                                                    <a download=""
                                                        href="{{ !empty($attachment) && checkFile($attachment) ? getFile($attachment) : getFile('uploads/default-images/image_not_available.png') }}"
                                                        class="edit-icon py-1 ml-2" title="{{ __('Download') }}"><i
                                                            class="fa fa-download ms-2"></i></a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <span>{{ \Carbon\Carbon::parse($ticket->created_at)->format('l h:ia') }}</span>
                            </div>
                            <div class="msg-user-info" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="{{ $ticket->name }}">
                                <div class="msg-img">
                                    <img alt="{{ $ticket->name }}" class="img-fluid" avatar="{{ $ticket->name }}">
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="msg left-msg">
                        <div class="msg-box">
                            <div class="msg-user-info" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="{{ $ticket->name }}">
                                <div class="msg-img">
                                    <img alt="{{ $ticket->name }}" class="img-fluid" avatar="{{ $ticket->name }}">
                                </div>
                            </div>
                            <div class="msg-box-content">
                                <p>{!! $ticket->description !!}</p>
                                @php $attachments = json_decode($ticket->attachments);@endphp
                                @if (isset($attachments) && !empty($attachments))
                                    <div class="attachments-wrp mb-1">
                                        <h6>{{ __('Ticket Attachments') }} :</h6>
                                        <ul class="attachments-list">
                                            @foreach ($attachments as $index => $attachment)
                                                <li>
                                                    <span> {{ basename($attachment) }} </span>
                                                    <a download=""
                                                        href="{{ !empty($attachment) && checkFile($attachment) ? getFile($attachment) : getFile('uploads/default-images/image_not_available.png') }}"
                                                        class="edit-icon py-1 ml-2" title="{{ __('Download') }}"><i
                                                            class="fa fa-download ms-2"></i></a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <span>{{ \Carbon\Carbon::parse($ticket->created_at)->format('l h:ia') }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>

        <div class="messages-container" id="msg">
            @foreach ($ticket->conversions as $conversion)
                @if(moduleIsActive('CustomerLogin') && \Auth::user()->type == 'customer')
                    @if ($conversion->sender == 'user')
                        <div class="msg right-msg">
                            <div class="msg-box {{ isset($isSaveChat, $conversion->is_bookmark) && $isSaveChat && $conversion->is_bookmark ? 'bookmark-active' : '' }}"
                                data-conversion-id="{{ $conversion->id }}">
                                <div class="msg-box-content">
                                    <p> {!! $conversion->description !!} </p>
                                    @php $attachments = json_decode($conversion->attachments); @endphp

                                    @if (isset($attachments))
                                        <div class="attachments-wrp">
                                            <h6>{{ __('Attachments') }} :</h6>
                                            <ul class="attachments-list">
                                                @foreach ($attachments as $index => $attachment)
                                                    <li>
                                                        <span> {{ basename($attachment) }} </span>
                                                        <a download=""
                                                            href="{{ !empty($attachment) && checkFile($attachment) ? getFile($attachment) : getFile('uploads/default-images/image_not_available.png') }}"
                                                            class="edit-icon py-1 ml-2" title="{{ __('Download') }}"><i
                                                                class="fa fa-download ms-2"></i></a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <span>{{ \Carbon\Carbon::parse($conversion->created_at)->format('l h:ia') }}</span>
                                    <!-- SaveChat module start -->
                                    @if (isset($isSaveChat) && $isSaveChat)
                                        @include('save-chat::bookmark', ['conversionId' => $conversion->id])
                                    @endif
                                    <!-- SaveChat module end -->
                                </div>


                                <div class="msg-user-info" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="{{ $conversion->replyBy()->name ?? '' }}">
                                    <div class="msg-img">
                                        <img alt="{{ $conversion->replyBy()->name ?? '' }}" class="img-fluid"
                                            avatar="{{ $conversion->replyBy()->name ?? '' }}">
                                    </div>

                                </div>
                            </div>
                        </div>
                    @else
                        <div class="msg left-msg">
                            <div class="msg-box {{ isset($isSaveChat, $conversion->is_bookmark) && $isSaveChat && $conversion->is_bookmark ? 'bookmark-active' : '' }}"
                                data-conversion-id="{{ $conversion->id }}">
                                <div class="msg-user-info" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="{{ $conversion->replyBy()->name }}">
                                    <div class="msg-img">
                                        @if ($ticket->type == 'Instagram' && !empty($isInstagramChat))
                                            @include('instagram-chat::instagram.profile')
                                        @elseif($ticket->type == 'Facebook' && !empty($isFacebookChat))
                                            @include('facebook-chat::facebook.profile')
                                        @else
                                            <img alt="{{ $conversion->replyBy()->name }}" class="img-fluid"
                                                avatar="{{ $conversion->replyBy()->name }}">
                                        @endif
                                    </div>

                                </div>
                                <div class="msg-box-content">
                                    <p> {!! $conversion->description !!} </p>
                                    @php $attachments = json_decode($conversion->attachments); @endphp

                                    @if (isset($attachments))
                                        <div class="attachments-wrp mb-1">
                                            <h6>{{ __('Attachments') }} :</h6>
                                            <ul class="attachments-list">
                                                @foreach ($attachments as $index => $attachment)
                                                    <li>
                                                        <span> {{ basename($attachment) ?? '' }} </span>
                                                        <a download=""
                                                            href="{{ !empty($attachment) && checkFile($attachment) ? getFile($attachment) : getFile('uploads/default-images/image_not_available.png') }}"
                                                            class="edit-icon py-1 ml-2" title="{{ __('Download') }}"><i
                                                                class="fa fa-download ms-2"></i></a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <span>{{ \Carbon\Carbon::parse($conversion->created_at)->format('l h:ia') }}</span>
                                    <!-- SaveChat module start -->
                                    @if (isset($isSaveChat) && $isSaveChat)
                                        @include('save-chat::bookmark', ['conversionId' => $conversion->id])
                                    @endif
                                    <!-- SaveChat module end -->
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    @if ($conversion->sender == 'user')
                        <div class="msg left-msg">
                            <div class="msg-box {{ isset($isSaveChat, $conversion->is_bookmark) && $isSaveChat && $conversion->is_bookmark ? 'bookmark-active' : '' }}"
                                data-conversion-id="{{ $conversion->id }}">
                                <div class="msg-user-info" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="{{ $conversion->replyBy()->name }}">
                                    <div class="msg-img">
                                        @if ($ticket->type == 'Instagram' && !empty($isInstagramChat))
                                            @include('instagram-chat::instagram.profile')
                                        @elseif($ticket->type == 'Facebook' && !empty($isFacebookChat))
                                            @include('facebook-chat::facebook.profile')
                                        @else
                                            <img alt="{{ $conversion->replyBy()->name }}" class="img-fluid"
                                                avatar="{{ $conversion->replyBy()->name }}">
                                        @endif
                                    </div>

                                </div>
                                <div class="msg-box-content">
                                    <p> {!! $conversion->description !!} </p>
                                    @php $attachments = json_decode($conversion->attachments); @endphp

                                    @if (isset($attachments))
                                        <div class="attachments-wrp mb-1">
                                            <h6>{{ __('Attachments') }} :</h6>
                                            <ul class="attachments-list">
                                                @foreach ($attachments as $index => $attachment)
                                                    <li>
                                                        <span> {{ basename($attachment) ?? '' }} </span>
                                                        <a download=""
                                                            href="{{ !empty($attachment) && checkFile($attachment) ? getFile($attachment) : getFile('uploads/default-images/image_not_available.png') }}"
                                                            class="edit-icon py-1 ml-2" title="{{ __('Download') }}"><i
                                                                class="fa fa-download ms-2"></i></a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <span>{{ \Carbon\Carbon::parse($conversion->created_at)->format('l h:ia') }}</span>
                                    <!-- SaveChat module start -->
                                    @if (isset($isSaveChat) && $isSaveChat)
                                        @include('save-chat::bookmark', ['conversionId' => $conversion->id])
                                    @endif
                                    <!-- SaveChat module end -->
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="msg right-msg">
                            <div class="msg-box {{ isset($isSaveChat, $conversion->is_bookmark) && $isSaveChat && $conversion->is_bookmark ? 'bookmark-active' : '' }}"
                                data-conversion-id="{{ $conversion->id }}">
                                <div class="msg-box-content">
                                    <p> {!! $conversion->description !!} </p>
                                    @php $attachments = json_decode($conversion->attachments); @endphp

                                    @if (isset($attachments))
                                        <div class="attachments-wrp">
                                            <h6>{{ __('Attachments') }} :</h6>
                                            <ul class="attachments-list">
                                                @foreach ($attachments as $index => $attachment)
                                                    <li>
                                                        <span> {{ basename($attachment) }} </span>
                                                        <a download=""
                                                            href="{{ !empty($attachment) && checkFile($attachment) ? getFile($attachment) : getFile('uploads/default-images/image_not_available.png') }}"
                                                            class="edit-icon py-1 ml-2" title="{{ __('Download') }}"><i
                                                                class="fa fa-download ms-2"></i></a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <span>{{ \Carbon\Carbon::parse($conversion->created_at)->format('l h:ia') }}</span>
                                    <!-- SaveChat module start -->
                                    @if (isset($isSaveChat) && $isSaveChat)
                                        @include('save-chat::bookmark', ['conversionId' => $conversion->id])
                                    @endif
                                    <!-- SaveChat module end -->
                                </div>


                                <div class="msg-user-info" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="{{ $conversion->replyBy()->name ?? '' }}">
                                    <div class="msg-img">
                                        <img alt="{{ $conversion->replyBy()->name ?? '' }}" class="img-fluid"
                                            avatar="{{ $conversion->replyBy()->name ?? '' }}">
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            @endforeach
        </div>
    </div>
    <div class="chat-footer">
        <div class="tabs-wrapper">
            <ul class="chat-tabs nav nav-pills nav-fill" id="pills-tab" role="tablist">
                @if ($ticket->status != 'Closed')
                    <li data-tab="chat-tab-1" class="{{ $ticket->status != 'Closed' ? 'active' : '-' }}">
                        {{ __('Reply') }}
                    </li>
                @endif
                <li data-tab="chat-tab-2" class="{{ $ticket->status == 'Closed' ? 'active' : '-' }}">
                    {{ __('Private Note') }}
                </li>
            </ul>

        </div>
        <div class="tabs-container">
            @if ($ticket->status != 'Closed')
                <div class="tab-content {{ $ticket->status != 'Closed' ? 'active' : '-' }} " id="chat-tab-1">

                    <form method="POST" action="{{ route('admin.reply.store', $ticket->id) }}" enctype="multipart/form-data"
                        class="needs-validation" novalidate id="your-form-id">

                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <div
                                    class="flex-column  flex-wrap flex-lg-row  d-flex align-items-lg-center gap-2 justify-content-between mb-3">
                                    <label class="require form-label mb-0 w-auto">{{ __('Description') }}</label>
                                    @if (isset($settings['is_enabled']) && $settings['is_enabled'] == 'on')
                                        <div class="col-auto">
                                            <div class="d-flex flex-wrap gap-3 ">
                                                <a href="#" data-size="md" class="btn btn-primary btn-icon btn-sm"
                                                    data-ajax-popup-over="true" id="grammarCheck"
                                                    data-url="{{ route('grammar', ['grammar']) }}" data-bs-placement="top"
                                                    data-title="{{ __('Grammar check with AI') }}">
                                                    <i class="ti ti-rotate"></i>
                                                    <span>{{ __('Grammar check with AI') }}</span>
                                                </a>
                                                <a href="#" data-size="md" class="btn btn-sm btn-primary"
                                                    data-ajax-popup-over="true" data-size="md"
                                                    data-title="{{ __('Generate content with AI') }}"
                                                    data-url="{{ route('generate', ['reply']) }}"
                                                    data-toggle="tooltip" title="{{ __('Generate') }}">
                                                    <i class="fas fa-robot"></i><span
                                                    class="robot ms-1">{{ __('Generate With AI') }}</span>
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <textarea name="reply_description" id="reply_description"
                                    class="form-control summernote-simple grammer_textarea @error('name') is-invalid @enderror"
                                    required>
                                    </textarea>
                                @error('reply_description')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group file-group mb-2">
                                <div class="choose-file form-group choose-file-col">
                                    <span>{{ __('Attachments:') }}</span>
                                    <label for="file" class="form-label mb-0">
                                        <div class="choose-file-wrp btn btn-primary btn-block btn-submit"><i
                                                class="fa fa-paperclip"></i>
                                            <input type="file" name="reply_attachments[]" id="file"
                                                class="form-control mb-2 {{ $errors->has('reply_attachments') ? ' is-invalid' : '' }}"
                                                multiple="" data-filename="multiple_reply_file_selection">
                                        </div>

                                        @error('reply_description')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </label>
                                </div>
                            </div>
                            <p class="multiple_reply_file_selection"></p>
                            <!-- SaveReply module start -->
                            @if (!Auth::user()->hasRole('customer'))
                                @stack('save-reply')
                            @endif
                            <!-- SaveReply module end -->
                            <div class="chat-footer-btn-wrp d-flex gap-3 flex-wrap align-items-start">
                                <div class="chat-footer-wrp">
                                    <div class="chat-footer-btn">
                                        <button class="btn btn-primary btn-block btn-submit" type="button"
                                            id="reply_submit">{{ __('Send') }}</button>
                                        @if (isset($isSendClose) && $isSendClose)
                                            <div class="chat-footer-dropdown">
                                                <svg xmlns="http://www.w3.org/2000/svg" shape-rendering="geometricPrecision"
                                                    text-rendering="geometricPrecision" image-rendering="optimizeQuality"
                                                    fill-rule="evenodd" clip-rule="evenodd" viewBox="0 0 512 299.283">
                                                    <path
                                                        d="M75.334 12.591C10.57-24.337-20.852 28.186 15.131 64.566l200.866 209.613c33.472 33.471 46.534 33.471 80.006 0L496.869 64.566c35.983-36.38 4.561-88.903-60.203-51.975L256 109.944 75.334 12.591z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    @if (isset($isSendClose) && $isSendClose)
                                        <ul class="list">
                                            @stack('send-close')
                                        </ul>
                                    @endif
                                </div>

                                {{-- Add Button For Close Existing Chat Ticket In Whatsapp --}}
                                @stack('whatsapp-close-ticket')
                            </div>
                        </div>
                    </form>
                </div>
            @endif
            <div class="tab-content {{ $ticket->status == 'Closed' ? 'active' : '-' }}" id="chat-tab-2">

                <form id="note-form">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <div
                                class="flex-lg-row  flex-wrap d-flex align-items-lg-center gap-2 justify-content-between mb-3">
                                <label class="require form-label mb-0 w-auto">{{ __('Description') }}</label>
                                @if (isset($settings['is_enabled']) && $settings['is_enabled'] == 'on')
                                    <a class="btn btn-primary btn-sm float-end ms-2" href="#" data-size="lg"
                                        data-ajax-popup-over="true" data-url="{{ route('generate', ['note']) }}"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="{{ __('Generate') }}"
                                        data-title="{{ __('Generate Content with AI') }}"><i class="fas fa-robot me-1">
                                            </i>{{ __('Generate with AI') }}</a>
                                @endif
                            </div>

                            <textarea name="note" id="note"
                                class="form-control summernote-simple grammer_textarea @error('name') is-invalid @enderror"
                                required>{{ $ticket->note }}</textarea>
                            @error('note')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="chat-footer-btn">
                            <button class="btn btn-primary btn-block  btn-submit" type="button"
                                id="add_note">{{ __('Add Note') }}</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<div class="msg-card-wrp">
    <div class="msg-card ticket-info-card">

        <div class="msg-card-top">
            <div class="avatar-image">
                <img class="avatar-sm rounded-circle mr-3" alt="{{ $ticket->name }}" avatar="{{ $ticket->name }}">

            </div>
            <div class="info-name">
                <h6 class="mb-3">{{ $ticket->email }}</h6>
                <div class="d-flex align-items-center flex-wrap justify-content-center ticket-no gap-2">
                    <div class="ticket-number d-flex flex-wrap align-items-center gap-2">
                        <span>{{ __('Ticket number : ') }}</span>
                        <b>{{ isset($isTicketNumberActive) && $isTicketNumberActive ? Workdo\TicketNumber\Entities\TicketNumber::ticketNumberFormat($ticket->id) : $ticket->ticket_id }}</b>
                    </div>
                    <div class="ticket-btns d-flex flex-wrap align-items-center gap-2">
                        <a href="#" class="btn px-2 btn-sm btn-primary btn-icon cp_link"
                            data-link="{{ route('home.view', \Illuminate\Support\Facades\Crypt::encrypt($ticket->ticket_id)) }}"
                            data-toggle="tooltip" data-original-title="{{ __('Click To Copy Support Ticket Url') }}"
                            title="{{ __('Click To Copy Support Ticket Url') }}" data-bs-toggle="tooltip"
                            data-bs-placement="top">
                            <i class="ti ti-copy"></i>
                        </a>
                        <!-- ExportConversations module start -->
                        @if (isset($isExportConversations) && $isExportConversations)
                            <div class="btn btn-sm btn-primary btn-icon me-2" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="{{ __('Export conversations') }}">
                                <a href="{{ route('conversation.pdf', \Illuminate\Support\Facades\Crypt::encrypt($ticket->id)) }}"
                                    target="_blank" class=""><i class="ti ti-file-export text-white"></i></a>
                            </div>
                        @endif
                        <!--  ExportConversations module end -->
                    </div>
                </div>
            </div>
        </div>
        <div class="msg-card-bottom">
            <ul>
                <li class="d-flex align-items-center">
                    <span>{{ __('Name') }} :</span>
                    <div class="badge-wrp d-flex align-items-center gap-1">
                        <div class="save-badge d-flex align-items-center gap-2 admin-edit-select " id="name">
                            <input type="text" name="name" id="ticket-name" value="{{ $ticket->name }}">

                            <div class="" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Save') }}">
                                <a href="#" id="save-name">
                                    <i class="ti ti-file-check"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="d-flex align-items-center">
                    <span>{{ __('Email') }} :</span>
                    <div class="badge-wrp d-flex align-items-center gap-1">
                        <div class="save-badge d-flex align-items-center gap-2 admin-edit-select " id="email">
                            <input type="text" name="email" id="ticket-email" value="{{ $ticket->email }}">

                            <div class="" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Save') }}">
                                <a href="#" id="save-email">
                                    <i class="ti ti-file-check"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="d-flex align-items-center">
                    <span>{{ __('Subject') }} :</span>
                    <div class="badge-wrp d-flex align-items-center gap-1">
                        <div class="save-badge d-flex align-items-center gap-2 admin-edit-select " id="subject">
                            <input type="text" name="subject" id="ticket-subject" value="{{ $ticket->subject }}">

                            <div class="" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Save') }}">
                                <a href="#" id="save-subject">
                                    <i class="ti ti-file-check"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="d-flex align-items-center">
                    <span>{{ __('Priority') }} :</span>
                    <div class="badge-wrp d-flex align-items-center gap-1">

                        <div class="badge-wrp d-flex align-items-center gap-1 admin-edit-select " id="priority-select">
                            <select id="priority" class="form-select" name="priority"
                                data-url="{{ route('admin.ticket.priority.change', ['id' => isset($ticket) ? $ticket->id : '0']) }}"
                                required>
                                <option selected disabled>{{__('Select Priority')}}</option>

                                @foreach ($priorities as $priority)
                                    <option value="{{ $priority->id }}" @if ($ticket->priority == $priority->id) selected
                                    @endif>{{ $priority->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </li>
                <li class="d-flex align-items-center">
                    <span>{{ __('Category') }} :</span>
                    <div class="badge-wrp d-flex align-items-center gap-2">

                        <div class="badge-wrp d-flex align-items-center gap-1 admin-edit-select " id="category-select">
                            <select id="category" class="form-select" name="category"
                                data-url="{{ route('admin.ticket.category.change', ['id' => isset($ticket) ? $ticket->id : '0']) }}"
                                required>
                                <option selected disabled>{{__('Select Category')}}</option>

                                @foreach ($categoryTree as $category)
                                    <option value="{{ $category['id'] }}" {{ $ticket->category_id == $category['id'] ? 'selected' : '' }}>
                                        {!! $category['name'] !!}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </li>
                <li class="d-flex align-items-center">
                    <span>{{ __('Assign Agent') }} :</span>
                    <div class="badge-wrp assign-select-wrp d-flex align-items-center gap-1">
                        @if (moduleIsActive('OutOfOffice'))
                            @stack('is_available_edit')
                        @else
                            <select id="agents" class="form-select" name="agent_id"
                                data-url="{{ route('admin.ticket.assign.change', ['id' => isset($ticket) ? $ticket->id : '0']) }}"
                                required>
                                <option selected disabled value="">{{ __('Select Agent') }}</option>
                                @foreach ($users as $agent)
                                    <option value="{{ $agent->id }}" {{ $ticket->is_assign == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->name }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </li>


                {{-- @foreach ($customFields as $field)
                <li class="d-flex align-items-center">
                    <span>{{ $field->name }} :</span>
                    <div class="badge-wrp d-flex align-items-center gap-1">
                        <div class="save-badge d-flex align-items-center gap-2 admin-edit-select" id="email">
                            @if ($field->type == 'textarea')
                            <textarea name="{{ $field->name }}" class="form-control" required>{!! !empty($field->getData($ticket, $field->id))
                                ? $field->getData($ticket, $field->id)
                                : '-' !!}</textarea>
                            @else
                            <input type="{{ $field->type }}" name="{{ $field->name }}" id="ticket-{{ $field->name }}"
                                value="{{ !empty($field->getData($ticket, $field->id)) 
                                        ? $field->getData($ticket, $field->id) 
                                        : '' }}" />
                            @endif
                            <div class="" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Save') }}">
                                <a href="#" id="save-{{$field->id}}">
                                    <i class="ti ti-file-check"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach --}}




                <!-- Tag module start -->
                @if (isset($isTags) && $isTags)
                    @include('tags::tags.tag')
                @endif
                <!-- Tag module end -->

            </ul>
        </div>
        @if (count($customFields) != 0)
            <div class="custom-field d-flex flex-wrap align-items-center justify-content-center gap-2">
                <span>{{ __('Custom field') }}</span>
                <a href="#" class="btn btn-sm btn-icon bg-warning text-white" title="{{ __('View Custom Field') }}"
                    data-bs-toggle="tooltip" data-bs-placement="top" data-ajax-popup="true"
                    data-title="{{ __('Custom Field') }}"
                    data-url="{{ route('admin.ticketcustomfield.show', $ticket->id) }}" data-size="lg"><i
                        class="ti ti-eye"></i></a>
            </div>
        @endif
        <div class="action-btn msg-delete-btn">
            <form method="POST" action="{{ route('admin.tickets.destroy', $ticket->id) }}"
                id="user-form-{{ $ticket->id }}">
                @csrf
                @method('DELETE')
                <input name="_method" type="hidden" value="DELETE">
                <a class="danger btn btn-sm align-items-center bs-pass-para bg-danger text-white border-0 show_confirm trigger--fire-modal-1"
                    data-bs-toggle="tooltip" title="" data-bs-original-title="Delete" aria-label="Delete"
                    data-confirm="{{ __('Are You Sure?') }}"
                    data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                    data-confirm-yes="delete-form-{{ $ticket->id }}"><i class="ti ti-trash"></i></a>
            </form>
        </div>
    </div>
</div>



<script>
    function setDescription(description) {
        $('#reply_description').summernote('code', description);
    }
</script>
<script>
    $('#commonModal-right').on('shown.bs.modal', function () {
        $(document).off('focusin.modal');
    });

    $(document).on('click', 'a[data-ajax-popup="true"], button[data-ajax-popup="true"]', function (e) {
        var title = $(this).data('title');
        var size = ($(this).data('size') == '') ? 'md' : $(this).data('size');
        var url = $(this).data('url');

        $("#commonModal .modal-title").html(title);
        $("#commonModal .modal-dialog").addClass('modal-' + size);

        $.ajax({
            url: url,
            cache: false,
            success: function (data) {
                $('#commonModal .modal-body ').html(data);
                $("#commonModal").modal('show');
                commonLoader();
                validation();
            },
            error: function (data) {
                data = data.responseJSON;
                show_toastr('Error', data.error, 'error')
            }
        });
        e.stopImmediatePropagation();
        return false;
    });

    $(document).on('click',
        'a[data-ajax-popup-right="true"], button[data-ajax-popup-right="true"], div[data-ajax-popup-right="true"], span[data-ajax-popup-right="true"]',
        function (e) {
            var url = $(this).data('url');

            $.ajax({
                url: url,
                cache: false,
                success: function (data) {
                    $('#commonModal-right').html(data);
                    $("#commonModal-right").modal('show');
                    commonLoader();
                    validation();
                },
                error: function (data) {
                    data = data.responseJSON;
                    show_toastr('Error', data.error, 'error')
                }
            });
        });


    $(document).on('click',
        'a[data-ajax-popup-over="true"], button[data-ajax-popup-over="true"], div[data-ajax-popup-over="true"]',
        function () {

            var validate = $(this).attr('data-validate');
            var id = '';
            if (validate) {
                id = $(validate).val();
            }

            var title = $(this).data('title');
            var size = ($(this).data('size') == '') ? 'md' : $(this).data('size');
            var url = $(this).data('url');

            $("#commonModalOver .modal-title").html(title);
            $("#commonModalOver .modal-dialog").addClass('modal-' + size);

            $.ajax({
                url: url + '?id=' + id,
                success: function (data) {
                    $('#commonModalOver .modal-body').html(data);
                    $("#commonModalOver").modal('show');
                },
                error: function (data) {
                    data = data.responseJSON;
                    show_toastr('Error', data.error, 'error')
                }
            });

        });

    $(document).on('click', '.show_confirm', function () {
        var form = $(this).closest("form");
        var title = $(this).attr("data-confirm");
        var text = $(this).attr("data-text");
        if (title == '' || title == undefined) {
            title = "Are you sure?";

        }
        if (text == '' || text == undefined) {
            text = "This action can not be undone. Do you want to continue?";

        }

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })


        swalWithBootstrapButtons.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        })
    });

    // copy link
    $('.cp_link').on('click', function () {
        var value = $(this).attr('data-link');
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val(value).select();
        document.execCommand("copy");
        $temp.remove();
        show_toastr('Success', '{{ __('Link Copy on Clipboard') }}', 'success')
    });
</script>