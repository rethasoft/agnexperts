@extends('app.layouts.app')
@section('title', 'Keuringen')
@section('content')
    <div class="container-fluid px-4">
        <!-- Breadcrumb with modern shadow -->
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb" class="bg-white shadow-sm rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active fw-semibold" aria-current="page">Keuringen</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Alerts with improved styling -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($msg = Session::get('msg'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                {{ $msg }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Main content card with modern styling -->
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <!-- Header section with improved styling -->
                <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
                    <div style="width: 250px;">
                        <select class="form-select shadow-sm py-2 px-3 fw-medium" id="statusFilter">
                            <option value="">🔍 Alle Statussen</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <a href="{{ route($guard . '.inspections.create') }}" class="btn btn-primary shadow-sm py-2 fw-medium">
                        <i class="ri-add-line"></i> Nieuwe Keuring
                    </a>
                </div>

                <!-- Responsive table with modern styling -->
                <div class="table-responsive">
                    <table id="inspections-table" class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 py-3">Project nr</th>
                                <th class="border-0 py-3">Naam</th>
                                <th class="border-0 py-3">Adres</th>
                                <th class="border-0 py-3">Email</th>
                                <th class="border-0 py-3">Telefoon</th>
                                @auth('tenant')
                                    <th class="border-0 py-3">Prijs</th>
                                @endauth
                                <th class="border-0 py-3">Status</th>
                                @if (auth('tenant')->check() || auth('client')->check())
                                    <th class="border-0 py-3">Factuur</th>
                                @endif
                                <th class="border-0 py-3">Datum</th>
                                <th class="border-0 py-3 text-end">Acties</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($inspections->count() > 0)
                                @foreach ($inspections as $item)
                                    <tr>
                                        <td class="py-3">{{ $item->file_id }}</td>
                                        <td class="py-3">
                                            <a href="{{ route($guard . '.inspections.show', $item->id) }}"
                                                class="text-decoration-none fw-semibold text-primary">
                                                {{ $item->name }}
                                            </a>
                                        </td>
                                        <td class="py-3">{{ $item->street . ' ' . $item->postal_code }}</td>
                                        <td class="py-3">
                                            <a href="mailto:{{ $item->email }}" class="text-decoration-none text-dark">
                                                {{ $item->email }}
                                            </a>
                                        </td>
                                        <td class="py-3">{{ $item->phone }}</td>
                                        @auth('tenant')
                                            <td class="py-3 fw-semibold">{{ $item->formatted_total }}</td>
                                        @endauth
                                        <td class="py-3">
                                            @if ($item->status)
                                                <span class="badge rounded-pill fw-normal py-2 px-3"
                                                    style="background: {{ $item->status->color }}">{{ $item->status->name }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        @if (auth('tenant')->check() || auth('client')->check())
                                            <td class="py-3">
                                                @if ($item->invoice)
                                                    <span class="badge rounded-pill py-2 px-3"
                                                        style="background: {{ $item->invoice->status->color() }}">
                                                        {{ $item->invoice->status }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        @endif
                                        <td class="py-3">{{ date('Y-m-d', strtotime($item->created_at)) }}</td>
                                        <td class="py-3">
                                            <div class="btn-group shadow-sm">
                                                @auth('tenant')
                                                    <a href="{{ $item->invoice ? route('invoices.update-status', ['invoice' => $item->invoice->id]) : '#' }}"
                                                        onclick="{{ $item->invoice ? "return confirm('Weet u zeker dat u de status van deze keuring wilt bijwerken?')" : '' }}"
                                                        class="btn btn-sm {{ !$item->invoice ? 'btn-light text-muted disabled' : ($item->invoice->status->value === 'PAID' ? 'btn-success disabled' : 'btn-warning') }}"
                                                        style="border-radius: 6px 0 0 6px; transition: all 0.2s ease;"
                                                        title="{{ !$item->invoice ? 'Geen factuur beschikbaar' : ($item->invoice->status->value === 'PAID' ? 'Betaald' : 'Markeer als betaald') }}">
                                                        <i class="ri-check-line"></i>
                                                    </a>
                                                    <a href="{{ $item->invoice && $item->invoice->file ? $item->invoice->file->secure_url : '#' }}"
                                                        class="btn btn-sm {{ !$item->invoice || !$item->invoice->file ? 'btn-light text-muted disabled' : 'btn-danger' }}"
                                                        style="border-radius: 0; transition: all 0.2s ease;"
                                                        {{ $item->invoice && $item->invoice->file ? 'target="_blank"' : '' }}
                                                        title="{{ !$item->invoice || !$item->invoice->file ? 'Geen factuur beschikbaar' : 'Download PDF' }}">
                                                        <i class="ri-file-pdf-line"></i>
                                                    </a>
                                                @endauth
                                                @auth('client')
                                                    <a href="{{ route($guard . '.inspections.show', $item->id) }}"
                                                        class="btn btn-sm btn-primary"
                                                        style="border-radius: 0; transition: all 0.2s ease;">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
                                                @endauth
                                                <a href="{{ route($guard . '.inspections.edit', $item->id) }}"
                                                    class="btn btn-sm btn-info"
                                                    style="border-radius: 0; transition: all 0.2s ease;">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                <form action="{{ route($guard . '.inspections.destroy', $item->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        style="border-radius: 0 6px 6px 0; transition: all 0.2s ease;"
                                                        onclick="return confirm('Do you want to delete this record')">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusFilter = document.getElementById('statusFilter');
            const table = $('#inspections-table').DataTable({
                order: [
                    [0, 'desc']
                ],
                pageLength: 10,
                language: {
                    search: "Zoeken:",
                    lengthMenu: "Toon _MENU_ items per pagina",
                    info: "Toont _START_ tot _END_ van _TOTAL_ items",
                    paginate: {
                        first: "Eerste",
                        last: "Laatste",
                        next: "Volgende",
                        previous: "Vorige"
                    }
                }
            });

            statusFilter.addEventListener('change', function() {
                const selectedStatus = this.options[this.selectedIndex].text;
                table.search('').draw();
                if (selectedStatus !== '🔍 Alle Statussen') {
                    table.column(6).search(selectedStatus).draw();
                } else {
                    table.column(6).search('').draw();
                }
            });
        });
    </script>
@endpush
