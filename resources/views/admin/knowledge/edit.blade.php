<div class="row">
    <div class="col-12">
        <div class="row">
            @if (isset($settings['is_enabled']) && $settings['is_enabled'] == 'on')
                <div class="float-end">
                    <a class="btn btn-primary btn-sm float-end ms-2" href="#" data-size="lg"
                        data-ajax-popup-over="true" data-url="{{ route('generate', ['knowledge']) }}"
                        data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}"
                        data-title="{{ __('Generate Content with AI') }}"><i class="fas fa-robot me-1">
                           </i>{{ __('Generate with AI') }}</a>
                </div>
            @endif
        </div>
        <form method="POST" class="needs-validation" novalidate action="{{ route('admin.knowledge.update', $knowledge->id) }}">
            @csrf
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="form-label">{{ __('Title') }}</label><x-required></x-required>
                    <div class="col-sm-12 col-md-12">
                        <input type="text" placeholder="{{ __('Title of the Knowledge') }}" name="title"
                            class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}"
                            value="{{ $knowledge->title }}" required autofocus>

                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label class="form-label">{{ __('Category') }}</label>
                    <div class="col-sm-12 col-md-12">
                        <select class="form-select" name="category">
                            @foreach ($category as $cat)
                                <option value="{{ $cat->id }}" {{ $knowledge->category == $cat->id ? 'selected' : '' }}>{{ $cat->title }}</option>
                            @endforeach
                        </select>

                        <div class=" text-xs mt-1">
                            {{ __('Please add Knowledgebase category. ') }}<a
                                href="{{ route('admin.knowledgecategory') }}"><b>{{ __('Add Category') }}</b></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <label class="require form-label">{{ __('Description') }}</label><x-required></x-required>
                    <textarea name="description" id="description"
                        class="form-control summernote-simple {{ !empty($errors->first('description')) ? 'is-invalid' : '' }}" required>{!! $knowledge->description !!}</textarea>
                        <p class="text-danger summernote_text"></p>
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
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
