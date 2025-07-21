@extends('app.layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item"><a href="{{ route('coupon.index') }}">{{ __('Coupons') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Bewerk</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <form action="{{ route('coupon.update', ['coupon' => $coupon->id]) }}" method="POST">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="alert alert-danger mt-3">{{ $errors->first() }}</div>
        @endif
        @if ($msg = Session::get('msg'))
            <div class="alert alert-success mt-3">{{ $msg }}</div>
        @endif
        <div class="row mt-3">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        Inhoud
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="code" class="form-label">Kortingscode</label>
                            <input type="text" id="code" name="data[code]" class="form-control"
                                autocomplete="off" required value="{{ $coupon->code }}" readonly>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">Omschrijving</label>
                            <textarea id="description" name="data[description]" class="form-control">{{ $coupon->description }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="discount_type" class="form-label">Kortingstype</label>
                            <select id="discount_type" name="data[discount_type]" class="form-control" required>
                                <option value="percentage" {{ $coupon->discount_type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                <option value="fixed" {{ $coupon->discount_type == 'fixed' ? 'selected' : '' }}>Vast bedrag</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="discount_value" class="form-label">Kortingswaarde</label>
                            <input type="number" id="discount_value" name="data[discount_value]" class="form-control" 
                                min="0" required value="{{ $coupon->discount_value }}">
                        </div>

                        <div class="mb-4">
                            <label for="max_uses" class="form-label">Maximum aantal gebruiken</label>
                            <input type="number" id="max_uses" name="data[max_uses]" class="form-control"
                                min="1" value="{{ $coupon->max_uses }}">
                        </div>

                        <div class="mb-4">
                            <label for="min_order_amount" class="form-label">Minimaal orderbedrag</label>
                            <input type="number" id="min_order_amount" name="data[min_order_amount]" class="form-control"
                                min="1" value="{{ $coupon->min_order_amount }}">
                        </div>

                        <div class="mb-4">
                            <label for="starts_at" class="form-label">Startdatum</label>
                            <input type="date" id="starts_at" name="data[starts_at]" class="form-control" 
                                value="{{ $coupon->starts_at ? date('Y-m-d', strtotime($coupon->starts_at)) : '' }}">
                        </div>

                        <div class="mb-4">
                            <label for="expires_at" class="form-label">Vervaldatum</label>
                            <input type="date" id="expires_at" name="data[expires_at]" class="form-control"
                                value="{{ $coupon->expires_at ? date('Y-m-d', strtotime($coupon->expires_at)) : '' }}">
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input type="hidden" name="data[is_active]" value="0">
                                <input type="checkbox" id="is_active" name="data[is_active]" class="form-check-input" 
                                    {{ $coupon->is_active ? 'checked' : '' }} value="1">
                                <label for="is_active" class="form-check-label">Actief</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <button class="btn btn-success" type="submit">Opslaan</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
