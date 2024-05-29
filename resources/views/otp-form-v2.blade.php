<!DOCTYPE html>
<html>
<head> </head>
<body></body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="{{asset('website-css/authstyle.css')}}" rel="stylesheet" />
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
        rel="stylesheet"/>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css"
    />
</head>
<body>
<section class="nav-shadow-design">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-white">
            <!-- <div class="container-fluid"> -->
            <a class="navbar-brand" href="/">
                <img src="{{asset('website-images/ShuttleLogo.png')}}" alt="" />
            </a>
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div
                class="collapse navbar-collapse pt-2"
                id="navbarSupportedContent"
            >
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item ps-0">
                        <a class="nav-link" href="https://www.shuttlebd.com/"
                        >Home</a
                        >
                    </li>
                    <li class="nav-item ps-0">
                        <a class="nav-link" href="https://www.shuttlebd.com/business"
                        >Business</a
                        >
                    </li>
                    <li class="nav-item ps-0">
                        <a class="nav-link" href="https://www.shuttlebd.com/partner"
                        >Partner</a
                        >
                    </li>
                    <li class="nav-item ps-0">
                        <a class="nav-link" href="https://www.shuttlebd.com/rental"
                        >Rental</a
                        >
                    </li>
                    <li class="nav-item ps-0">
                        <a class="nav-link" href="https://www.shuttlebd.com/shuttle-for-school"
                        >School</a
                        >
                    </li>

                    <li class="nav-item ps-0">
                        <a class="nav-link" href="https://www.shuttlebd.com/blogs"
                        >Blog</a
                        >
                    </li>
                </ul>

                <button type="button" class="btn btn-light">Login</button>
            </div>
            <!-- </div> -->
        </nav>
    </div>
</section>

<section class="mobile-number">
    <div class="container">
        <div class="row">
            <div class="col-md-4">

            </div>
            <div class="col-md-4">
                <div class="card">
                    <a class="shuttle-logo" href="/">
                        <img src="{{asset('website-images/ShuttleLogo.png')}}" alt="" />
                    </a>
                    <h3 class="text-center">Verify Mobile Number</h3>
                    <p class="text-center">
                        We will send an SMS with a code to verify your mobile number
                    </p>
                    <form action="{{ route('request-otp') }}" method="POST">
                        @csrf
                    <div class="mb-3">
                        <label for="mobile" class="form-label">Mobile Number</label>
                        <input
                            type="tel"
                            id="mobileNumber"
                            placeholder="Enter your phone number"
                            class="form-control"
                            name="phone_number"
                            required
                            pattern="\d{11}"
                            oninput="validateMobileNumber()"
                        />
                    </div>
                    <div class="form-footer d-flex">
                        <!-- <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button> -->
                        <button type="submit" class="submit-button" id="nextBtn">
                            Send Code
                        </button>
                    </div>
                    </form>

                </div>

            </div>
            <div class="col-md-4">

            </div>

        </div>

    </div>

</section>
<!-- footer html part start from here -->
<section class="footer" id="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="partone">
                    <h6>Company</h6>

                    <a
                        href="https://www.shuttlebd.com/terms-&-conditions"
                        target="_blank"
                    >
                        <p>Terms & Conditions</p></a
                    >
                    <a href="https://www.shuttlebd.com/privacy-policy" target="_blank"
                    ><p>Privacy & Policy</p></a
                    >
                </div>
            </div>
            <div class="col-md-3">
                <div class="partone">
                    <h6>Our Services</h6>

                    <a href="/About" target="_blank"> <p>Shuttle B2C</p></a>
                    <a href="/Business" target="_blank"
                    ><p>Shuttle for Business</p></a
                    >
                    <a href="/"> <p>Shuttle Partner</p></a>
                    <a href="/Rental" target="_blank"><p>Shuttle Rental</p></a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="partone">
                    <h6>Contact</h6>
                    <p>+8809638888868</p>
                    <p>(Saturday - Thursday: 6:00AM - 9:00PM)</p>
                    <p>Email: info@shuttlebd.com</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="parttwo">
                    <h6>Follow us on</h6>

                    <a target="_blank" href="https://www.facebook.com/shuttlebd"
                    ><i class="bi bi-facebook"></i
                        ></a>
                    <a
                        target="_blank"
                        href="https://www.instagram.com/shuttlebangladesh/"
                    ><i class="bi bi-instagram"></i
                        ></a>
                    <a target="_blank" href="https://twitter.com/shuttle_bd"
                    ><i class="bi bi-twitter"></i
                        ></a>
                    <a
                        target="_blank"
                        href="https://www.linkedin.com/company/shuttlebd/mycompany/"
                    ><i class="bi bi-linkedin"></i
                        ></a>

                    <div class="store">
                        <a
                            target="_blank"
                            href="https://play.google.com/store/apps/details?id=com.easytransport.shuttle"
                        ><img src="{{asset('website-images/google1.png')}}" alt=""
                            /></a>
                        <a
                            target="_blank"
                            href="https://apps.apple.com/app/shuttlebd/id1459741215"
                        ><img src="{{asset('website-images/app1.png')}}" alt=""
                            /></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- footer section html part end here -->
<!-- bottom footer html part start -->
<div class="bottom-footer">
    <div class="container">
        <div class="textbox">
            <p>© Copyright 2021. All rights reserved</p>
        </div>
        <div class="imagebox">
            <img src="{{asset('website-images/bottom.png')}}" />
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function validateMobileNumber() {
        const input = document.getElementById("mobileNumber");
        const number = input.value.replace(/\D/g, "");
        const isValid = number.length === 11;

        if (!isValid) {
            input.classList.add("error");
            document.getElementById("error-message").textContent =
                "Please enter a valid mobile number";
        } else {
            input.classList.remove("error");
            document.getElementById("error-message").textContent = "";
        }
    }
</script>
</body>
</html>
