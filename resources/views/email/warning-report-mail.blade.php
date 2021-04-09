<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Welcome to the involvvely.</title>
        <link rel="stylesheet" type="text/css" href="{{asset('resources/css/bootstrap.min.css')}}">

        <style>
            a:hover {
                text-decoration: none;
            }
            .ucenter okay {
                text-align:center
            }


        </style>

    </head>

    <body class="reset" style="background: #edeef2;height: 591px;">
        <div class="reset-password-section">
            <div class="header" style="padding: 30px 0px;">
                <img src="{{asset('resources/img/logo.png')}}" style="width: 150px; margin: auto; display: flex;">
            </div>
            <div class="reset-password-area" style="background: #fff; flex-direction: column; align-items: center; margin: auto; width: 550px; padding: 30px;text-align: center; border-top: 3px solid #62cef2; box-shadow: 0px 0px 12px rgba(224, 224, 224, 0.5);">
                <div class="reset-pass-img">
                    <img src="{{asset('resources/img/mail.png')}}" style=" width: 43px;margin-bottom: 20px;">
                </div>
                <div class="reset-pass-content">
                    <p style="color: #62cef2; font-weight:500;">Hello, {{$name}}</p>
                    <!--<h1>ThankYou For Choosing Us</h1>-->
                    <!--<p>Your profile has been Created, <span>Welcome to the Barbaar Tv.</span></p>-->
                    <!--<p>Your Credentials are as Follows , <br>-->
                    <!-- <span class="ucenter"><a href=""
                      style="box-sizing:border-box;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';border-radius:4px;color:#fff;display:inline-block;overflow:hidden;text-decoration:none;background-color:#2d3748;border-bottom:8px solid #2d3748;border-left:18px solid #2d3748;border-right:18px solid #2d3748;border-top:8px solid #2d3748"
                      target="_blank"></a></span> <br> -->
                      <p>Reason for Report{{ $reason}}</p>
                    <p>Thank you!</p>
                    <!-- <p
                    style="box-sizing:border-box;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';line-height:1.5em;margin-top:0;text-align:left;font-size:14px">
                    If you’re having trouble clicking the "Reset Password" button, copy and paste the URL below
                    into your web browser: <span
                      style="box-sizing:border-box;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';word-break:break-all"><a
                        href=""
                        style="box-sizing:border-box;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';color:#3869d4"
                        target="_blank"></a></span>
                  </p> -->
                </div>

            </div>
        </div>

    </body>


</html>

