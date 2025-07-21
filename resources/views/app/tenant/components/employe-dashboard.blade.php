<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 mt-4 gy-3">
    <div class="col mb-3 mb-md-0">
        <div class="card">
            <div class="card-header">Totaal aantal keuringen</div>
            <div class="card-body d-flex align-items-center justify-content-between">
                <h1>{{ $getTotal }}</h1>
                <i class="fs-1 ri-file-text-line"></i>
            </div>
        </div>
    </div>

    @if (is_array($statuses) && count($statuses) > 0 )
        @foreach ($statuses as $status_key => $status)
        <div class="col mb-3 mb-md-0">
            <div class="card" style="background:{{ $status['color'] }}">
                <div class="card-header">{{ ucwords($status_key) }}</div>
                <div class="card-body d-flex align-items-center justify-content-between">
                    <h1>{{ $status['count'] }}</h1>
                    <i class="fs-1 ri-file-text-line"></i>
                </div>
            </div>
        </div>            
        @endforeach
    @endif

</div>