<form action="{{ route('admin.webhook.update', $webhook->id) }}" method="post" class="needs-validation" novalidate>
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="module" class="form-label">{{ __('Module') }}</label>
        <select name="module" id="module" class="form-control" required>
            @foreach ($module as $key => $value)
                <option value="{{ $key }}" {{ $webhook->module == $key ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="url" class="form-label">{{ __('URL') }}</label>
        <input type="text" name="url" id="url" class="form-control" placeholder="{{ __('Enter Url') }}"
            value="{{ $webhook->url }}" required>
    </div>
    <div class="form-group">
        <label for="method" class="form-label">{{ __('Method') }}</label>
        <select name="method" id="method" class="form-control" required>
            @foreach ($method as $key => $value)
                <option value="{{ $key }}" {{ $webhook->method == $key ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('Close')}}</button>
        <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
    </div>
</form>
