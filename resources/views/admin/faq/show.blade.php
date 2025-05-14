<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="form-group col-12">
                <label class="form-label">{{ __('Title') }}</label>
                <div class="col-sm-12 col-md-12">
                    <input type="text" name="title" class="form-control" value="{{$faq['title']}}" disabled>
                </div>
            </div>
            <div class="form-group col-12">
                <label class="form-label">{{ __('Description') }}</label>
                <div class="col-sm-12 col-md-12">
                    <textarea name="description" class="form-control" placeholder="{{ __('Enter Description') }}" cols="10"
                        rows="5" disabled>{{ $faq['description'] }}</textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12 mb-0">
                <!-- <label class="form-label"></label> -->
                <div class="col-sm-12 col-md-12 text-end">
                    <button class="btn btn-secondary btn-block btn-submit" data-bs-dismiss="modal"><span>{{ __('Close') }}</span></button>
                </div>
            </div>
        </div>
    </div>
</div>
