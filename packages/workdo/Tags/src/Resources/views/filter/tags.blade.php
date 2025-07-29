@if(!\Auth::user()->hasRole('customer'))
<div class="col-md-3 col-sm-6 col-12">
    <div class="btn-box">
    <label class="form-label text-dark">{{ __('Tags') }}</label>
        <select class="form-control" name="tags">
            <option value="">{{ __('Select Tags') }}</option>
            @foreach ($tags as $tag)
            <option value="{{ $tag->id }}" {{ request('tags') == $tag->id ? 'selected' : '' }}>{{ $tag->name }}</option>
            @endforeach
        </select>
    </div>
</div>
@endif