<!-- [ Main Content ] end -->
<footer class="dash-footer">
    <div class="footer-wrapper text-center">
        <p class="mb-0 f-w-500">
            @if (isset($setting['footer_text']))
            {{ $setting['footer_text'] }}
            @else
            {{ __('Copyright') }} &copy; {{ config('app.name') }}
            @endif
        </p>
    </div>
</footer>