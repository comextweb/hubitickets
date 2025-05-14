@if(moduleIsActive('AutoReply'))
<div class="card" id="autoreply-sidenav">
    <form method="post" class="needs-validation" novalidate action="{{ route('autoreply.setting.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-sm-10 col-9">
                    <h5 class="">{{ __('Auto Reply Settings') }}</h5>
                    <small>{{ __('Enable Auto Reply Message button and set a message to be sent when a new ticket is created.')}}</small>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-sm-6">
                    <label class="form-label">{{ __('Enable Auto Reply Message') }}</label>
                    <input type="hidden" name="is_enable_auto_reply"  value="off" />
                    <div class="custom-control custom-switch">
                        <input type="checkbox" data-toggle="switchbutton"
                            data-onstyle="primary" class=""
                            name="is_enable_auto_reply" id="is_enable_auto_reply" value='on'
                            {{ isset($settings['is_enable_auto_reply']) && $settings['is_enable_auto_reply'] == 'on' ? 'checked="checked"' : '' }}>
                        <label class="custom-control-label"
                            for="is_enable_auto_reply"></label>
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    <label class="form-label ">{{ __('Message') }}</label> <br>
                    <input class="form-control" placeholder="{{ __('Enter Message') }}"
                        name="auto_reply_message" type="text"
                        value="{{ isset($settings['auto_reply_message']) ? $settings['auto_reply_message'] : '' }}"
                        id="auto_reply_message">
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <input class="btn btn-print-invoice  btn-primary" type="submit" value="{{ __('Save Changes') }}">
        </div>
    </form>
</div>
@endif