<div class="auth-footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-6">
                <span>
                    @if (isset($settings['footer_text']))
                        {{ $settings['footer_text'] }}
                    @else
                        {{ __('Copyright') }} &copy; {{ config('app.name') }}
                    @endif
                </span>
            </div>
            <div class="col-6 text-end">
            </div>
        </div>
    </div>
</div>
