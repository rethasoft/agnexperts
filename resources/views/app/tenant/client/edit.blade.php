@extends('app.layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item"><a href="{{ route('client.index') }}">Client</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Bewerk</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <form action="{{ route('client.update', ['client' => $client->id]) }}" method="POST">
        @csrf
        @method('PUT')

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
                                    autocomplete="off" required value="{{ $client->name }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <label for="surname" class="form-label">Voornaam</label>
                                <input type="text" id="surname" name="data[surname]" class="form-control"
                                    autocomplete="off" required value="{{ $client->surname }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="data[email]" class="form-control"
                                    autocomplete="off" required value="{{ $client->email }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <label for="password" class="form-label">Wachtwoord</label>
                                <input type="text" id="password" name="data[password]" class="form-control"
                                    autocomplete="off">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <label for="phone" class="form-label">Telefoon</label>
                                <input type="text" id="phone" name="data[phone]" class="form-control"
                                    autocomplete="off" required value="{{ $client->phone }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" id="address" name="data[address]" class="form-control"
                                    autocomplete="off" required value="{{ $client->address }}">
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
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        Prijslijst
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Dienst</th>
                                        <th scope="col">Prijs</th>
                                        <th scope="col">Niuewe Prijs</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($types->count() > 0)
                                        @foreach ($types as $type)
                                            @if ($type->subTypes->count() > 0)
                                                @foreach ($type->subTypes as $subType)
                                                    <tr class="">
                                                        <td scope="row">{{ $type->short_name . ' > ' . $subType->name }}</td>
                                                        <td>{{ $subType->price }}</td>
                                                        <td>
                                                            <input type="hidden" name="pricelist[{{ $type->id * $subType->id }}][type_id]" style="width:80px;text-align:center;" value="{{ $subType->id }}">
                                                            <input type="text" name="pricelist[{{ $type->id * $subType->id }}][price]" style="width:80px;text-align:center;"
                                                            value="{{ isset($prices[$subType->id]) ? $prices[$subType->id]['price'] : '' }}"
                                                            >
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr class="">
                                                    <td scope="row">{{ $type->name }}</td>
                                                    <td>{{ $type->price }}</td>
                                                    <td>
                                                        <input type="hidden" name="pricelist[{{ $type->id }}][type_id]" style="width:80px;text-align:center;" value="{{ $type->id }}">
                                                        <input type="text" name="pricelist[{{ $type->id }}][price]" style="width:80px;text-align:center;"
                                                        value="{{ isset($prices[$type->id]) ? $prices[$type->id]['price'] : '' }}"
                                                        >
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
