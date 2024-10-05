<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('Reset Password') }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        .email-container {
            background-color: #ffffff;
            padding: 20px;
            margin: 50px auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            text-align: center;
        }
        h1 {
            font-size: 24px;
            color: #333333;
        }
        p {
            font-size: 18px;
            color: #555555;
        }
        .btn-custom {
            background-color: #28a745;
            color: #ffffff !important;
            border-radius: 5px;
            padding: 15px 30px;
            text-decoration: none;
            display: inline-block;
            margin: 20px 0;
            font-size: 18px;
        }
        .btn-custom:hover {
            color: #ffffff;
            background-color: #218838;
        }
        .footer {
            margin-top: 20px;
            border-top: 1px solid #dddddd;
            padding-top: 20px;
            color: #888888;
        }
        
        .footer a {
            color: #218838;
            text-decoration: none;
        }
        .footer a:hover {
            color: #81da94;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h1>{{ __("Hello, it looks like you've forgotten your password.") }}</h1>
        <p>{{ __('Good news, you can reset it by clicking the button below:') }}</p>
        <a href="{{ url('reset-password/' . $token) }}" class="btn-custom">{{ __('Reset Password') }}</a>
        <p>{{ __("You have 60 minutes to reset your password till the requested link will not be eligible anymore.") }}</p>
        <p>{{ __("If you're not the one that requested the password change, please ignore this message.") }}</p>
        <p>{{ __('Happy Coding,') }}<br>{{ __('The iCodeNET Team') }}</p>
        <div class="footer">
            <p>{{ __('Follow us:') }}</p>
            <p>
                <a href="{{ url('/') }}">{{ __('Dashboard') }}</a>
            </p>
        </div>
    </div>
</body>
</html>
