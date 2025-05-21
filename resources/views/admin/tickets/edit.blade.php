@extends('layouts.admin')

@section('page-title')
    {{ __('Reply Ticket') }} - {{ isset($isTicketNumberActive) && $isTicketNumberActive ? Workdo\TicketNumber\Entities\TicketNumber::ticketNumberFormat($ticket->id) : $ticket->ticket_id }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.tickets.index') }}">{{ __('Tickets') }}</a></li>
    <li class="breadcrumb-item">{{ __('Reply') }}</li>
@endsection

@section('multiple-action-button')
    <div class="row justify-content-end">
        <div class="col-auto">
            @permission('ticket edit')
                <div class="btn btn-sm btn-info btn-icon m-1 float-end">
                    <a href="#ticket-info" class="" type="button" data-bs-toggle="collapse" data-bs-placement="top"
                        title="{{ __('Edit Ticket') }}"><i class="ti ti-pencil text-white"></i></a>
                </div>
            @endpermission
        </div>
    </div>
@endsection
@push('css-page')
    <link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
@endpush
@section('content')
    @permission('ticket edit')
        <form action="{{ route('admin.tickets.update', $ticket->id) }}" method="POST" class="needs-validation collapse mt-3"
            id="ticket-info" enctype="multipart/form-data" novalidate>
            @csrf @method('PUT')

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div
                            class="card-header flex-column flex-lg-row  d-flex align-items-lg-center gap-2 justify-content-between">
                            <h6>{{ __('Ticket Information') }}</h6>
                            @if (isset($settings['is_enabled']) && $settings['is_enabled'] == 'on')
                                <a class="btn btn-primary btn-sm float-end ms-2" href="#" data-size="lg"
                                    data-ajax-popup-over="true" data-url="{{ route('generate', ['support']) }}"
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}"
                                    data-title="{{ __('Generate Content with AI') }}"><i class="fas fa-robot me-1">
                                        </i>{{ __('Generate with AI') }}</a>
                            @endif
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="require form-label">{{ __('Name') }}</label><x-required></x-required>
                                    <input class="form-control @error('name') is-invalid @enderror" type="text"
                                        name="name" required="" value="{{ $ticket->name }}"
                                        placeholder="{{ __('Name') }}">
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="require form-label">{{ __('Email') }}</label><x-required></x-required>
                                    <input class="form-control @error('email') is-invalid @enderror" type="email"
                                        name="email" required="" value="{{ $ticket->email }}"
                                        placeholder="{{ __('Email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="require form-label">{{ __('Category') }}</label><x-required></x-required>
                                    <select class="form-select @error('category') is-invalid @enderror" name="category"
                                        id="ticketCategory" required="">
                                        <option value="">{{ __('Select Category') }}</option>
                                        @foreach ($categoryTree as $category)
                                            <option value="{{ $category['id'] }}"
                                                {{ $ticket->category_id == $category['id'] ? 'selected' : '' }}>
                                                {!! $category['name'] !!}</option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="require form-label">{{ __('Status') }}</label><x-required></x-required>
                                    <select class="form-select @error('status') is-invalid @enderror" name="status"
                                        required="">
                                        <option value="New Ticket" @if ($ticket->status == 'New Ticket') selected @endif>
                                            {{ __('New Ticket') }}</option>
                                        <option value="In Progress" @if ($ticket->status == 'In Progress') selected @endif>
                                            {{ __('In Progress') }}</option>
                                        <option value="On Hold" @if ($ticket->status == 'On Hold') selected @endif>
                                            {{ __('On Hold') }}</option>
                                        <option value="Closed" @if ($ticket->status == 'Closed') selected @endif>
                                            {{ __('Closed') }}</option>
                                        <option value="Resolved" @if ($ticket->status == 'Resolved') selected @endif>
                                            {{ __('Resolved') }}</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="require form-label">{{ __('Subject') }}</label><x-required></x-required>
                                    <input class="form-control @error('subject') is-invalid @enderror" type="text"
                                        name="subject" required="" value="{{ $ticket->subject }}"
                                        placeholder="{{ __('Subject') }}">
                                    @error('subject')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="require form-label">{{ __('Priority') }}</label><x-required></x-required>
                                    <select class="form-control @error('priority') is-invalid @enderror" name="priority"
                                        required="">
                                        <option value="">{{ __('Select Priority') }}</option>

                                        @foreach ($priorities as $priority)
                                            <option value="{{ $priority->id }}"
                                                @if ($ticket->priority == $priority->id) selected @endif>{{ $priority->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                @if(moduleIsActive('OutOfOffice'))
                                @stack('is_available_edit')
                                @else
                                <div class="form-group col-md-6">
                                    <label class="require form-label">{{ __('Agent') }}</label><x-required></x-required>
                                    <select id="agents" class="form-select @error('category') is-invalid @enderror"
                                        name="agent_id" required>
                                        <option value="">{{ __('Select Agent') }}</option>
                                        @foreach ($users as $agent)
                                            <option value="{{ $agent->id }}"
                                                {{ $ticket->is_assign == $agent->id ? 'selected' : '' }}>
                                                {{ $agent->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('agent_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                @endif


                                {{-- <x-mobile divClass="col-md-6" value="{{ $ticket->mobile_no }}"></x-mobile> --}}

                                <div class="form-group col-md-6">
                                    <label class="require form-label">{{ __('Attachments') }}
                                        <small>({{ __('You can select multiple files') }})</small> </label>
                                    <div class="choose-file form-group">
                                        <label for="file" class="form-label d-block">
                                            <input type="file" name="attachments[]" id="file"
                                                class="form-control mb-2 @error('attachments') is-invalid @enderror"
                                                multiple="" data-filename="multiple_file_selection"
                                                onchange="document.getElementById('blah2').src = window.URL.createObjectURL(this.files[0])">
                                            <img src="" id="blah2" width="20%" />

                                            @error('attachments')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </label>
                                    </div>
                                    <div class="mx-4">
                                        <p class="multiple_file_selection mb-0"></p>
                                        <ul class="list-group list-group-flush w-100 attachment_list">
                                            @php $attachments = json_decode($ticket->attachments); @endphp
                                            @if (!empty($attachments))
                                                @foreach ($attachments as $index => $attachment)
                                                    <li class="list-group-item px-0 me-3 b-0">
                                                        <a download=""
                                                            href="{{ !empty($attachment) && checkFile($attachment) ? getFile($attachment) : getFile('uploads/default-images/image_not_available.png') }}"
                                                            class="btn btn-sm btn-primary d-inline-flex align-items-center"
                                                            data-bs-toggle="tooltip" title="{{ __('Download') }}">
                                                            <i class="ti ti-arrow-bar-to-down me-2"></i>
                                                            {{ basename($attachment) }}
                                                        </a>
                                                        <a class="bg-danger ms-2 mx-3 btn btn-sm d-inline-flex align-items-center"
                                                            title="{{ __('Delete') }}"
                                                            onclick="(confirm('Are You Sure?')?(document.getElementById('user-form-{{ $index }}').submit()):'');">
                                                            <i class="ti ti-trash text-white"></i>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>

                                </div>


                                <div class="form-group col-md-12">
                                    <label class="require form-label">{{ __('Description') }}</label>
                                    <textarea name="description" id="description"
                                        class="form-control summernote-simple @error('description') is-invalid @enderror">{!! $ticket->description !!}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                @if (!$customFields->isEmpty())
                                    @include('admin.customFields.formBuilder')
                                @endif
                            </div>

                            <div class="text-end">
                                <a class="btn btn-secondary custom-cancel-btn btn-light mr-2"
                                    href="{{ route('admin.tickets.index') }}">{{ __('Cancel') }}</a>
                                <button class="btn btn-primary btn-block btn-submit"
                                    type="submit">{{ __('Update') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        @php
            $attachments = json_decode($ticket->attachments);
        @endphp
        @if (isset($attachments))
            @foreach ($attachments as $index => $attachment)
                <form method="post" id="user-form-{{ $index }}"
                    action="{{ route('admin.tickets.attachment.destroy', [$ticket->id, $index]) }}">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
        @endif
    @endpermission
    <div class="row mt-3">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h6>
                            <span class="text-left">
                                {{ $ticket->name }} <small>({{ $ticket->created_at->diffForHumans() }})</small>
                                <span class="d-block"><small>{{ $ticket->email }}</small></span>
                            </span>
                        </h6>
                        <small>
                            <span class="text-right">
                                {{ __('Status') }} : <span
                                    class="badge rounded @if ($ticket->status == 'In Progress') badge bg-warning  @elseif($ticket->status == 'On Hold') badge bg-danger @else badge bg-success @endif">{{ __($ticket->status) }}</span>
                            </span>
                            <span class="d-block"> {{ __('Category') }} : <span
                                    class="badge bg-primary rounded">{{ $ticket->getCategory ? $ticket->getCategory->name : '-' }}</span>
                            </span>
                        </small>
                    </div>
                    <div class="row">
                        @foreach ($customFields as $field)
                        <div class="col-6">
                                <small>
                                    <span class="text-right">
                                        {{ $field->name }} : {!! !empty($field->getData($ticket, $field->id))
                                            ? $field->getData($ticket, $field->id)
                                            : '-' !!}
                                    </span>
                                </small>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <p>{!! $ticket->description !!}</p>
                    </div>
                    @php
                        $attachments = json_decode($ticket->attachments);
                    @endphp
                    @if (isset($attachments))
                        <div class="m-1">
                            <h6>{{ __('Attachments') }} :</h6>
                            <ul class="list-group list-group-flush">
                                @foreach ($attachments as $index => $attachment)
                                    <li class="list-group-item px-0">
                                        {{ basename($attachment) }}
                                        <a download=""
                                            href="{{ !empty($attachment) && checkFile($attachment) ? getFile($attachment) : getFile('uploads/default-images/image_not_available.png') }}"
                                            class="edit-icon py-1 ml-2" title="{{ __('Download') }}"><i
                                                class="fas fa-download ms-2"></i></a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
            @foreach ($ticket->conversions as $conversion)
                <div class="card">
                    <div class="card-header">
                        <h6>{{ $conversion->replyBy()->name }}
                            <small>({{ $conversion->created_at->diffForHumans() }})</small>
                        </h6>
                    </div>
                    <div class="card-body">
                        <div>{!! $conversion->description !!}</div>
                        @php $attachments = json_decode($conversion->attachments); @endphp
                        @if (isset($attachments))
                            <div class="m-1">
                                <h6>{{ __('Attachments') }} :</h6>
                                <ul class="list-group list-group-flush">
                                    @foreach ($attachments as $index => $attachment)
                                        <li class="list-group-item px-0">
                                            {{ basename($attachment) }}
                                            <a download=""
                                                href="{{ !empty($attachment) && checkFile($attachment) ? getFile($attachment) : getFile('uploads/default-images/image_not_available.png') }}"
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
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                @permission('ticket reply')
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        @if ($ticket->status != 'Closed')
                            <div class="card">
                                <div
                                    class="card-header flex-column flex-lg-row  d-flex align-items-lg-center gap-2 justify-content-between">
                                    <h6>{{ __('Add Reply') }}</h6>
                                    @if (isset($settings['is_enabled']) && $settings['is_enabled'] == 'on')
                                        <div class="col-md-8">
                                            <div class="float-end">
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
                                                    data-url="{{ route('generate', ['reply']) }}" data-toggle="tooltip"
                                                    title="{{ __('Generate') }}">
                                                    <i class="fas fa-robot me-1"></i></span><span
                                                    class="robot">{{ __('Generate With AI') }}</span>
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <form method="POST" action="{{ route('admin.conversion.store', $ticket->id) }}"
                                    enctype="multipart/form-data" class="needs-validation" novalidate>
                                    @csrf
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label class="require form-label">{{ __('Description') }}</label>
                                            <textarea name="reply_description" id="reply_description"
                                                class="form-control summernote-simple grammer_textarea @error('name') is-invalid @enderror" required></textarea>
                                            @error('reply_description')
                                                <div class="invalid-feedback d-block">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group file-group mb-5">
                                            <label class="require form-label">{{ __('Attachments') }}</label>
                                            <label
                                                class="form-label"><small>({{ __('You can select multiple files') }})</small></label>
                                            <div class="choose-file form-group">
                                                <label for="file" class="form-label d-block">
                                                    <div>{{ __('Choose File Here') }}</div>

                                                    <input type="file" name="reply_attachments[]" id="file"
                                                        class="form-control mb-2 {{ $errors->has('reply_attachments') ? ' is-invalid' : '' }}"
                                                        multiple="" data-filename="multiple_reply_file_selection"
                                                        onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                                                    <img src="" id="blah" width="20%" />
                                                    @error('reply_description')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </label>
                                            </div>
                                        </div>
                                        <p class="multiple_reply_file_selection"></p>
                                        <div class="text-end">
                                            <button class="btn btn-primary btn-block mt-2 btn-submit"
                                                type="submit">{{ __('Submit') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                @endpermission

                @permission('tiketnote store')
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div
                                class="card-header flex-column flex-lg-row  d-flex align-items-lg-center gap-2 justify-content-between">
                                <h6>{{ __('Note') }}</h6>
                                @if (isset($settings['is_enabled']) && $settings['is_enabled'] == 'on')
                                    <a class="btn btn-primary btn-sm float-end ms-2" href="#" data-size="lg"
                                        data-ajax-popup-over="true" data-url="{{ route('generate', ['note']) }}"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}"
                                        data-title="{{ __('Generate Content with AI') }}"><i class="fas fa-robot me-1">
                                           </i> {{ __('Generate with AI') }}</a>
                                @endif
                            </div>
                            <form method="post" action="{{ route('admin.note.store', $ticket->id) }}">
                                @csrf
                                <div class="card-body adjust_card_width">
                                    <div class="form-group ckfix_height">
                                        <textarea name="note" class="form-control summernote-simple" id="note">{{ $ticket->note }}</textarea>
                                        <div class="invalid-feedback">
                                            {{ $errors->first('note') }}
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <button class="btn btn-primary btn-block mt-2 btn-submit"
                                            type="submit">{{ __('Add Note') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endpermission
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>
    {{-- <script>
        $(document).ready(function() {
            $('#ticketCategory').change(function() {
                let categoryId = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.get.random.agent') }}",
                    data: {
                        categoryId: categoryId
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status == 'success') {
                            let agents = response.message;
                            $('#agents').empty();
                            $('#agents').append(
                                '<option value="" selected disabled>Select an agent</option>'
                                );
                            $.each(agents, function(index, agent) {
                                $('#agents').append(
                                    `<option value="${agent.id}">${agent.name}</option>`
                                    );
                            });
                        } else {
                            show_toastr(response.status, response.message, 'error');
                        }
                    }
                });
            });
        });
    </script> --}}
    <script>
        if ($(".summernote-simple").length > 0) {
            $('.summernote-simple').summernote({
                dialogsInBody: !0,
                minHeight: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'strikethrough']],
                    ['list', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'unlink']],
                ],
                height: 250,
            });
        }
    </script>
@endpush
