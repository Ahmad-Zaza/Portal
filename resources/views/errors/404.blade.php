<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="{{ asset('js/jquery-1.12.4.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" class="en-style" href="{{ url('/css/veeamServer/generalElement.css') }}">
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <title>Bactopus Portal | SaaS Backup & Recovery Solution for Microsoft 365</title>
    <link rel="icon" type="image/x-icon" href="{{asset('img/bactopus_favicon.png')}}">

    <style>
        body {
            background-image: url('{{asset('images/404_error.png')}}');
            background-size: cover;
            background-repeat: no-repeat;
            overflow: hidden;
        }

        .first {
            padding-bottom: 40%;
        }



        a {
            font-weight: bold;
            border-radius: 3px;
            background-color: #FA9351;
            border: solid;
            border-color: coral;
            color: #ffffff;
            padding-left: 30%;
            padding-right: 30%;
            text-decoration: none;
            padding-top: 2%;
            padding-bottom: 2%;
            font-size: 16px;
        }

        a:hover {
            background-color: #FA9351;
            color: Black;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="row first">
    </div>
    <div class="row">
        <div class="col-sm-8"></div>
        <div class="col-sm-3">

            <a href="/">HOME</a>
        </div>
    </div>

</body>

</html>
