@extends('app.layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item"><a href="{{ route('role.index') }}">{{ __('Rollen') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Nieuw</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <form action="{{ route('role.store') }}" method="POST">
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
                                <input type="text" id="username" name="name" class="form-control"
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

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        Permissions
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="text-center">Create</th>
                                    <th class="text-center">Read</th>
                                    <th class="text-center">Update</th>
                                    <th class="text-center">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Keuringen</td>
                                    <td class="text-center"><input type="checkbox" name="create[]" value="keuringen" id=""></td>
                                    <td class="text-center"><input type="checkbox" name="read[]" value="keuringen" id=""></td>
                                    <td class="text-center"><input type="checkbox" name="update[]" value="keuringen" id=""></td>
                                    <td class="text-center"><input type="checkbox" name="delete[]" value="keuringen" id=""></td>
                                </tr>

                                <tr>
                                    <td>Diensten</td>
                                    <td class="text-center"><input type="checkbox" name="create[]" value="dienst" id=""></td>
                                    <td class="text-center"><input type="checkbox" name="read[]" value="dienst" id=""></td>
                                    <td class="text-center"><input type="checkbox" name="update[]" value="dienst" id=""></td>
                                    <td class="text-center"><input type="checkbox" name="delete[]" value="dienst" id=""></td>
                                </tr>

                                <tr>
                                    <td>Klanten</td>
                                    <td class="text-center"><input type="checkbox" name="create[]" value="client" id=""></td>
                                    <td class="text-center"><input type="checkbox" name="read[]" value="client" id=""></td>
                                    <td class="text-center"><input type="checkbox" name="update[]" value="client" id=""></td>
                                    <td class="text-center"><input type="checkbox" name="delete[]" value="client" id=""></td>
                                </tr>

                                <tr>
                                    <td>Status</td>
                                    <td class="text-center"><input type="checkbox" name="create[]" value="status" id=""></td>
                                    <td class="text-center"><input type="checkbox" name="read[]" value="status" id=""></td>
                                    <td class="text-center"><input type="checkbox" name="update[]" value="status" id=""></td>
                                    <td class="text-center"><input type="checkbox" name="delete[]" value="status" id=""></td>
                                </tr>
                            </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
