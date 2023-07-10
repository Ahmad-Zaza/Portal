// var otherForm = false;
$(document).ready(function() {
    if (document.querySelector('.agree-licence') !== null) {
        var Scrollbar = window.Scrollbar;
        Scrollbar.init(document.querySelector('.agree-licence'));
    }


    $(".alt-p,.alt-a").attr("aria-expanded", "true");
    $(".alt-p").addClass("collapse in");
    //-----------
    $('[data-toggle="tooltip"]').tooltip();
    //-----------
    var a = '';
    var b = '';
    jQuery.strength = function(element, password) {
        var desc = [{
            'width': '0px'
        }, {
            'width': '20%'
        }, {
            'width': '40%'
        }, {
            'width': '60%'
        }, {
            'width': '80%'
        }, {
            'width': '100%'
        }];
        var descClass = ['', 'progress-bar-danger', 'progress-bar-danger', 'progress-bar-warning', 'progress-bar-success', 'progress-bar-success'];
        var score = 0;

        if (password.length > 6) {
            score++;
        }

        if ((password.match(/[a-z]/)) && (password.match(/[A-Z]/))) {
            score++;
        }

        if (password.match(/\d+/)) {
            score++;
        }

        if (password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)) {
            score++;
        }

        if (password.length > 10) {
            score++;
        }
        a = descClass[score - 1];
        b = descClass[score];
        c = desc[score]
        element.removeClass(descClass[score - 1]).addClass(descClass[score]).css(desc[score]);
    };
    if (document.getElementById('newTenant3') !== null) {
        if (document.getElementById('newTenant3').checked) {
            document.getElementById("microsoftTenant3").checked = false;
            document.getElementById("newTenant3").checked = true;
            $(".parent-form4").attr("data-anim", "hide-to--right");
            $(".parent-form3").attr("data-anim", "show-from--left");
        } else if (document.getElementById('microsoftTenant3').checked) {
            // debugger
            document.getElementById("microsoftTenant4").checked = true;
            document.getElementById("newTenant4").checked = false;
            $(".parent-form3").attr("data-anim", "hide-to--left");
            $(".parent-form4").attr("data-anim", "show-from--right");
            // console.log(otherForm);
        }
    }

    if (document.getElementById('trialRadio') !== null) {
        if (document.getElementById('trialRadio').checked) {
            document.getElementsByClassName('vCodeChild')[0].style.display = 'none';
        } else if (document.getElementById('vCodeRadio').checked) {
            document.getElementsByClassName('vCodeChild')[0].style.display = 'block';
        }
    }



    if (document.getElementsByClassName('invalid-feedback') !== null) {
        $(".invalid-feedback").siblings("input,textarea,select").css("border-left", "1px solid rgb(246, 85, 85)");
        $(".invalid-feedback").siblings("input,textarea,select").after('<i class="fa fa-exclamation-circle" aria-hidden="true"></i>');
    }

    if ($(".parent-form4 .alert.alert-danger ul li").text() !== "") {
        $(".parent-form4 #tenantId").css("border-left", "1px solid rgb(246, 85, 85)");
        $(".parent-form4 #tenantId").after('<i class="fa fa-exclamation-circle" style="top: 60px;" aria-hidden="true"></i>');
    }

    changeCountry(event);

    if ($("select[name='Country']").val() == "-2") {
        $(".row-withoutcountry-currentenant").css("display", "none");
        $(".row-withoutcountry-newtenant").css("display", "none");
    }
});


// Registration Form Radio Checked
$(".subscrTypeRadio").on('click', function() {
    if (document.getElementById('trialRadio').checked) {
        // document.getElementsByClassName('trialChild')[0].style.display = 'block';
        document.getElementsByClassName('vCodeChild')[0].style.display = 'none';
        $("#trial").prop('required', true);
        $("#pwd").prop('required', false);
        $("#vcode").prop('required', false);
    } else if (document.getElementById('vCodeRadio').checked) {
        // document.getElementsByClassName('trialChild')[0].style.display = 'none';
        document.getElementsByClassName('vCodeChild')[0].style.display = 'block';
        $("#pwd").prop('required', true);
        $("#vcode").prop('required', true);
        $("#trial").prop('required', false);
    }
});

//End Registration Form Radio Checked


// Tenants Form Radio Checked
$(".tenantType").on('change', function() {
    // document.getElementById('microsoftTenant').value
    if ($(this).val() == 'microsoftTenant') {
        // debugger
        document.getElementById("microsoftTenant4").checked = true;
        document.getElementById("newTenant4").checked = false;
        $(".parent-form3").attr("data-anim", "hide-to--left");
        $(".parent-form4").attr("data-anim", "show-from--right");
    } else if ($(this).val() == 'newTenant') {
        //  debugger
        document.getElementById("microsoftTenant3").checked = false;
        document.getElementById("newTenant3").checked = true;
        // document.getElementById("newTenant3").checked = true;
        $(".parent-form4").attr("data-anim", "hide-to--right");
        $(".parent-form3").attr("data-anim", "show-from--left");
        $(".row-withoutcountry-newtenant").css("display", "block");
    }
});
//End Tenants Form Radio Checked


$(window).on('load', function() {
    $('.loading').delay(1000).animate({
        'opacity': '0'
    }, function() {
        $(this).css({
            'display': 'none'
        });
        $('.removeScroll').removeClass('removeScroll');
    });
});

function changeCountry(event) {
    $('#country4').attr('disabled', 'disabled');
    // debugger
    if (event.target.value == "-2") {
        $(".row-withoutcountry-currentenant").css("display", "none");
        $(".row-withoutcountry-newtenant").css("display", "none");

    } else if (event.target.value == "-1") {
        $('#country').val('-1').attr("selected", true);
        $('#country5').val('-1').attr("selected", true);
        // document.getElementById("microsoftTenant3").checked = false;
        $(".tenant-group").css("display", "none");
        $(".parent-form4").attr("data-anim", "hide-to--right");
        $(".parent-form3").attr("data-anim", "show-from--left");
        // $(".parent-form3").attr("data-other", "formOther");
        $(".row-withoutcountry-newtenant").css("display", "block");
        $(".row-withoutcountry-currentenant").css("display", "none");

        var selectedCountry = event.target.value;
        var options1 = document.querySelectorAll("select#country option");
        var options = document.querySelectorAll("select#country5 option");
        for (var i = 0; i < options1.length; i++) {
            //   debugger
            if (options[i].value === selectedCountry) {
                options1[i].setAttribute("selected", true);
                $("#country").val(selectedCountry);
                options[i].setAttribute("selected", true);
                $("#country5").val(selectedCountry);
            }
        }
        // debugger
        $('#country4').val('SA').attr("selected", true);
        // otherForm = true;
        // .attr('checked');
    } else if (document.getElementById('newTenant3') !== null || document.getElementById('microsoftTenant3') !== null) {
        if (document.getElementById('newTenant3').checked) {
            document.getElementById("microsoftTenant3").checked = false;
            document.getElementById("newTenant3").checked = true;
            $(".parent-form4").attr("data-anim", "hide-to--right");
            $(".parent-form3").attr("data-anim", "show-from--left");
            $(".row-withoutcountry-newtenant").css("display", "block");
            var selectedCountry = event.target.value;
            var options4 = document.querySelectorAll("select#country4 option");
            var options = document.querySelectorAll("select#country5 option");
            for (var i = 0; i < options4.length; i++) {
                // debugger
                if (options4[i].value === selectedCountry) {
                    options[i].setAttribute("selected", true);
                    $("#country5").val(selectedCountry);
                    options4[i].setAttribute("selected", true);
                    $("#country4").val(selectedCountry);
                }
            }
        } else if (document.getElementById('microsoftTenant3').checked) {
            document.getElementById("microsoftTenant3").checked = true;
            document.getElementById("newTenant3").checked = false;
            var selectedCountry5 = event.target.value;
            var options = document.querySelectorAll("select#country option");
            var options4 = document.querySelectorAll("select#country4 option");

            for (var i = 0; i < options.length; i++) {
                // debugger
                if (options[i].value === selectedCountry5) {
                    options[i].setAttribute("selected", true);
                    $("#country").val(selectedCountry5);
                    $("#country5").val(selectedCountry5);
                    options4[i].setAttribute("selected", true);
                }
            }

            document.getElementById("microsoftTenant4").checked = true;
            document.getElementById("newTenant4").checked = false;
            $(".parent-form3").attr("data-anim", "hide-to--left");
            $(".parent-form4").attr("data-anim", "show-from--right");
            $(".row-withoutcountry-newtenant").css("display", "none");
        }
        // $('#country4').val('SA').attr("selected",true);
        $(".tenant-group").css("display", "block");

        $(".row-withoutcountry-currentenant").css("display", "block");

    }

}

// timeLine Soultion
var timeLinePoint = document.getElementsByClassName("ui-link");
for (var i = 1; i < timeLinePoint.length; i++) {
    if (timeLinePoint[i].className !== "ui-link disabled-step") {
        $(".filling-line").css("transform", "scaleX(" + 0.333 * i + ")");
        continue
    }
}



// add alert after every step
$("form").on("submit", function(e) {
    var x = document.getElementById("registrationForm");
    var y = document.getElementById("formLogin");
    var z = document.getElementById("formReset");
    var w = document.getElementById("authenticationForm");
    // var z = document.getElementById("authenticationForm");
    if (e.target !== x && e.target !== y && e.target !== z && e.target !== w) {
        e.preventDefault();
        $(".swal-modal").show();
        swal({
            title: "Do you want to send it anyway?",
            text: "You won't be able to update your entered data later.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $(this).unbind('submit').submit();
            } else {
                // console.log(e.target == "form#registrationForm.form-horizontal");
                // swal("Your imaginary file is safe!");
                $(".swal-modal").hide();
            }
        });
    }
});
