@extends('app.layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item"><a href="{{ route('service.index') }}">{{ __('Diensten') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Nieuw</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <form action="{{ route('service.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

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
                                <label for="name" class="form-label">Naam</label>
                                <input type="text" id="name" name="data[name]" class="form-control"
                                    autocomplete="off" required>
                            </div>

                            <div class="mb-4">
                                <label for="short_description" class="form-label">Korte Beschrijving</label>
                                <input type="text" id="short_description" name="data[short_description]" class="form-control"
                                    autocomplete="off">
                            </div>

                            <div class="mb-4">
                                <label for="description" class="form-label">Beschrijving</label>
                                <textarea id="editor" name="data[description]" class="form-control"></textarea>
                            </div>

                            <div class="mb-4">
                                <label for="image" class="form-label">Afbeelding</label>
                                <input type="file" id="image" name="image" class="form-control"
                                    accept="image/*">
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Regio's</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="regions[]" value="brussel" id="region_brussel">
                                    <label class="form-check-label" for="region_brussel">
                                        Brussel
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="regions[]" value="vlaanderen" id="region_vlaanderen">
                                    <label class="form-check-label" for="region_vlaanderen">
                                        Vlaanderen
                                    </label>
                                </div>
                                <small class="form-text text-muted">Selecteer de regio's waar deze dienst beschikbaar is.</small>
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
