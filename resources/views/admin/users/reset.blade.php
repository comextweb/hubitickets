<form action="{{ route('user.password.update', $user->id) }}" class="needs-validation" method="POST" enctype="multipart/form-data"
        novalidate>
        @csrf
    <div class="row">
        <div class="form-group col-md-12">
            <label class="form-label" for="password">{{ __('Password') }}</label><x-required></x-required>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                required autocomplete="new-password">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-12">
            <label class="form-label" for="password_confirmation">{{ __('Confirm Password') }}</label><x-required></x-required>
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required
                autocomplete="new-password">
        </div>
    </div>

    <div class="modal-footer p-0 pt-3">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary" data-bs-dismiss="modal">
        <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
    </div>

</form>



