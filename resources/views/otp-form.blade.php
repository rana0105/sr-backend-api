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
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap');

        body {
            font-family: 'Work Sans', sans-serif;

        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {

            font-family: 'Montserrat', sans-serif;
        }

        p {

            font-family: 'Montserrat', sans-serif;
        }

        a {

            font-family: 'Montserrat', sans-serif;
        }

        .verify-number {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
        }

        .error-message {
            color: red;
        }

        .form-check {

            padding-left: 0px !important;

        }

        .text-center h3 {
            font-weight: 600;
            font-size: 22px;
            color: #111632;
        }

        label {
            color: #202020;
            font-weight: 500;
            font-size: 14px;

        }

        input {
            background-color: #F7F7F7 !important;
        }

        .text-center p {

            color: #202020;
            font-weight: 400;
            font-size: 16px;
        }

        .full-width-button {
            width: 100%;
            border: 1px solid #a95eea !important;
            background-color: #a95eea !important;
            color: #fff;
        }

        .otp-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .otp-container p {
            cursor: pointer;
            color: #a95eea;
            font-weight: 500;
            font-size: 14px;
        }

        .justify-content-between {
            color: #a95eea;
            font-weight: 400;
        }
    </style>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    />
</head>
<body>
<section class="verify-number">
    <div class="container">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <div class="text-center">
                    <img class="pb-2" src="https://i.postimg.cc/9X1wrDTB/Shuttle-Logo.png" />
                    <h3>Verifying Mobile Number</h3>
                    <p class="pt-2 pb-2">
                        We will send an SMS with a code to verify your mobile number
                    </p>
                </div>
                <form action="{{ route('request-otp') }}" method="POST">
                    @csrf
                    <div class="mb-3 form-check">
                        <label for="mobileNumber" class="pb-2">Mobile Number</label>
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
                        <div id="error-message" class="error-message pt-2"></div>
                    </div>
                    <button type="submit" class="btn btn-primary full-width-button">
                        Send Code
                    </button>
                </form>
            </div>

            <div class="col-md-4"></div>
        </div>
    </div>
</section>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
