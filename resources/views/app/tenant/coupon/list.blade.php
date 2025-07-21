@extends('app.layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Coupons') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger mt-3">{{ $errors->first() }}</div>
                    @endif
                    @if ($msg = Session::get('success'))
                        <div class="alert alert-success mt-3">{{ $msg }}</div>
                    @endif
                    <div class="button-bar mb-2">
                        <a href="{{ route('coupon.create') }}" class="btn btn-success"><i class="ri-add-line"></i></a>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Discount Type</th>
                                <th>Discount Value</th>
                                <th>Max Uses</th>
                                <th>Used Count</th>
                                <th>Min Order Amount</th>
                                <th>Valid Period</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($coupons->count() > 0)
                                @foreach ($coupons as $coupon)
                                <tr>
                                    <td>{{ $coupon->code }}</td>
                                    <td>{{ $coupon->description }}</td>
                                    <td>{{ ucfirst($coupon->discount_type) }}</td>
                                    <td>{{ $coupon->discount_type === 'percentage' ? $coupon->discount_value . '%' : '€' . $coupon->discount_value }}</td>
                                    <td>{{ $coupon->max_uses ?? 'Unlimited' }}</td>
                                    <td>{{ $coupon->used_count }}</td>
                                    <td>{{ $coupon->min_order_amount ? '€' . $coupon->min_order_amount : 'None' }}</td>
                                    <td>
                                        {{ $coupon->starts_at ? date('Y-m-d', strtotime($coupon->starts_at)) : 'Any time' }}
                                        to
                                        {{ $coupon->expires_at ? date('Y-m-d', strtotime($coupon->expires_at)) : 'No expiry' }}
                                    </td>
                                    <td><span class="badge bg-{{ $coupon->is_active ? 'success' : 'danger' }}">{{ $coupon->is_active ? 'Active' : 'Inactive' }}</span></td>
                                    <td class="text-end">
                                        <form action="{{ route('coupon.edit', ['coupon' => $coupon->id]) }}" class="d-inline-block" method="GET">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success"><i class="ri-pencil-line"></i></button>
                                        </form>
                                        <form action="{{ route('coupon.destroy', ['coupon' => $coupon->id]) }}" class="d-inline-block" method="POST"
                                            onsubmit="if(!confirm('Do you want to delete this record')){return false;}"
                                            >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="ri-delete-bin-line"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                            <tr>
                                <td colspan="10">
                                    <div class="alert alert-danger mb-0">{{ __('validation.custom.no_data') }}</div>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
