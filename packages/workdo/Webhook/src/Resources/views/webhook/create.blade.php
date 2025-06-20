<form action="{{ route('webhook.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="form-group">
            <label class="form-label">{{ __('Module') }}</label>
            <select name="module" class="form-control" required>
                @foreach ($webhookModule as $key => $values)
                    <optgroup label="{{ ucfirst($key) }}">
                        @foreach ($values as $keys => $item)
                            <option value="{{ $keys }}">{{ $item }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">{{ __('Method') }}</label>
            <select name="method" class="form-select">
                <option value="GET">{{ __('GET') }}</option>
                <option value="POST">{{ __('POST') }}</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">{{ __('URL') }}</label>
            <input type="text" name="url" class="form-control" placeholder="{{ __('Enter Webhook Url Here') }}"
                required>
        </div>
    </div>
    <div class="modal-footer p-0 pt-3">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
    </div>
</form>
