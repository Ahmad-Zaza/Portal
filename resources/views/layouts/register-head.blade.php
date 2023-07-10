<!DOCTYPE html>
<html>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <script src="{{ asset('js/jquery-1.12.4.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animate.css') }}">
    <link rel="stylesheet" class="en-style" href="{{ asset('css/ltr_style.css') }}">

    <link rel="stylesheet" href="{{ asset('css/veeamServer/generalElement.css') }}">

    <script src="{{ asset('js/fonts.js') }}"></script>
    <title>Bactopus Portal | SaaS Backup & Recovery Solution for Microsoft 365</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/bactopus_favicon.png') }}">
    <style>
        .checkmark {
            top: 9px !important;
            left: 9px !important;
            height: 15px !important;
            width: 15px !important;
        }

        .checkmark:after {
            top: 3px !important;
            left: 3px !important;
        }

        input[disabled] {
            color: #737779;
        }
    </style>
</head>

<body class="removeScroll">
    <div class="loading">
        <div class="wrapper">
            <div class="loader-outer">
                <div class="loader-inner" style="top: -16px">
                    <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                </div>
            </div>
            <h1><span>LOADING</span></h1>
        </div>
    </div>
    <div class="alert custom-swal-modal-success custom-success-oper success-oper" role="alert">
        <div class="custom-swal-icon swal-icon--sucess">
            <span class="swal-icon--success__line swal-icon--success__line--long"></span>
            <span class="swal-icon--success__line swal-icon--success__line--tip"></span>
        </div>
        <div class="swal-title text-center">Done!</div>
        <div class="success-msg mb-10 text-center"></div>
    </div>

    <div class="alert custom-swal-modal custom-danger-oper danger-oper" role="alert">
        <div class="swal-icon swal-icon--error">
            <div class="swal-icon--error__x-mark">
                <span class="swal-icon--error__line swal-icon--error__line--left"></span>
                <span class="swal-icon--error__line swal-icon--error__line--right"></span>
            </div>
        </div>
        <div class="swal-title text-center">Error</div>
        <div class="danger-msg mb-10 text-center"></div>
    </div>

    @yield('content')
</body>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/sweetalert2.js') }}"></script>
<script src="{{ asset('js/smooth-scrollbar.js') }}"></script>
<script src="{{ asset('js/script.js') }}"></script>


<script>
    $(document).ready(function() {
        $('[name="Country"]').change();
    })
    $("#Domain").change(function(e) {
        $("button[type='submit']").attr("disabled", true);
        var value = $(this).val() + ".onmicrosoft.com";
        $("#wrong-domain").hide();
        $("#wrong-domain1").hide();
        $("#success-domain").hide();
        $(e).attr("disabled", true);
        $('.loading-image').show();
        $.get('/step2/checkDomain/' + value, function(response) {
            if (response.status == 404) {
                $("#success-domain").show();
                $("button[type='submit']").attr("disabled", false);

            } else if (response.status == 400) {
                $("#wrong-domain1").show();
                $("button[type='submit']").attr("disabled", true);
            } else {
                $("#wrong-domain").show();
                $("button[type='submit']").attr("disabled", true);
            }
            $('.loading-image').hide();
            $(e).attr("disabled", false);

        })
    });
    $(function() {
        detectMobile();
    });
    $(window).bind('beforeunload', function(event) {
        $('.loading').css("opacity", 100).css("display", "block");
        $('body').addClass('removeScroll');
    });

    function detectMobile() {
        window.setInterval(function() {
            let width = window.innerWidth;
            if (width < 1050) {
                $('body').addClass('removeScroll');
                $('#resizeError').removeClass('hide');
            } else {
                $('body').removeClass('removeScroll');
                $('#resizeError').addClass('hide');
            }
        }, 500);
    }
</script>

@stack('bottom')

</html>
