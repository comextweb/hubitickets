<form method="post" class="needs-validation" novalidate action="{{ route('admin.custom-field.store') }}">
    @csrf
    <div class="row">
        <div class="form-group col-md-12">
            <label name="name" class="form-label">{{__('Label')}}</label><x-required></x-required>
            <input type="text" class="form-control" name="name" placeholder="{{__('Enter Label')}}" required>
        </div>
        <div class="form-group col-md-12">
            <label name="placeholder" class="form-label">{{__('Placeholder')}}</label><x-required></x-required>
            <input type="text" class="form-control" name="placeholder" placeholder="{{__('Enter Placeholder')}}" required>
        </div>
        <div class="form-group col-md-12">
            <label name="type" class="form-label">{{__('Type')}}</label><x-required></x-required>
            <select class="form-control field_type"
                name="type">
                @foreach (\App\Models\CustomField::$fieldTypes as $key => $value)
                    <option value="{{ $key }}">{{ $value }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-12 repeater d-none">
            <div class="row">
                <div class="col-12">
                    <div class="row flex-grow-1">
                        <div class="col-md d-flex align-items-center col-6">
                        </div>

                        <div class="col-md-6 justify-content-between align-items-center col-6">
                            <div class="col-md-12 d-flex align-items-center  justify-content-end">
                                <a data-repeater-create="" class="btn btn-primary btn-sm add-row text-white"
                                    data-toggle="modal" data-target="#add-bank">
                                    <i class="ti ti-plus"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="" data-repeater-list="field_value">
                        <div class="row mt-3 ui-sortable flex-grow-1 repeater-field" data-repeater-item>
                            <div class="col-md-10 justify-content-between align-items-center col-10">
                                <input type="text" class="form-control name field_value" name="field_value" >
                            </div>
                            <div class="col-md-2 justify-content-between align-items-center col-2" data-repeater-delete>
                                <a href="#!"
                                    class="mx-3 btn btn-sm d-inline-flex align-items-center m-2 p-2 bg-danger desc_delete">
                                      <i class="ti ti-trash text-white" data-bs-toggle="tooltip"
                                          data-bs-original-title="{{ __('Delete') }}" ></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group col-md-12">
            <label name="width" class="form-label">{{__('Width')}}</label><x-required></x-required>
            <select class="form-control"
                name="width">
                @foreach (\App\Models\CustomField::$fieldWidth as $key => $value)
                    <option value="{{ $key }}">{{ $value }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label name="is_required" class="form-label">{{__('Required')}}</label><x-required></x-required>
                <select class="form-control"
                    name="is_required">
                    <option value="1">{{ __('Yes') }}</option>
                    <option value="0">{{ __('No') }}</option>
                </select>
            </div>
        </div>
    </div>

    <div class="modal-footer p-0 pt-3">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary" data-bs-dismiss="modal">
        <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
    </div>
</form>
