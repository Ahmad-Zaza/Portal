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
            background-size: auto;
            background-repeat: no-repeat;
            overflow: hidden;
            background-size: cover;
            background-position: center center;
            min-height: 100vh;
            position: relative;
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
            text-align: center;
            display: block;
        }

        a:hover {
            background-color: #FA9351;
            color: Black;
            text-decoration: none;
        }

        .permission {
            color: white;
            display: block;
            margin-bottom: 20px;
        }

        .row {
            margin-right: -15px;
            margin-left: -15px;
            position: absolute;
            width: 100%;
            bottom: 8vh;
        }

    </style>
</head>

<body>
    <div class="row">
        <div class="col-sm-8"></div>
        <div class="col-sm-3">
            <div class="permission">You don't have permission to
                {{ $permission->permissionCategory->name . ' - ' . $permission->display_name }}</div>
            <a href="/">HOME</a>
            <a style="margin-top:20px;" href="{{url()->previous()}}">back</a>
        </div>
    </div>

</body>

</html>
