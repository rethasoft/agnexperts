@extends('app.layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item"><a href="{{ route('status.index') }}">{{ ucwords(Request::segment(2)) }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Nieuw</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<form action="{{ route('status.store') }}" method="POST">
    @csrf

    @if ($errors->any())
        <div class="alert alert-danger mt-3">{{ $errors->first() }}</div>
    @endif
    @if ($msg = Session::get('msg'))
        <div class="alert alert-success mt-3">{{ $msg }}</div>
    @endif
    <div class="row mt-3">
        <div class="col-lg-4 mb-3 mb-md-0">
            <div class="card">
                <div class="card-header">
                    Inhoud
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <label for="username" class="form-label">Naam</label>
                            <input type="text" id="username" name="data[name]" class="form-control"
                                autocomplete="off" required>
                        </div>
                    </div>
                   
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <label for="color" class="form-label">Color</label>
                            <input type="color" id="color" name="data[color]" class="form-control"
                                autocomplete="off" required>
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