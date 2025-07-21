@extends('app.layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item"><a href="{{ route('role.index') }}">Rollen</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Bewerk</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <form action="{{ route('role.update', ['role' => $role->id]) }}" method="POST">
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
                                <input type="text" id="username" name="name" class="form-control" autocomplete="off"
                                    required value="{{ $role->name }}">
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
                                @if (is_array($entities) && !empty($entities))
                                    @foreach ($entities as $entity)
                                        <tr>
                                            <td>{{ ucfirst($entity) }}</td>
                                            @foreach ($actions as $action)
                                                <td class="text-center">
                                                    @php
                                                        $permissionName = $action . '_' . $entity;
                                                        try {
                                                            $hasPermission = $role->hasPermissionTo($permissionName);
                                                        } catch (\Spatie\Permission\Exceptions\PermissionDoesNotExist $e) {
                                                            $hasPermission = false; // Permission does not exist
                                                        }
                                                    @endphp
                                                    <input type="checkbox" name="{{ $action }}[]"
                                                        value="{{ $entity }}"
                                                        id="{{ $action }}_{{ $entity }}"
                                                        {{ $hasPermission ? 'checked' : '' }}>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endif
                                <tr></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </form>
@endsection
