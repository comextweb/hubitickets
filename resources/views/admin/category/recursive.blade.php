<li>
    <div class="d-flex align-items-center justify-content-between gap-3 category-wrp">
        <div class="category-item">
            <span>{{ $category['name'] }}</span>
        </div>
        <div class="action-btn-wrp d-flex align-items-center gap-3">
            <div class="action-btn">
                <a href="#" class="bg-info btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip"
                    data-bs-placement="top" data-url="{{ route('admin.category.edit', $category['id']) }}"
                    data-ajax-popup="true" data-title="Edit Category" data-size="lg" title="Edit" aria-label="Edit">
                    <span class="text-white"><i class="ti ti-pencil"></i></span>
                </a>
            </div>
            <div class="action-btn">
                <form method="POST" action="{{ route('admin.category.destroy', $category['id']) }}">
                    @csrf
                    @method('DELETE')
                    <a class="bg-danger btn btn-sm align-items-center bs-pass-para show_confirm trigger--fire-modal-1"
                        data-bs-toggle="tooltip" data-confirm="Are You Sure?"
                        data-text="This action cannot be undone. Do you want to continue?"
                        data-confirm-yes="delete-form-{{ $category['id'] }}" title="Delete" aria-label="Delete">
                        <i class="ti ti-trash text-white"></i>
                    </a>
                </form>
            </div>
        </div>
    </div>

    {{-- Children Category called recursively --}}
    @if (!empty($category['children']))
        <ul class="sub">
            @foreach ($category['children'] as $child)
                @include('admin.category.recursive', ['category' => $child])
            @endforeach
        </ul>
    @endif
</li>
