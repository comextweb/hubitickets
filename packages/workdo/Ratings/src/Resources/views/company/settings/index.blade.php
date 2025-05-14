<div class="card" id="ratings_sidenav">
    <form action="{{ route('ratings.setting.store') }}" enctype="multipart/form-data"
    class="needs-validation" novalidate method="post">
        @csrf
        <div class="card-header">
            <div class="d-flex align-items-center">
                <div class="col-10 ">
                    <h5 class="">{{ __('Ratings') }}</h5>
                    <small>{{ __('If the status is same in both the settings and the ticket, Ticket Review email will be sent.') }}</small>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-12">
                    <label class="form-label">{{ __('Ticket Status') }}</label>
                    <div class="row ms-1">
                        @foreach ($status as $key => $value)
                            <div class="form-check col-md-2 col-sm-2 col-6">
                                <input class="form-check-input currency_note" type="radio" name="ticket_status"
                                    value="{{ $value }}" @if (isset($settings['ticket_status']) && $settings['ticket_status'] == $value) checked @endif
                                    id="ticket_status_{{ $value }}">
                                <label class="form-check-label" for="ticket_status_{{ $value }}">
                                    {{ $value }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <input class="btn btn-print-invoice  btn-primary" type="submit" value="{{ __('Save Changes') }}">
        </div>
    </form>
</div>

