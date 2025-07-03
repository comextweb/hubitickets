<div class="modal-body">
    <form method="post" class="needs-validation" novalidate id="variablesForm" action="{{ route('update.email.variables', $templateLang->id) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label class="form-label">{{ __('Variables Configuration') }}</label>
            <textarea name="variables" class="form-control {{ $errors->has('variables') ? ' is-invalid' : '' }}" rows="12" style="font-family: monospace;" required>@json(json_decode($templateLang->variables), JSON_PRETTY_PRINT)</textarea>
            <div class="invalid-feedback">
                {{ $errors->first('variables') }}
            </div>
            <small class="text-muted">{{ __('Edit the JSON directly to modify variables') }}</small>
        </div>

        <div class="modal-footer p-0 pt-3">
            <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary" data-bs-dismiss="modal">
            <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
        </div>
    </form>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Validación del formulario
    $('#variablesForm').on('submit', function(e) {
        e.preventDefault();
        
        try {
            // Validar que sea JSON válido
            JSON.parse($('textarea[name="variables"]').val());
            
            // Enviar el formulario si es válido
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if(response.success) {
                        show_toastr('Success', response.success, 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                },
                error: function(xhr) {
                    show_toastr('Error', xhr.responseJSON.error || '{{ __("Unknown error") }}', 'error');
                }
            });
        } catch (err) {
            // Mostrar error si el JSON no es válido
            $('textarea[name="variables"]').addClass('is-invalid');
            $('.invalid-feedback').text('{{ __("Invalid JSON format") }}: ' + err.message);
        }
    });
});
</script>
@endpush