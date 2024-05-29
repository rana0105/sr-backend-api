@extends('layouts.app')
@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://jqueryvalidation.org/files/demo/site-demos.css">
    <link rel="stylesheet" href="http://jqueryvalidation.org/files/demo/site-demos.css">

    <style>
        label.error {
            position: absolute;
            bottom: -21px;
            left: 0%;
            color: red;
        }

        #field, label {
            float: left;
            font-family: Arial, Helvetica, sans-serif;
            font-size: small;
        }

        .ui-datepicker .ui-datepicker-buttonpane button {
            display: none;
        }

        marquee {
            font-size: 15px;
            font-weight: 800;
            color: red;
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
@endsection
@section('content')
    <div id="banner">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-lg-6" id="banner-random-color" style="padding: 0">
                    <img id="banner-leftwave" width="100%"
                         src="{{asset('frontend/assets/images/banner-left-wave.png')}}" alt="">
                    <div id="trip-form">
                        <div id="form-title">
                            <h4>Book your Trip</h4>
                            <p>Experience a Safe & Hassle Free Journey</p>

                        </div>
                        <!--<marquee height="30%">-->
                        <!--    <span>Due to government lockdown notice our rental services outside Dhaka are closed temporarily.-->
                        <!--        Sorry for the inconvenience.</span>-->
                        <!--</marquee>-->
                        <form action="{{route('save-quote-request')}}" method="POST" id="shuttleForm"
                              autocomplete="off">
                            @csrf
                            <div class="info-form">
                                <div class="form-group row">
                                    <div class="col-md-12 col-lg-12 checkbox">
                                    <span id="one-way"><input class="mr-1" type="radio" name="trip_type" value="One_Way"
                                                              required checked>One Way</span>
                                        <span id="return"><input class="mr-1" type="radio" name="trip_type"
                                                                 value="Return"
                                                                 required>Return</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12 col-lg-12">
                                        <input type="text" required class="form-control" name="from"
                                               placeholder="From"><span class="fas fa-map-marker-alt"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12 col-lg-12">
                                        <input type="text" required class="form-control" name="to"
                                               placeholder="To"><span
                                            class="fas fa-map-marker-alt"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12 col-lg-12">
                                        <input type="text" class="form-control datepicker" id="journey_date"
                                               name="journey_date" placeholder="Journey Date" required><span
                                            class="fa fa-calendar" autocomplete="off"></span>
                                    </div>
                                </div>
                                <div class="form-group row" style="display: none" id="return_date">
                                    <div class="col-md-12 col-lg-12">
                                        <input type="text" class="form-control datepicker return_date"
                                               name="return_date" placeholder="Return Date" required><span
                                            class="fa fa-calendar"></span>
                                    </div>
                                </div>

                                {{-- <div class="form-group row">
                                    <div class="col-md-12 col-lg-12">
                                        <input type="text" class="form-control" id="redeem_check"
                                               name="redeem_check"
                                               placeholder="Redeem Check">
                                    </div>
                                </div> --}}

                                <div class="form-group row">
                                    <div class="col-md-12 col-lg-12">
                                        <select name="vehicle_type" required>
                                            <option value="">Select Vehicle Type</option>
                                            <option value="Hiace">Hiace</option>
                                            <option value="Noah">Noah</option>
                                            <option value="Sedan">Sedan</option>
                                            <option value="Coaster">Coaster</option>
                                        </select><span class="fa fa-car"></span>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <div class="col-md-12 col-lg-12">
                                        <button id="proceed" type="button" class="btn btn-info">Proceed</button>
                                    </div>
                                </div>
                            </div>

                            <div class="details-form" style="display: none;padding: 5px 0">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <input type="text" required class="form-control" name="name" placeholder="Name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12" id="email">
                                        <input type="email" required class="form-control" name="email"
                                               placeholder="Email">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <input type="text" required class="form-control" name="phone"
                                               placeholder="Phone Number">
                                    </div>
                                </div>
                                <div class="form-group row" style="justify-content: center;">
                                    <div class="col-md-12">
                                        <button onclick="showInfoForm();" style="background-color: #0278ae;"
                                                type="submit"
                                                class="btn btn-info">Request for a quote
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6" id="budget-pic">
                    <img width="100%" id="banner-car" src="{{asset('frontend/assets/images/banner-car-edited.png')}}"
                         alt="">
                    <img width="100%" id="banner-car-mobile"
                         src="{{asset('frontend/assets/images/banner-car-edited-mobile2.png')}}" alt="">
                    <div class="social-meda">
                        <ul style="list-style-type: none">
                            <li><a target="_blank" href="https://web.facebook.com/Shuttleforbusiness"><i
                                        class="fab fa-facebook-f"></i></a></li>
                            <li><a target="_blank" href="https://twitter.com/shuttle_bd"><i
                                        class="fab fa-twitter"></i></a></li>
                            <li><a target="_blank" href="https://www.linkedin.com/company/shuttlebd"><i
                                        class="fab fa-linkedin"></i></a></li>
                            <li><a target="_blank"
                                   href="https://www.instagram.com/shuttleforwomen/?igshid=1uyjftxnbl4qw"><i
                                        class="fab fa-instagram"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div id="how-works">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-lg-12">
                    <h2>Travel around Bangladesh with us</h2>
                    <h4>How it works</h4>
                    <h3>Book your Shuttle in just 4 simple steps</h3>
                    <img width="100%" src="{{asset('frontend/assets/images/how-works.png')}}" alt="">
                </div>
            </div>
        </div>
    </div>
    <div id="budget-experience">
        <div class="container-fluid">
            <div class="row align-items-center h-100">
                <div class="col-md-6 col-lg-6" style="padding: 0">
                    <img id="budget-color" width="100%"
                         src="{{asset('frontend/assets/images/budget-color-edited.png')}}" alt="">
                </div>
                <div class="col-md-6 col-lg-6 mx-auto" id="contentBudget">
                    <h4>What you get</h4>
                    <h3>The best experience within your budget</h3>
                    <p><img width="40px" src="{{ asset('/frontend/assets/images/rectangle.png') }}" alt="">Get the best
                        price in town</p>
                    <p><img width="40px" src="{{ asset('/frontend/assets/images/rectangle.png') }}" alt="">Choose from a
                        wide range of vehicles</p>
                    <p><img width="40px" src="{{ asset('/frontend/assets/images/rectangle.png') }}" alt="">Trained
                        driver </p>
                    <p><img width="40px" src="{{ asset('/frontend/assets/images/rectangle.png') }}" alt="">24/7 Customer
                        support</p>
                    <p><img width="40px" src="{{ asset('/frontend/assets/images/rectangle.png') }}" alt="">Any vehicle
                        for any occasion</p>

                    <img id="rightWave" width="100%" src="{{ asset('/frontend/assets/images/right-wave-mobile.png') }}"
                         alt="">
                </div>

                {{-- <div class="col-md-6 col-lg-6" id="budget-content" style="padding: 0;display: none">
                    <div id="budget-heading">
                        <h4>What you get</h4>
                        <h3>The best experience within your budget</h3>
                    </div>
                    <div id="budget-checkbox">
                        <ul>
                            <li><img width="100%" src="{{ asset('/frontend/assets/images/rectangle.png') }}" alt="">Get the best price in town</li>
                            <li><img width="100%" src="{{ asset('/frontend/assets/images/rectangle.png') }}" alt="">Choose from a wide range of vehicles</li>
                            <li><img width="100%" src="{{ asset('/frontend/assets/images/rectangle.png') }}" alt="">Trained driver </li>
                            <li><img width="100%" src="{{ asset('/frontend/assets/images/rectangle.png') }}" alt="">24/7 Customer support</li>
                            <li><img width="100%" src="{{ asset('/frontend/assets/images/rectangle.png') }}" alt="">Any vehicle for any occasion</li>
                        </ul>
                    </div>
                    <div id="right-wave">
                        <img width="100%" src="{{ asset('/frontend/assets/images/right-wave-mobile.png') }}" alt="">
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
    <div id="hygiene">
        <img id="hygiene-original" src="{{asset('frontend/assets/images/corona-safety.png')}}">
        <img id="hygiene-mobile" width="100%" src="{{asset('frontend/assets/images/corona-safety-mobile.png')}}">
    </div>
    <div id="shuttle-support">
    <div class="container">
        <h1>We are proudly supported by</h1>
        <div class="row justfy-content-center" id="shuttle-support-content" style="justify-content: space-evenly">
            <div class=" col-6 col-lg-2 col-md-2">
                <img src="{{asset('frontend/assets/images/robi.svg')}}" class="robi">
            </div>
            <div class=" col-6 col-lg-2 col-md-2">
                <img src="{{asset('frontend/assets/images/undp.png')}}" class="undp">
            </div>
            <div class=" col-6 col-lg-2 col-md-2">
                <img src="{{asset('frontend/assets/images/ac.png')}}" class="ac">
            </div>
            <div class=" col-6 col-lg-2 col-md-2">
                <img src="{{asset('frontend/assets/images/citi.png')}}" class="citi">
            </div>
            <div class=" col-6 col-lg-2 col-md-2">
                <img src="{{asset('frontend/assets/images/LogoBBriddhi.png')}}" class="briddhi">
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
    {{-- <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            $("#shuttleForm").validate({
                messages: {},
                errorPlacement: function (error, element) {
                    var placement = $(element).data('errors');
                    if (placement) {
                        $(placement).append(error)
                    } else {
                        error.insertBefore(element);
                    }
                }
            })

            $('#proceed').click(function () {
                $valid = $("#shuttleForm").valid();
                if ($valid) {
                    $('.info-form').fadeOut(function () {
                        $('.details-form').fadeIn('fast');
                    });
                }
            });
        });
        var dateToday = new Date();
        var dates = $("#journey_date, .return_date").datepicker({
            dateFormat: "yy-mm-dd",
            changeMonth: true,
            numberOfMonths: 1,
            minDate: dateToday,
            onSelect: function (selectedDate) {
                var option = this.id == "journey_date" ? "minDate" : "maxDate",
                    instance = $(this).data("datepicker"),
                    date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
                dates.not(this).datepicker("option", option, date);
            }
        });
        // var dateToday = new Date();
        // $(function() {
        //     $( ".datepicker" ).datepicker({
        //         dateFormat: "yy-mm-dd",
        //         numberOfMonths: 1,
        //         showButtonPanel: true,
        //         minDate: dateToday
        //     });
        // });

        $("input[name=trip_type]:radio").click(function () {
            if ($('input[name=trip_type]:checked').val() == "Return") {
                $('#return_date').show();
            } else {
                $('#return_date').hide();
            }
        });

        function setCookie(key, value, expiry) {
            var expires = new Date();
            expires.setTime(expires.getTime() + (expiry * 24 * 60 * 60 * 1000));
            document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
        }

        function getCookie(key) {
            var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
            return keyValue ? keyValue[2] : null;
        }

        function eraseCookie(key) {
            var keyValue = getCookie(key);
            setCookie(key, keyValue, '-1');
        }

        var searchRequest = null;

        $(function () {
            var minlength = 5;

            $("#redeem_check").on('blur', function () {
                var that = this,
                    value = $(this).val();
                if (value.length >= minlength) {
                    if (searchRequest != null)
                        searchRequest.abort();
                    searchRequest = $.ajax({
                        type: "GET",
                        url: "{{route('redeem.code.check')}}",
                        data: {
                            'redeem_check': value
                        },
                        dataType: "text",
                        success: function (data) {
                            console.log(data)
                            //we need to check if the value is the same
                            if (data == 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    text: 'You have enter right redeem code !',
                                })
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'You have enter wrong redeem code !',
                                })
                            }
                        }, error: function (data) {
                            console.log(data)
                        }
                    });
                }
            });
        });

    </script>
@endsection
