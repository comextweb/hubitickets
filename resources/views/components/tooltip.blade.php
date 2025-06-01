@props(['description' => null])

@if($description)
    <i class="ti ti-info-circle text-info ms-1 align-baseline"
       data-bs-toggle="tooltip"
       data-bs-placement="top"
       title="{{ $description }}"
       aria-label="Información adicional"></i>
@endif