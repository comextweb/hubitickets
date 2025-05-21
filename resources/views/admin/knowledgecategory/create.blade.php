<div class="row">
    <div class="col-12">
        <div class="row">
            @if (isset($settings['is_enabled']) && $settings['is_enabled'] == 'on')
                <div class="float-end">
                    <a class="btn btn-primary btn-sm float-end ms-2" href="#" data-size="lg"
                        data-ajax-popup-over="true" data-url="{{ route('generate', ['knowledge_category']) }}"
                        data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}"
                        data-title="{{ __('Generate Content with AI') }}"><i class="fas fa-robot me-1">
                            </i>{{ __('Generate with AI') }}</a>
                </div>
            @endif
        </div>
        <form method="POST" class="needs-validation" novalidate
            action="{{ route('admin.knowledgecategory.store') }}">
            @csrf
            <div class="row">
                <div class="form-group col-12">
                    <label class="form-label">{{ __('Title') }}</label><x-required></x-required>
                    <div class="col-sm-12 col-md-12">
                        <input type="text" placeholder="{{ __('Title of the Knowledge') }}" name="title" class="form-control" required autofocus>
                    </div>
                </div>
            </div>
            <div class="modal-footer p-0 pt-3">
                <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary" data-bs-dismiss="modal">
                <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
            </div>
        </form>
    </div>
</div>
