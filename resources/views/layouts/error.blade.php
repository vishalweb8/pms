<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Error Occurred</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&family=Nunito:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/error.css') }}">
</head>
<body id="error">
    <main role="main" class="main">
        @yield('content')
    </main>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', ()=> {
            let goBack = document.getElementById('goBack');
            if (goBack) {
                goBack.addEventListener('click', (e)=> {
                    e.preventDefault();
                    history.back();
                })
            }
        })
    </script>
</body>
</html>
