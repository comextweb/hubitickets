<!-- [ Main Content ] end -->
<footer class="dash-footer">
    <div class="footer-wrapper">
        <p class="text-muted mb-0">
            @if (isset($setting['footer_text']))
            {{ $setting['footer_text'] }}
            @else
            {{ __('Copyright') }} &copy; {{ config('app.name') }}
            @endif
        </p>
    </div>
</footer>