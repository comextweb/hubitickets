<div class="tag-chioce">
    <select name="tags[]" class="form-control tags" id="choices-multiple-remove-button"
        data-url="{{ route('ticket.assign.tags', ['id' => isset($ticket) ? $ticket->id : '0']) }}" multiple>
        @foreach ($tags as $tag)
            <option value="{{ $tag->id }}" {{ isset($ticket->tags_id) && in_array($tag->id, explode(',', $ticket->tags_id)) ? 'selected' : '' }}>
                {{ $tag->name }}
            </option>
        @endforeach
    </select>
</div>
<script>
    var multipleCancelButton = new Choices(
        '#choices-multiple-remove-button', {
        removeItemButton: true,
    }
    );

    // tags change
    $('.tags').off('change').on('change', function () {
        var id = $('.user_chat.active').attr('id');
        var selectedTags = $(this).val() || []; // Handle empty state when no tags are selected

        $.ajax({
            url: '{{ route('ticket.assign.tags', ':id') }}'.replace(':id', id),
            type: 'POST',
            data: {
                'tags': selectedTags,
                'id': id,
                _token: "{{ csrf_token() }}",

            },
            cache: false,
            success: function (data) {
                if (data.status === 'success') {
                    show_toastr('Success', data.message, 'success');
                } else {
                    if (data.flag == 0) {
                        show_toastr('Error', data.msg, 'error');
                    }
                }
            },
            error: function (xhr, status, error) {
                show_toastr('Error', 'An error occurred: ' + xhr.responseText, 'error');
            }
        });
    });
</script>