<form method="post" class="needs-validation" novalidate action="{{ route('admin.priority.update', $priority->id) }}">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="form-group col-md-10">
            <label class="form-label">{{ __('Name') }}</label><x-required></x-required>
            <input type="text" placeholder="{{ __('Name of the Category') }}" name="name"
                class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" required
                value="{{ $priority->name }}" autofocus>
            <div class="invalid-feedback">
                {{ $errors->first('name') }}
            </div>
        </div>

        <div class="form-group col-md-2">
            <label for="exampleColorInput" class="form-label">{{ __('Color') }}</label>
            <input name="color" type="color"
                class="w-100 form-control form-control-color {{ $errors->has('color') ? ' is-invalid' : '' }}"
                value="{{ $priority->color }}">
            <div class="invalid-feedback">
                {{ $errors->first('color') }}
            </div>
        </div>
    </div>

    <div class="modal-footer p-0 pt-3">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary" data-bs-dismiss="modal">
        <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
    </div>
</form>
