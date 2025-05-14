<link rel="stylesheet" href="{{ asset('packages/workdo/Tags/src/Resources/assets/css/custom.css') }}">  
@php
    $tags = Workdo\Tags\Entities\Tags::get();
@endphp
<li class="d-flex align-items-center">
    <span>{{ __('Tags') }} :</span>
    <select name="tags[]" class="form-control tags" id="choices-multiple-remove-button"
        data-url="{{ route('ticket.assign.tags', ['id' => isset($ticket) ? $ticket->id : '0']) }}" multiple>
        @foreach ($tags as $tag)
            <option value="{{ $tag->id }}" {{ isset($ticket->tags_id) && in_array($tag->id, explode(',', $ticket->tags_id)) ? 'selected' : '' }}>
                {{ $tag->name }}
            </option>
        @endforeach
    </select>
</li>

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
            success: function(data) {
                if (data.status === 'success') {
                    show_toastr('Success', data.message, 'success');
                } else {
                    show_toastr('Error', data.message, 'error');
                }
            },
            error: function(xhr, status, error) {
                show_toastr('Error', 'An error occurred: ' + xhr.responseText, 'error');
            }
        });
    });
</script>
