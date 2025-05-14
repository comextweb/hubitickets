@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Custom Field') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Custom Field') }}</li>
@endsection

@section('multiple-action-button')
    @permission('user create')
        <a href="#" class="btn btn-sm btn-primary btn-icon" title="{{ __('Create') }}" data-bs-toggle="tooltip"
            data-bs-placement="top" data-ajax-popup="true" data-title="{{ __('Create Custom Field') }}"
            data-url="{{ route('admin.custom-field.create') }}" data-size="md"><i class="ti ti-plus"></i></a>
    @endpermission
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table id="pc-dt-simple" class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th>{{ __('Labels') }}</th>
                                    <th>{{ __('Placeholder') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Require') }}</th>
                                    <th>{{ __('Width') }}</th>
                                    <th class="text-end me-3">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="sortable">
                                @foreach ($customFields as $index => $customField)
                                    <tr data-id="{{ $customField->id }}">
                                        <td scope="row">
                                            <i class="ti ti-arrows-maximize sort-handler ui-sortable-handle"></i>
                                        </td>
                                        <td>{{ $customField->name }}</td>
                                        <td>{{ $customField->placeholder }}</td>
                                        <td>{{ $customField->type }}</td>
                                        @if ($customField->is_required == 1)
                                            <td><span
                                                    class="badge bg-success p-2 px-3 status-badge7">{{ __('Required') }}</span>
                                            </td>
                                        @else
                                            <td><span
                                                    class="badge bg-danger p-2 px-3 status-badge7">{{ __('Not Required') }}</span>
                                            </td>
                                        @endif
                                        <td>{{ $customField->width }}</td>
                                        <td class="text-end me-3">
                                            @permission('faq edit')
                                                <div class="action-btn me-2">
                                                    <a href="#" class="btn btn-sm btn-icon bg-info text-white"
                                                        title="{{ __('Edit Custom Field') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" data-ajax-popup="true"
                                                        data-title="{{ __('Edit Custom Field') }}"
                                                        data-url="{{ route('admin.custom-field.edit', $customField->id) }}"
                                                        data-size="md"><i class="ti ti-pencil"></i></a>
                                                </div>
                                            @endpermission
                                            @permission('user delete')
                                                @if ($customField->id > 6)
                                                    <div class="action-btn me-2">
                                                        <form method="POST"
                                                            action="{{ route('admin.custom-field.destroy', $customField->id) }}"
                                                            id="delete-form-{{ $customField->id }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input name="_method" type="hidden" value="DELETE">

                                                            <a class="mx-3 bg-danger btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Delete" aria-label="Delete"
                                                                data-confirm="{{ __('Are You Sure?') }}"
                                                                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                data-confirm-yes="delete-form-{{ $customField->id }}"><i
                                                                    class="ti ti-trash text-white text-white"></i></a>
                                                        </form>
                                                    </div>
                                                @endif
                                            @endpermission
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="note mt-2 d-flex">
                            <p class="mb-0"><b>Note : </b></p> <span class="text-danger ms-2"> You Can Drag & Drop the Custom
                                Fields.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script>
        $(function() {
            $(".sortable").sortable();
            $(".sortable").disableSelection();
            $(".sortable").sortable({
                items: "tr", // Only make table rows draggable
                handle: "td", // Only allow dragging from the handle icon
                helper: function(e, ui) {
                    ui.children().each(function() {
                        $(this).width($(this).width()); // Preserve width of each cell
                    });
                    return ui;
                },
                placeholder: "sortable-placeholder",
                stop: function() {
                    var order = [];
                    $(this).find('tr').each(function(index, data) {
                        order[index] = $(data).attr('data-id');
                    });

                    $.ajax({
                        url: "{{ route('admin.custom-field.order') }}",
                        data: {
                            order: order,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        success: function(data) {
                            show_toastr('{{ __('Success') }}', data.message, 'success');
                        },
                        error: function(data) {
                            data = data.responseJSON;
                            show_toastr('{{ __('Error') }}', data.message, 'error');
                        }
                    })
                }
            });
        });
    </script>
    <script src="{{ asset('js/repeater.js') }}"></script>

    <script>
        $(document).on('click', '.field_type', function() {
            var type = $(this).val();
            if (type == 'select' || type == 'checkbox' || type == 'radio') {
                $('.repeater').removeClass('d-none');
                $('.repeater .field_value').attr('required', 'required');
            } else {
                $('.repeater').addClass('d-none');
                $('.repeater .field_value').removeAttr('required');
            }
        });
    </script>

    <script>
        $('#commonModal').on('shown.bs.modal', function() {
            var selector = "body";

            if ($(selector + " .repeater").length) {
                var $dragAndDrop = $("body .repeater .repeater-field").sortable({
                    handle: '.sort-handler'
                });
                var $repeater = $(selector + ' .repeater').repeater({
                    initEmpty: false,
                    defaultValues: {
                        'status': 1
                    },
                    show: function() {
                        $(this).slideDown();
                        $(this).find('.add-row').remove();
                    },
                    hide: function(deleteElement) {
                        $(this).remove();
                    },
                    ready: function(setIndexes) {
                        $dragAndDrop.on('drop', setIndexes);
                    },
                    isFirstItemUndeletable: true
                });

                var value = $(selector + " .repeater").attr('data-value');
                if (typeof value != 'undefined' && value.length != 0) {
                    value = JSON.parse(value);
                    $repeater.setList(value);
                }
            }
        });
    </script>
@endpush
