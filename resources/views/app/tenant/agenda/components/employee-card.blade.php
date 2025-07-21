{{-- resources/views/app/tenant/agenda/components/employee-card.blade.php --}}
@php
    $gender = 'men';
    $photoId = rand(1, 99);
@endphp
<div class="list-group-item p-2" data-employee-id="{{ $id ?? 1 }}">
    <div class="d-flex align-items-center">
        <div class="form-check me-2">
            <input class="form-check-input employee-selector" type="checkbox" value="{{ $id ?? 1 }}"
                id="employee-{{ $id ?? 1 }}" data-employee-name="{{ $name ?? 'Jan Janssen' }}"
                data-employee-avatar="{{ $avatar ?? "https://randomuser.me/api/portraits/{$gender}/{$photoId}.jpg" }}">
        </div>
        <div class="avatar me-2">
            <img src="{{ $avatar ?? "https://randomuser.me/api/portraits/{$gender}/{$photoId}.jpg" }}"
                alt="{{ $name ?? 'Jan Janssen' }}" class="rounded-circle" width="36" height="36">
        </div>
        <div>
            <span class="fw-medium">{{ $name ?? 'Jan Janssen' }}</span>
            <div class="d-flex align-items-center">
                <span class="badge bg-primary me-1">{{ $tasks ?? 3 }}</span>
                <small class="text-muted">taken vandaag</small>
            </div>
        </div>
    </div>
</div>
