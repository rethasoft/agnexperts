@extends('app.layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item"><a href="{{ route('setting.index') }}">Instellingen</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Nieuw</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<form action="{{ route('setting.store') }}" method="POST" enctype="multipart/form-data">
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
                            <label for="company" class="form-label">Bedrijfsnaam</label>
                            <input type="text" id="company" name="data[company]" class="form-control"
                                autocomplete="off" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <label for="name" class="form-label">Naam</label>
                            <input type="text" id="name" name="data[name]" class="form-control"
                                autocomplete="off" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <label for="surname" class="form-label">Voornaam</label>
                            <input type="text" id="surname" name="data[surname]" class="form-control"
                                autocomplete="off" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="data[email]" class="form-control"
                                autocomplete="off" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <label for="password" class="form-label">Wachtwoord</label>
                            <input type="text" id="password" name="password" class="form-control"
                                autocomplete="off" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" id="phone" name="data[phone]" class="form-control"
                                autocomplete="off" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <label for="logo" class="form-label">Logo</label>
                            <input type="file" name="logo" id="logo">
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