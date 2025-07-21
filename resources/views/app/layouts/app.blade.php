<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>KeuringHub | @yield('title')</title>

    <!-- Fonts -->

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Sweetalert CSS -->
    <link href=" https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.min.css " rel="stylesheet">

    <!-- Remove FullCalendar CSS from main layout as it's loaded in specific pages -->
    @if (!request()->is('*agenda*'))
        <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.css" rel="stylesheet">
    @endif

    <link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/calendar-custom.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @method('PUT')
    <meta name="user-role" content="{{ auth()->user()->type }}">
</head>

<body>
    <div class="wrapper d-flex">
        <div class="sidebar d-none d-xxl-flex">
            <div class="sidebar-top">
                <div class="logo text-center border-bottom">
                    @php
                        $user = Auth::user();
                    @endphp

                    @if ($user->type === 'client')
                        <div class="py-3">Client Management</div>
                    @elseif($user->type === 'employe')
                        <div class="py-3">Medewerker Management</div>
                    @elseif($user->type === 'tenant')
                        @php
                            $setting = App\Models\Setting::where('tenant_id', $user->id)->first();
                        @endphp
                        @if ($setting)
                            @if ($setting->logo != null)
                                <img class="admin-logo" src="{{ asset('img/files/' . $setting->logo) }}"
                                    alt="{{ $setting->company }}" />
                            @else
                                {{ $setting->company }}
                            @endif
                        @endif
                    @endif
                </div>

                @auth('tenant')
                    <x-tenant::menu />
                @endauth
                @auth('client')
                    <x-client::menu />
                @endauth
                @auth('employee')
                    <x-employee::menu />
                @endauth

                <ul class="mt-0 py-0">
                    <li>
                        <form action="{{ route('logout') }}" method="post">
                            @csrf
                            <button type="submit">
                                <i class="ri-logout-box-line"></i> {{ __('Afmelden') }}
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            <div class="sidebar-bottom border-top">
                <div class="profile">
                    <i class="ri-user-smile-line"></i>
                    @if ($authUser)
                        {{ $authUser->name }}
                    @endif
                </div>
            </div>
        </div>
        <div class="content">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->

</body>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        var editorElement = document.querySelector("#editor");

        if (editorElement) {
            var quill = new Quill('#editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{
                            'size': ['small', false, 'large', 'huge']
                        }],
                        [{
                            'color': []
                        }, {
                            'background': []
                        }],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }],
                        ['link'],
                        ['clean']
                    ]
                }
            });

            $('form').on('submit', function(e) {
                e.preventDefault();
                var content = quill.root.innerHTML;
                $('#description').val(content);
                this.submit(); // This will actually submit the form
            });
        }



    });
</script>

<!-- Only load calendar-library.js on non-agenda pages -->
@if (!request()->is('*agenda*'))
    <script src="{{ asset('js/calendar-library.js') }}"></script>
@endif

<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/chartLibrary.js') }}"></script>
<!-- Sweetalert -->
<script src=" https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.all.min.js "></script>
<!-- Comment out the module import that's causing errors -->
<!-- <script type="module" src="{{ asset('js/calendar/index.js') }}"></script> -->
<script src="{{ asset('js/chart.js') }}"></script>
@stack('scripts')
<script src="{{ asset('js/cart.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


</html>
