@extends('app.layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">{{ __('Blog') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Bewerk</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <form action="{{ route('blog.update', ['blog' => $blog->id]) }}" method="POST" enctype="multipart/form-data" onsubmit="return false;">
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
                            <label for="name" class="form-label">Naam</label>
                            <input type="text" id="title" name="data[title]" class="form-control" autocomplete="off"
                                required value="{{ $blog->title }}">
                        </div>

                        <div class="mb-4">
                            <label for="short_description" class="form-label">Korte Beschrijving</label>
                            <input type="text" id="short_description" name="data[short_description]" class="form-control"
                                autocomplete="off" value="{{ $blog->short_description }}">
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">Content</label>
                            <div id="editor">{!! $blog->content !!}</div>
                                <input type="hidden" name="data[content]" id="description">
                        </div>

                        <div class="mb-4">
                            <label for="image" class="form-label">Afbeelding</label>
                            <input type="file" id="image" name="image" class="form-control" accept="image/*"
                                value="{{ $blog->image }}">
                        </div>

                        <div class="mb-4">
                            <img src="{{ asset($blog->image) }}" alt="{{ $blog->title }}" height="50"
                                class="border rounded">
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
