<div class="{{ $divClass }}">
    <div class="form-group">
        <label for="{{ $id }}" class="form-label">{{ $label }}</label>
        @if($required) <span class="text-danger">*</span> @endif
        <input type="text" name="{{ $name }}" value="{{ $value }}" 
               class="{{ $class }}" placeholder="{{ $placeholder }}" 
               pattern="^\+\d{1,3}\d{9,13}$" id="{{ $id }}" 
               {{ $required ? 'required' : '' }}>
        <div class="text-xs text-danger">
            {{ __('Please use with country code. (ex. +91)') }}
        </div>
    </div>
</div>
