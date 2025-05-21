<div class="row">
    <div class="col-12">

        <div class="row">
            @if (isset($settings['is_enabled']) && $settings['is_enabled'] == 'on')
                <div class="float-end">
                    <a class="btn btn-primary btn-sm float-end ms-2" href="#" data-size="lg"
                        data-ajax-popup-over="true" data-url="{{ route('generate', ['faq']) }}" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="{{ __('Generate') }}"
                        data-title="{{ __('Generate Content with AI') }}"><i class="fas fa-robot me-1">
                            </i>{{ __('Generate with AI') }}</a>
                </div>
            @endif
        </div>

        <form method="POST" class="needs-validation" novalidate action="{{ route('admin.faq.update', $faq->id) }}">
            @csrf
            <div class="row">
                <div class="form-group col-12">
                    <label class="form-label">{{ __('Title') }}</label><x-required></x-required>
                    <div class="col-sm-12 col-md-12">
                        <input type="text" placeholder="{{ __('Title of the Faq') }}" name="title"
                            class="form-control" required value="{{ $faq->title }}" autofocus>

                    </div>
                </div>
                <div class="form-group col-12">
                    <label class="form-label">{{ __('Description') }}</label><x-required></x-required>
                    <div class="col-sm-12 col-md-12">
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" required cols="3" rows="4">{{ $faq->description }}</textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer p-0 pt-3">
                <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary" data-bs-dismiss="modal">
                <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
            </div>
        </form>
    </div>
</div>
