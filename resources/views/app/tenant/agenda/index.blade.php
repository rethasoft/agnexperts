@extends('layouts.tenant')

@section('title', 'Calendar')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Calendar</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="calendar-container p-4">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('app.tenant.agenda.partials.scripts')
@endsection
