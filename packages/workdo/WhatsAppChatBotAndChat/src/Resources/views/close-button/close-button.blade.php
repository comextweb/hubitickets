

<div class="chat-footer-btn">
    <a href="{{ route('ask.close.ticket', ['ticketId' => encrypt($ticket->id)]) }}"  class="btn chat-btn btn-block btn-submit" 
        id="whatsapp-close-ticket">{{ __('Ask For Close Ticket') }}</a>
</div>