
<div class="card" id="ticket-number-sidenav">
    <form method="post" class="needs-validation" novalidate action="{{ route('ticket.number.setting.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-10 ">
                    <h5 class="">{{ __('Ticket Number Settings') }}</h5>
                    <small>{{ __('This ticket prefix will appear in all places where ticket numbers are shown.') }}</small>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <label for="ticket_number_prefix" class="form-label">{{__('Ticket Number Prefix')}}</label>
                        <input type="text" id="ticket_number_prefix" name="ticket_number_prefix" class="form-control"
                            placeholder="Enter Ticket Number Prefix"
                            value="{{ !empty($settings['ticket_number_prefix']) ? $settings['ticket_number_prefix'] : '' }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <input class="btn btn-print-invoice  btn-primary" type="submit" value="{{ __('Save Changes') }}">
        </div>
    </form>
</div>





