  <div id="email-settings" class="card">
    <div class="card-header">
        <h5 class="mb-2">{{ __('Email Settings') }}</h5>
        <small>{{ __('Edit your Email settings') }}</small>
    </div>
    <form action="{{ route('admin.email.settings.store') }}" class="needs-validation" novalidate method="POST">
        @csrf
        <div class="card-body pb-0">
              <div class="row">
                  <div class="col-md-4">
                      <div class="form-group">
                          <label class="form-label">{{ __('Mail Driver') }}</label><x-required></x-required>
                          <input class="form-control" placeholder="{{ __('Mail Driver') }}" name="mail_driver"
                              type="text"
                              value="{{ isset($settings['mail_driver']) ? $settings['mail_driver'] : '' }}"
                              id="mail_driver" required>

                          @if ($errors->has('mail_driver'))
                              <div class="text-danger my-2">
                                  {{ $errors->first('mail_driver') }}
                              </div>
                          @endif
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label class="form-label">{{ __('Mail Host') }}</label><x-required></x-required>
                          <input class="form-control" placeholder="{{ __('Mail Host') }}" name="mail_host"
                              type="text" value="{{ isset($settings['mail_host']) ? $settings['mail_host'] : '' }}"
                              id="mail_host" required>
                          @if ($errors->has('mail_host'))
                              <div class="text-danger my-2">
                                  {{ $errors->first('mail_host') }}
                              </div>
                          @endif
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label class="form-label">{{ __('Mail Port') }}</label><x-required></x-required>
                          <input class="form-control" placeholder="{{ __('Mail Port') }}" name="mail_port"
                              type="text" value="{{ isset($settings['mail_port']) ? $settings['mail_port'] : '' }}"
                              id="mail_port" required>
                          @if ($errors->has('mail_port'))
                              <div class="text-danger my-2">
                                  {{ $errors->first('mail_port') }}
                              </div>
                          @endif
                      </div>
                  </div>
              </div>

              <div class="row">
                  <div class="col-md-4">
                      <div class="form-group">
                          <label class="form-label">{{ __('Mail Username') }}</label><x-required></x-required>
                          <input class="form-control" placeholder="{{ __('Mail Username') }}" name="mail_username"
                              type="text"
                              value="{{ isset($settings['mail_username']) ? $settings['mail_username'] : '' }}"
                              id="mail_username" required>
                          @if ($errors->has('mail_username'))
                              <div class="text-danger my-2">
                                  {{ $errors->first('mail_username') }}
                              </div>
                          @endif
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label class="form-label">{{ __('Mail Password') }}</label><x-required></x-required>
                          <input class="form-control" placeholder="{{ __('Mail Password') }}" name="mail_password"
                              type="text"
                              value="{{ isset($settings['mail_password']) ? $settings['mail_password'] : '' }}"
                              id="mail_password" required>
                          @if ($errors->has('mail_password'))
                              <div class="text-danger my-2">
                                  {{ $errors->first('mail_password') }}
                              </div>
                          @endif
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label class="form-label">{{ __('Mail Encryption') }}</label><x-required></x-required>
                          <input class="form-control" placeholder="{{ __('Mail Encryption') }}" name="mail_encryption"
                              type="text"
                              value="{{ isset($settings['mail_encryption']) ? $settings['mail_encryption'] : '' }}"
                              id="mail_encryption" required>
                          @if ($errors->has('mail_encryption'))
                              <div class="text-danger my-2">
                                  {{ $errors->first('mail_encryption') }}
                              </div>
                          @endif
                      </div>
                  </div>
              </div>

              <div class="row">
                  <div class="col-md-4">
                      <div class="form-group">
                          <label class="form-label">{{ __('Mail From Address') }}</label><x-required></x-required>
                          <input class="form-control" placeholder="{{ __('Mail From Address') }}"
                              name="mail_from_address" type="text"
                              value="{{ isset($settings['mail_from_address']) ? $settings['mail_from_address'] : '' }}"
                              id="mail_from_address" required>
                          @if ($errors->has('mail_from_address'))
                              <div class="text-danger my-2">
                                  {{ $errors->first('mail_from_address') }}
                              </div>
                          @endif
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label class="form-label">{{ __('Mail From Name') }}</label><x-required></x-required>
                          <input class="form-control" placeholder="{{ __('Mail From Name') }}" name="mail_from_name"
                              type="text"
                              value="{{ isset($settings['mail_from_name']) ? $settings['mail_from_name'] : '' }}"
                              id="mail_from_name" required>
                          @if ($errors->has('mail_from_name'))
                              <div class="text-danger my-2">
                                  {{ $errors->first('mail_from_name') }}
                              </div>
                          @endif
                      </div>
                  </div>
              </div>
        </div>
        <div class="card-footer d-flex justify-content-end pb-0">
            <div class="form-group me-2 mb-3">
                <a href="#" data-url="{{ route('admin.test.email') }}"
                    data-title="{{ __('Send Test Mail') }}" class="btn btn-primary send_email ">
                    {{ __('Send Test Mail') }}
                </a>
            </div>


            <div class="form-group mb-3">
                <button class="btn btn-primary" type="submit">{{ __('Save Changes') }}
                </button>
            </div>
        </div>
    </form>
  </div>
