<form action="{{ route('admin.webhook.store') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
    @csrf

    <div class="form-group">
        <label for="module" class="form-label">{{ __('Module') }}</label>
        <select name="module" id="module" class="form-control" required>
            @foreach ($module as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="url" class="form-label">{{ __('URL') }}</label>
        <input type="text" name="url" id="url" class="form-control" placeholder="{{ __('Enter Url') }}"
            required>
    </div>
    <div class="form-group">
        <label for="method" class="form-label">{{ __('Method') }}</label>
        <select name="method" id="method" class="form-control" required>
            @foreach ($method as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
    </div>
</form>
