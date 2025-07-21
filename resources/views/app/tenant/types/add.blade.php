@extends('app.layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item"><a href="{{ route('dienst.index') }}">Dienst</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Nieuw</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <form action="{{ route('dienst.store') }}" method="POST">
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
                                <label for="name" class="form-label">Categorie</label>
                                <select name="data[category_id]" id="" class="form-control">
                                    <option value="0"> Selecteren </option>
                                    @if ($types)
                                        @foreach ($types as $type)
                                            <option value="{{ $type->id }}">{{ $type->short_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
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
                                <label for="short_name" class="form-label">Korte Naam</label>
                                <input type="text" id="short_name" name="data[short_name]" class="form-control"
                                    autocomplete="off" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <label for="price" class="form-label">Prijs</label>
                                <input type="text" id="price" name="data[price]" class="form-control"
                                    autocomplete="off" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="form-check form-check-inline">
                                    <input type="hidden" name="data[extra]" id="" value="0" />
                                    <input class="form-check-input" type="checkbox" name="data[extra]" data-target="#extra-price" value="1" onclick="toggleExtra(this)">
                                    <label class="form-check-label" for="">Extra</label>
                                </div>
                            </div>
                        </div>
                        <div id="extra-price" class="row mb-3 d-none">
                            <div class="col-lg-12">
                                <input type="hidden" name="data[extra_price]" value="0">
                                <label for="extra_price" class="form-label">Extra Prijs</label>
                                <input type="text" id="extra_price" name="data[extra_price]" class="form-control" autocomplete="off" value="1">
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
