<!DOCTYPE html>
<html>
<head></head>
<body></body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    />
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
</head>
<body>
<section class="verify-number">
    <div class="container">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <div class="text-center">
                    <img class="pb-2" src="https://i.postimg.cc/9X1wrDTB/Shuttle-Logo.png" alt=""/>
                    <h3>Verifying Mobile Number</h3>
                    <p class="pt-2 pb-2">
                        We have sent an OTP on this number <b>{{ $phone }}</b>
                    </p>
                </div>
                <form action="{{ route('verifyOtp') }}" method="POST">
                    <div class="form-group" hidden>
                        <label for="phone">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ $phone }}">
                    </div>
                    <div class="mb-3 form-check">
                        <label for="mobileNumber" class="pb-2">OTP</label>
                        <input
                            type="text"
                            name="otp"
                            class="form-control"
                            placeholder="Enter your OTP"
                        />
                    </div>
                    <button type="submit" class="btn btn-primary full-width-button">
                        Verify Code
                    </button>
                </form>

                <div class="d-flex justify-content-between pt-2">
                    <p><span id="timer"></span></p>
                    <p>Resend OTP</p>
                </div>
            </div>

            <div class="col-md-4"></div>
        </div>
    </div>
</section>

<script>
    let remaining = 120;

    function timer() {
        const [minutes, seconds] = [Math.floor(remaining / 60), remaining % 60].map(time => time.toString().padStart(2, '0'));
        document.getElementById("timer").textContent = `${minutes}:${seconds}`;
        remaining--;

        if (remaining >= 0) {
            setTimeout(timer, 1000);
        } else {
            remaining = 120;
            timer();
        }
    }

    timer();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
