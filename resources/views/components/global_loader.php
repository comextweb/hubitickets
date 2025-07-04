<div id="globalLoader" class="global-loader" style="display: none;" >
    <div class="loader-content">
        <div class="loader"></div>
        <div class="loader-text" id="loaderText"></div>
    </div>
</div>

<style>
    .global-loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.5);
        z-index: 99999;
        display: flex;
        justify-content: center;
        align-items: center;
        backdrop-filter: blur(1px);
    }
    
    .loader-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
    }
    
    .loader-text {
        margin-top: 12px;
        color: white;
        font-weight: 500;
        font-size: 1.2rem;
    }
    
    /* Loader moderno */
    .loader {
        width: 50px;
        aspect-ratio: 1;
        display: grid;
    }
    .loader::before,
    .loader::after {    
        content:"";
        grid-area: 1/1;
        --c: no-repeat radial-gradient(farthest-side, #4f46e5 92%, #0000);
        background: 
            var(--c) 50%  0, 
            var(--c) 50%  100%, 
            var(--c) 100% 50%, 
            var(--c) 0    50%;
        background-size: 12px 12px;
        animation: l12 1s infinite;
    }
    .loader::before {
        margin: 4px;
        filter: hue-rotate(45deg);
        background-size: 8px 8px;
        animation-timing-function: linear;
    }

    @keyframes l12 { 
        100% { transform: rotate(.5turn); }
    }
</style>

<script>
    // Funciones globales accesibles desde PHP y JS
    window.showGlobalLoader = function(text = 'Cargando...') {
        const loader = document.getElementById('globalLoader');
        const loaderText = document.getElementById('loaderText');
        if (loaderText) loaderText.textContent = text;
        if (loader) loader.style.display = 'flex';
    };

    window.hideGlobalLoader = function() {
        const loader = document.getElementById('globalLoader');
        if (loader) loader.style.display = 'none';
    };
</script>