<form method="post" class="needs-validation" novalidate action="{{ route('admin.category.update', $category->id) }}">
    @csrf
    @method('PUT')
    <div class="row">
        @if (isset($settings['is_enabled']) && $settings['is_enabled'] == 'on')
            <div class="float-end" style="margin-bottom: 15px">
                <a class="btn btn-primary btn-sm" href="#" data-size="md" data-ajax-popup-over="true"
                    data-url="{{ route('generate', ['category']) }}" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="{{ __('Generate') }}" data-title="{{ __('Generate Content with AI') }}"><i
                        class="fas fa-robot me-1"> </i>{{ __('Generate with AI') }}</a>
            </div>
        @endif
        <div class="form-group col-md-5">
            <label class="form-label">{{ __('Name') }}</label><x-required></x-required>
            <input type="text" placeholder="{{ __('Enter Category Name') }}" name="category_name"
                class="form-control {{ $errors->has('category_name') ? ' is-invalid' : '' }}" required
                value="{{ $category->name }}" autofocus>
        </div>

        <div class="form-group col-md-5">
            <label class="form-label">{{ __('Parent Category') }}</label>
            <select class="form-select" id="parent_id" name="parent_id">
                <option value="">{{ __('Select Parent') }}</option>
                @foreach ($categoryTree as $cate)
                    <option value="{{ $cate['id'] }}" {{ $category->parent_id == $cate['id'] ? 'selected' : '' }}
                        {{ $category->parent_id == 0 && $category->id == $cate['id'] ? 'disabled' : '' }}
                        {{ $category->id == $cate['id'] ? 'disabled' : '' }}>{!! $cate['name'] !!}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-md-2">
            <label for="exampleColorInput" class="form-label">{{ __('Color') }}</label>
            <input name="color" type="color"
                class="form-control form-control-color {{ $errors->has('color') ? ' is-invalid' : '' }}"
                value="{{ $category->color }}">
            <div class="invalid-feedback">
                {{ $errors->first('color') }}
            </div>
        </div>
    </div>
    <div class="modal-footer p-0 pt-3">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary" data-bs-dismiss="modal">
        <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
    </div>
</form>

<script src="{{ asset('public/libs/select2/dist/js/select2.min.js') }}"></script>

<script>
    if ($(".multi-select").length > 0) {
        $($(".multi-select")).each(function(index, element) {
            var id = $(element).attr('id');
            var multipleCancelButton = new Choices(
                '#' + id, {
                    removeItemButton: true,
                }
            );
        });

    }
    if ($(".select2").length) {
        $('.select2').select2({
            "language": {
                "noResults": function() {
                    return "No result found";
                }
            },
        });
    }
</script>
