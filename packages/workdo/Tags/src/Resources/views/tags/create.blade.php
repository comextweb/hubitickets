<form action="{{ route('tags.store') }}" method="POST" class="needs-validation" novalidate>
    @csrf
    <div class="modal-body p-0">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="name" class="form-label">{{ __('Name') }}</label>
                    <input type="text" name="name" class="form-control"
                        placeholder="{{ __('Enter Name') }}" required>
                    @error('name')
                        <small class="invalid-name" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </small>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <label for="color" class="form-label">{{ __('Color') }}</label>
                <input type="color" name="color" class="form-control background-color color-picker-btn">
            </div>
        </div>
    </div>
    <div class="modal-footer p-0 pt-3">
        <input type="button" value="{{ __('Cancel') }}" class="btn  btn-secondary" data-bs-dismiss="modal">
        <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">
    </div>
</form>
