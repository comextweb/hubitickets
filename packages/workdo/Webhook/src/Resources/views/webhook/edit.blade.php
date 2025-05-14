<form action="{{ route('webhook.update', $webhook->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="form-group">
            <label class="form-label">{{ __('Module') }}</label>
            <select name="module" class="form-control" required>
                @foreach ($webhookModule as $key => $values)
                    <optgroup label="{{ ucfirst($key) }}">
                        @foreach ($values as $keys => $item)
                            <option value="{{ $keys }}" {{ $webhook->action == $keys ? 'selected' : '' }}>{{ $item }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">{{ __('Method') }}</label>
            <select name="method" class="form-control select">
                @foreach ($methods as $key => $value)
                    <option value="{{ $key }}" {{ $webhook->method == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">{{ __('URL') }}</label>
            <input type="text" name="url" class="form-control" value="{{ $webhook->url }}" placeholder="{{ __('Enter Webhook Url Here') }}" required>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
    </div>
</form>
