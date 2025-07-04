

<div class="{{ $divClass }}">
    <div class="form-group">
        <label for="{{ $id }}" class="form-label">{{ $label }}</label>
        @if($required) <span class="text-danger">*</span> @endif
        
        <div style="width: 100% !important;">
                    <input type="tel" 
               name="{{ $name }}" 
               value="{{ $value }}" 
               class="{{ $class }} phone-input {{ $errors->has($name) ? 'is-invalid' : '' }} w-100" 
               placeholder="{{ $placeholder }}" 
               id="{{ $id }}"
               {{ $required ? 'required' : '' }}>
        </div>
        <!-- Error message container outside the input wrapper -->
        <div id="{{ $id }}-error-container">
            @if($errors->has($name))
                <div class="invalid-feedback d-block">
                    {{ $errors->first($name) }}
                </div>
            @endif
        </div>
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cargar el script de intl-tel-input dinámicamente si no está ya cargado
    if (!window.intlTelInput) {
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js';
        script.onload = initPhoneInput;
        document.head.appendChild(script);
        
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css';
        document.head.appendChild(link);
    } else {
        initPhoneInput();
    }
    
    function initPhoneInput() {
        const input = document.getElementById('{{ $id }}');
        const errorContainer = document.getElementById('{{ $id }}-error-container');

        const iti = window.intlTelInput(input, {
            initialCountry: '{{ $defaultCountry }}',
            separateDialCode: true,
            preferredCountries: ['ec', 'us', 'co', 'pe', 'mx'], // Países prioritarios
            utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js'
        });
        const itiContainer = input.closest('.iti');
        if (itiContainer) {
            itiContainer.style.width = '100%';
            itiContainer.style.display = 'block';
        }

        
        // Si hay un valor inicial, configúralo
        @if($value)
            iti.setNumber('{{ $value }}');
        @endif
        
        // Manejar la validación del formulario
        const form = input.closest('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Actualizar el valor con el formato internacional completo
                input.value = iti.getNumber();
                
                // Validación personalizada
                // Validar formato del número si el campo tiene algo
                const isEmpty = input.value.trim() === '';
                const isValid = iti.isValidNumber();

                if (!isEmpty && !isValid) {
                    e.preventDefault();
                    input.classList.add('is-invalid');
                    
                    // Clear existing error and add new one to our container
                    errorContainer.innerHTML = '';
                    
                    const existingError = input.nextElementSibling;
                    if (existingError && existingError.classList.contains('invalid-feedback')) {
                        existingError.remove();
                    }

                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback d-block';
                    errorDiv.textContent = '{{ __("Please enter a valid phone number") }}';
                    errorContainer.appendChild(errorDiv);
                } else {
                    input.classList.remove('is-invalid');
                    errorContainer.innerHTML = '';
                }

               
            });
        }
    }
});
</script>
@endpush
@push('css')
<style>
.iti {
    width: 100% !important;
    display: block !important;
}
</style>
@endpush
