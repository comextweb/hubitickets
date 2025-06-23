
<form action="{{route('rating.update',$rating->id)}}" class="needs-validation" novalidate method="post">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="form-group col-md-12">
            <label for="ticket_id" class="form-label">{{ __('Ticket') }}</label><x-required></x-required>
            <div class="col-sm-12 col-md-12">
                <!-- <select name="ticket_id" class="form-control ticket_id" required>
                    <option value=''>{{ __('Select Ticket') }}</option>
                    @foreach($tickets as $key => $ticket)                    
                        <option value="{{ $ticket->id }}" {{ $ticket->id == $rating->ticket_id ? 'selected' : ''}} {{ in_array($ticket->id, $ticketRatings) ? 'disabled' : '' }}>{{ moduleIsActive('TicketNumber') ? Workdo\TicketNumber\Entities\TicketNumber::ticketNumberFormat($ticket->id) : $ticket->ticket_id }}</option>
                    @endforeach
                </select> -->
                <input type="text" id="ticket" class="form-control" value="{{ moduleIsActive('TicketNumber') ? Workdo\TicketNumber\Entities\TicketNumber::ticketNumberFormat(isset($rating->getTicketDetails) ? $rating->getTicketDetails->id : '-') : (isset($rating->getTicketDetails) ? $rating->getTicketDetails->ticket_id : '-') }}" disabled>
                <input type="hidden" name="ticket" value="{{ $rating->ticket_id }}">
            </div>
        </div>
        <div class="form-group col-md-12">
            <label class="form-label" for="rating_date">{{ __('Rating Date') }}</label><x-required></x-required>
            <input type="date" name="rating_date" class="form-control" required value="{{ $rating->rating_date }}">
        </div>
        <div class="rating-stars admin form-group col-md-12 ms-2">
            <label class="form-label">{{ __('Rating') }}</label><x-required></x-required>
            <ul id='stars'>
                @for ($i = 1; $i <= 5; $i++)
                    <li class='star {{ $rating->rating >= $i ? 'selected' : '' }}'
                        title='{{ $i }}' data-value='{{ $i }}'>
                        <i class='fa fa-star fa-fw'></i>
                    </li>
                @endfor
            </ul>
            <input type="hidden" name="rating" id="rating" value="{{ $rating->rating }}">
        </div>
        <div class="form-group col-md-12">
            <label class="form-label">{{ __('Review') }}</label><x-required></x-required>
            <textarea name="description" id="description" cols="30" rows="3" placeholder="{{ __('Write your review here') }}" class="form-control" required>{{ $rating->description }}</textarea>
        </div>
    </div>
    <div class="modal-footer p-0 pt-3">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary" data-bs-dismiss="modal">
        <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
    </div>
</form>

<script>
    $(document).ready(function() {
        $('.ticket_id').on('click', function() {
            var selectedValue = $(this).val();
            $('#ticket').val(selectedValue);
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#stars li').on('click', function() {
            var selectedValue = $(this).data('value');
            $('#rating').val(selectedValue);
        });
    });
</script>

<script>
    $(document).ready(function() {

        /* 1. Visualizing things on Hover - See next part for action on click */
        $(document).on('mouseover', '#stars li', function() {
            var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on

            // Now highlight all the stars that's not after the current hovered star
            $(this).parent().children('li.star').each(function(e) {
                if (e < onStar) {
                    $(this).addClass('hover');
                } else {
                    $(this).removeClass('hover');
                }
            });

        }).on('mouseout', function() {
            $(this).parent().children('li.star').each(function(e) {
                $(this).removeClass('hover');
            });
        });


        /* 2. Action to perform on click */
        $(document).on('click', '#stars li', function() {
            var onStar = parseInt($(this).data('value'), 10); // The star currently selected
            var stars = $(this).parent().children('li.star');
            for (i = 0; i < stars.length; i++) {
                $(stars[i]).removeClass('selected');
            }

            for (i = 0; i < onStar; i++) {
                $(stars[i]).addClass('selected');
            }

            $('#rating_no').val(onStar);
            // JUST RESPONSE (Not needed)
            var ratingValue = parseInt($('#stars li.selected').last().data('value'), 10);
            var msg = "";
            if (ratingValue > 1) {
                msg = "Thanks! You rated this " + ratingValue + " stars.";
            } else {
                msg = "We will improve ourselves. You rated this " + ratingValue + " stars.";
            }
            // responseMessage(msg);

        });
    });
</script>