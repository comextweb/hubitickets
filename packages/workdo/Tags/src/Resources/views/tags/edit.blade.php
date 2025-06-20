<div class=" bg-none card-box">
    <form action="{{ route('tags.update',$tag->id) }}" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-body p-0">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="name" class="form-label">{{ __('Name') }}</label><x-required></x-required>
                    <input type="text" name="name" class="form-control" value="{{$tag->name ?? ''}}"
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
                    <input type="color" name="color" value="{{ $tag->color ?? ''}}" class="form-control background-color color-picker-btn">
                </div>
            </div>
        </div>
        <div class="modal-footer p-0 pt-3">
            <input type="button" value="{{ __('Cancel') }}" class="btn  btn-secondary" data-bs-dismiss="modal">
            <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">
        </div>
    </form>
    </div>
