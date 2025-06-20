<div class="card" id="ratings_sidenav">
    <form action="{{ route('ratings.setting.store') }}" enctype="multipart/form-data" class="needs-validation"
        novalidate method="post">
        @csrf
        <div class="card-header p-3">
            <div class="d-flex align-items-center">
                <div class="col-10 ">
                    <h5 class="mb-1">{{ __('Rating Settings') }}</h5>
                    <small>{{ __('If the status is same in both the settings and the ticket, Ticket Review email will be sent.') }}</small>
                </div>
            </div>
        </div>
        <div class="card-body p-3">
                <div class="form-group mb-0">
                    <label class="form-label">{{ __('Ticket Status') }}</label>
                    <div class="row gy-2">
                        @foreach ($status as $key => $value)
                        <div class="col-lg-2 col-md-4 col-6">
                        <div class="form-check">
                            <input class="form-check-input currency_note" type="radio" name="ticket_status"
                                value="{{ $value }}" @if (isset($settings['ticket_status']) &&
                                $settings['ticket_status']==$value) checked @endif id="ticket_status_{{ $value }}">
                            <label class="form-check-label" for="ticket_status_{{ $value }}">
                                {{ $value }}
                            </label>
                        </div>
                        </div>
                        @endforeach
                    </div>
            </div>
        </div>
        <div class="card-footer text-end p-3">
            <input class="btn btn-print-invoice  btn-primary" type="submit" value="{{ __('Save Changes') }}">
        </div>
    </form>
</div>