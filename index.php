<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <style>
        /* CSS styles */
        #otpdiv, #verifyotp, #resend_otp {
            display: none;
        }
        .countdown {
            display: table;
            width: 100%;
            text-align: left;
            font-size: 15px;
        }
        #resend_otp:hover {
            text-decoration: underline;
        }
        .otp-container {
            border: 1px solid brown; /* Border color */
            border-radius: 5px;
            padding: 20px;
        }
        .otp-msg {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <!-- HTML part start -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 otp-container">
                <!-- OTP message display -->
                <div class="otp-msg"></div>
                <!-- Heading -->
                <h1 class="text-brown text-center mb-4">OTP VERIFICATION</h1>
                <form method="post">
                    <div class="form-group">
                        <!-- Mobile number input field -->
                        <label for="mobile">Enter Mobile Number</label>
                        <input type="text" class="form-control" id="mob" placeholder="Enter mobile">
                    </div>
                    <div class="form-group" id="otpdiv">
                        <!-- OTP input field -->
                        <label for="otp">Enter OTP</label>
                        <input type="text" class="form-control" id="otp" placeholder="Enter OTP">
                        <br>
                        <!-- Countdown for resend OTP -->
                        <div class="countdown"></div>
                        <!-- Resend OTP link -->
                        <a href="#" id="resend_otp" class="btn btn-link">Resend OTP</a>
                    </div>
                    <!-- Buttons for sending and verifying OTP -->
                    <button type="button" id="sendotp" class="btn btn-primary">Send OTP</button>
                    <button type="button" id="verifyotp" class="btn btn-success">Verify OTP</button>
                </form>
            </div>
        </div>
    </div>
    <!-- HTML part ends -->

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            // Function to validate mobile number
            function validate_mobile(mob) {
                if (mob == '') {
                    return false;
                } else {
                    return true;
                }
            }

            // Function to send OTP
            function send_otp(mob) {
                var ch = "send_otp";
                $.ajax({
                    url: "otp_process.php",
                    method: "post",
                    data: {mob: mob, ch: ch},
                    dataType: "text",
                    success: function (data) {
                        if (data == 'success') {
                            // Show OTP input and hide Send OTP button
                            $('#otpdiv').css("display", "block");
                            $('#sendotp').css("display", "none");
                            $('#verifyotp').css("display", "block");
                            // Start countdown timer
                            timer();
                            // Show success message
                            $('.otp_msg').html('<div class="alert alert-success">OTP sent successfully</div>').fadeIn();
                            window.setTimeout(function () {
                                $('.otp_msg').fadeOut();
                            }, 1000);
                        } else {
                            // Show error message if OTP sending fails
                            $('.otp_msg').html('<div class="alert alert-danger">Error in sending OTP</div>').fadeIn();
                            window.setTimeout(function () {
                                $('.otp_msg').fadeOut();
                            }, 1000);
                        }
                    }
                });
            }

            // Event listener for Send OTP button
            $('#sendotp').click(function () {
                var mob = $('#mob').val();
                if (validate_mobile(mob) == false)
                    $('.otp_msg').html('<div class="alert alert-danger" style="position:absolute">Enter Valid mobile number</div>').fadeIn();
                else
                    send_otp(mob);
                window.setTimeout(function () {
                    $('.otp_msg').fadeOut();
                }, 1000);
            });

            // Event listener for Resend OTP link
            $('#resend_otp').click(function () {
                var mob = $('#mob').val();
                send_otp(mob);
                $(this).hide();
            });

            // Event listener for Verify OTP button
            $('#verifyotp').click(function () {
                var ch = "verify_otp";
                var otp = $('#otp').val();
                $.ajax({
                    url: "otp_process.php",
                    method: "post",
                    data: {otp: otp, ch: ch},
                    dataType: "text",
                    success: function (data) {
                        if (data == "success") {
                            // Show success message if OTP is verified successfully
                            $('.otp_msg').html('<div class="alert alert-success">OTP Verified successfully</div>').show().fadeOut(4000);
                        } else {
                            // Show error message if OTP verification fails
                            $('.otp_msg').html('<div class="alert alert-danger">OTP did not match</div>').show().fadeOut(4000);
                        }
                    }
                });
            });

            // Function to start countdown timer
            function timer() {
                var timer2 = "00:31";
                var interval = setInterval(function () {
                    var timer = timer2.split(':');
                    var minutes = parseInt(timer[0], 10);
                    var seconds = parseInt(timer[1], 10);
                    --seconds;
                    minutes = (seconds < 0) ? --minutes : minutes;
                    seconds = (seconds < 0) ? 59 : seconds;
                    seconds = (seconds < 10) ? '0' + seconds : seconds;
                    $('.countdown').html("Resend OTP in: <b class='text-primary'>" + minutes + ':' + seconds + " seconds </b>");
                    if ((seconds <= 0) && (minutes <= 0)) {
                        clearInterval(interval);
                        $('.countdown').html('');
                        $('#resend_otp').css("display", "block");
                    }
                    timer2 = minutes + ':' + seconds;
                }, 1000);
            }
        });
    </script>
</body>
</html>
