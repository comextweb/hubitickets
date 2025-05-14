<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="form-group col-md-6">
                <label class="form-label">{{ __('Title') }}</label>
                <div class="col-sm-12 col-md-12">
                    <input type="text" name="title" class="form-control" value="{{ $knowledge->title }}" disabled>
                </div>
            </div>

            <div class="form-group col-md-6">
                <label class="form-label">{{ __('Category') }}</label>
                <div class="col-sm-12 col-md-12">
                    <input type="text" name="category" class="form-control" value="{{isset($knowledge->getCategoryInfo) ? $knowledge->getCategoryInfo->title : ''}}" disabled>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12 mb-0">
                <label class="require form-label">{{ __('Description') }}</label>
                <textarea name="description" id="description" class="form-control summernote-simple">{{ $knowledge['description'] }}</textarea>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12 mb-0">
                <!-- <label class="form-label"></label> -->
                <div class="col-sm-12 col-md-12 text-end">
                    <button class="btn btn-secondary btn-block btn-submit"
                        data-bs-dismiss="modal"><span>{{ __('Close') }}</span></button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>
<script>
    if ($(".summernote-simple").length > 0) {
        $('.summernote-simple').summernote({
            dialogsInBody: !0,
            minHeight: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough']],
                ['list', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'unlink']],
            ],
            height: 250,
        });

        $('.summernote-simple').summernote('disable');

    }
</script>
