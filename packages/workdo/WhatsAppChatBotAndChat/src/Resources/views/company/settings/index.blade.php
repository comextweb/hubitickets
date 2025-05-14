<div id="whatsappchatbot-settings" class="card">
    <div class="card-header">
        <h5>{{ __('WhatsApp Chatbot & Chat Settings') }}</h5>
        <small>{{ __('Edit your WhatsApp Chatbot & Chat Settings') }}</small>
    </div>
    <form action="{{ route('whatsappchatbot.setting.store') }}" class="needs-validation" novalidate method="POST">
        @csrf
            <div class="card-body pb-0">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label">{{ __('Webhook') }}</label><x-required></x-required>
                            <div class="input-group">
                                <input class="form-control" placeholder="{{ __('Access Token') }}"
                                    name="whatsapp_chatbot_access_token" type="text"
                                    value="{{ route('whatsapp.webhook') }}" id="webhookInput" readonly>
                                <button class="btn btn-primary" type="button" onclick="copyWebhookLink()">
                                    {{ __('Copy Link') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('Phone Number Id') }}</label><x-required></x-required>
                            <input class="form-control" placeholder="{{ __('Phone Number Id') }}"
                                name="whatsapp_chatbot_phone_number_id" type="text"
                                value="{{ isset($settings['whatsapp_chatbot_phone_number_id']) ? $settings['whatsapp_chatbot_phone_number_id'] : '' }}"
                                id="whatsapp_chatbot_phone_number_id" required>

                            @if ($errors->has('whatsapp_chatbot_phone_number_id'))
                                <div class="text-danger my-2">
                                    {{ $errors->first('whatsapp_chatbot_phone_number_id') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('Phone Number') }}</label><x-required></x-required>
                            <input class="form-control" placeholder="{{ __('Phone Number') }}"
                                name="whatsapp_chatbot_phone_number" type="text"
                                value="{{ isset($settings['whatsapp_chatbot_phone_number']) ? $settings['whatsapp_chatbot_phone_number'] : '' }}"
                                id="whatsapp_chatbot_phone_number" required>
                            <div class=" text-xs text-danger">
                                {{ __('Please use with country code. (ex. +91)') }}
                            </div>
                            @if ($errors->has('whatsapp_chatbot_phone_number'))
                                <div class="text-danger my-2">
                                    {{ $errors->first('whatsapp_chatbot_phone_number') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label">{{ __('Access Token') }}</label><x-required></x-required>
                            <input class="form-control" placeholder="{{ __('Access Token') }}"
                                name="whatsapp_chatbot_access_token" type="text"
                                value="{{ isset($settings['whatsapp_chatbot_access_token']) ? $settings['whatsapp_chatbot_access_token'] : '' }}"
                                id="whatsapp_chatbot_access_token" required>
                            @if ($errors->has('whatsapp_chatbot_access_token'))
                                <div class="text-danger my-2">
                                    {{ $errors->first('whatsapp_chatbot_access_token') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end pb-0">
                <div class="form-group mb-3">
                    <button class="btn btn-primary" type="submit">{{ __('Save Changes') }}
                    </button>
                </div>
            </div>
    </form>
</div>

@push('scripts')
    <script>
        function copyWebhookLink() {
            var copyText = document.getElementById("webhookInput");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");
            show_toastr('Success', '{{ __('Link Copy on Clipboard') }}', 'success')

        }
    </script>
@endpush
