<li><a class="btn-submit btn btn-outline-primary" type="button" id="send_close">{{ __('Send & Close') }}</a></li>



<script>
    $(document).on('click', '#send_close', function(e) {
        e.preventDefault();


        var ticket_id = $('.user_chat.active').attr('id');
        var formData = new FormData($('#your-form-id')[0]);
        var description = $('#reply_description').val();
        var file = $('#file').val();

        // when description and attchment null
        if (description.trim() === '' && file.trim() === '') {
            show_toastr('Error', "{{ __('Please add a description or attachment.') }}",
                'error');
        } else {
            $.ajax({
                url: "{{ url('/sendclose/ticketsendclose') }}" + '/' + ticket_id,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                success: function(data) {
                    if (data.status == 'success') {
                        const messageList = $('.messages-container');
                        let avatarSrc = LetterAvatar(data.sender_name, 100);
                        $('#reply_description').summernote('code', '');
                        $('.multiple_reply_file_selection').text('');
                        $('#file').val('');

                        var newMessage = `
                            <div class="msg right-msg">
                                <div class="msg-box">
                                    <div class="msg-box-content">
                                        <p>${data.new_message}</p>
                                        ${data.attachments ? `
                                            <div class="attachments-wrp">
                                                <h6>Attachments:</h6>
                                                    <ul class="attachments-list">
                                                        ${data.attachments.map(function(attachment) {
                                                            var filename = attachment.split('/').pop();
                                                            var fullUrl = data.baseUrl + attachment;
                                                            return `
                                                                <li>
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
                                        <span>${data.timestamp}</span>
                                    </div>
                                    <div class="msg-user-info">
                                        <div class="msg-img">
                                            <img alt="${data.sender_name}" class="img-fluid" src="${avatarSrc}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        messageList.append(newMessage);
                        $('.chat-container').scrollTop($('.chat-container')[0].scrollHeight);

                        LetterAvatar.transform();

                        show_toastr('Success', data.message, 'success');
                    } else {
                        show_toastr('Error', data.message, 'error');
                    }
                }, error: function(xhr) {
                     // If the validation fails, the status code will be 422
                    if (xhr.status == 422) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';
                        for (var field in errors) {
                                    errorMessage += errors[field].join('<br>');
                        }
                        show_toastr('Error', errorMessage, 'error');
                    }
                }
            });
        }

    });
</script>
