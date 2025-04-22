<!-- [ Main Content ] end -->
<footer class="dash-footer">
    <div class="footer-wrapper">
        <div class="py-1">
            <p class="text-muted">
                @if (isset($setting['footer_text']))
                    {{ $setting['footer_text'] }}
                @else
                    {{ __('Copyright') }} &copy; {{ config('app.name') }}
                @endif
            </p>
        </div>
        <div class="py-1">
            <ul class="list-inline m-0">

            </ul>
        </div>
    </div>
</footer>
