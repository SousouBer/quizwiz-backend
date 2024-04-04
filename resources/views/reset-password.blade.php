<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Document</title>
    <style>
        .main-container {
            align-content: center;
            padding: 2rem 0;
        }

        .heading-logo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .logo-container {
            width: 5rem;
        }

        .btn-container {
            display: flex;
            justify-content: center;
        }

        .logo {
            width: 100%;
        }

        .heading {
            font-size: 2.75rem;
            font-weight: 700;
        }

        .username {
            padding: 0 0.25rem;
            margin: 1rem;
        }

        .btn {
            background-color: #4b69fd;
            padding: 1rem 2.5rem;
            border-radius: 0.75rem;
            margin-top: 1rem;
        }

        </style>
</head>
<body style="width: 100%; background-color: #F3F4F6;">
    <div class="main-container" style="margin: 0 auto; width: 29rem;">
            <div class="heading-logo-container">
                <img src="{{ config('app.url') . '/images/ quizwiz.png' }}" alt="Quizwiz logo">
                <h1 class="heading">Reset your password<br> to join again</h1>
            </div>
            <div>
                <p class="username">Hi, {{ $username }}</p>
                <p>You're almost there! To reset your password, please<br> verify your email address below.</p>
            </div>
            <div class="btn-container">
                <a style="color: #fff; text-decoration: none;" class="btn" href="{{ $resetPasswordUrl }}">Verify Your Email Address</a>
            </div>            
        </div>
    </body>
</html>