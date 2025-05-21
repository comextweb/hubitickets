<div class="private=-note">
    <div class="col-lg-12">
        <div class="form-group mb-3 ">
            <div class="row align-items-center mb-2">
                <div class="col">
                    <label for="private_note" class="form-label mb-0">{{ __('Private Note') }}</label>
                </div>
                @if (isset($settings['is_enabled']) && $settings['is_enabled'] == 'on')
                    <div class="col-auto">
                        <a class="btn btn-primary btn-sm" href="#" data-size="lg" data-ajax-popup-over="true"
                            data-url="{{ route('generate', ['note']) }}" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="{{ __('Generate') }}" data-title="{{ __('Generate Content with AI') }}">
                            <i class="fas fa-robot me-1"></i>{{ __('Generate with AI') }}
                        </a>
                    </div>
                @endif
            </div>
            
            
            <textarea name="private_note" id="private_note" class="form-control summernote-simple" cols="3"
                rows="3">{{$ticket->note ?? ''}}</textarea>
        </div>
    </div>
    <div class="modal-footer p-0 pt-3">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
        <button type="submit" class="btn btn-primary save_private_note" data-bs-dismiss="modal">{{__('Save')}}</button>
    </div>
</div>
<script>
    $(document).off('click').on('click', '.save_private_note', function () {
        var privateNote = $('#private_note').val();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '{{ route('admin.ticket.note.store', ['ticketId' => $ticket->id]) }}',
            method: 'POST',
            data: {
                ticketPrivatnote: privateNote,
            },
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            cache: false,
            success: function (response) {
                if (response.status == true) {
                    show_toastr('success', response.message, 'success');
                } else {
                    show_toastr('Error', response.message, 'error');
                }
            }
        });
    });
</script>