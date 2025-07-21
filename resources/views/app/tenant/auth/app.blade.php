<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Management | Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{background: #eee;}
        .vertical-center{
            display:flex;
            flex-direction: column;
            height: 100vh;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-lg-7 col-xl-5 mx-auto my-auto">
                <div class="vertical-center">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</body>
</html>