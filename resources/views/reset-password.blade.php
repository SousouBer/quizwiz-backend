<div style="width: 100%; padding: 4rem 0; background-color: #F3F4F6;">
    <div style="align-content: center; text-align: center; padding: 2rem 0; margin: 0 auto; width: 50%; background-color: #F3F4F6;">
        <img src="{{ asset('/images/quizwiz.png') }}" alt="Quizwiz logo">
        <h1 style="text-align: center; font-size: 2.5rem; font-weight: 700; ">Reset your password<br> to join again</h1>
        <div style="margin-bottom: 2rem; text-align: start;">
            <p style="font-size: 1rem; padding: 0 0.25rem; margin: 1rem, 0.5rem;">Hi, {{ $username }}!</p>
            <p style="font-size: 1rem;">You're almost there! To reset your password, please<br> verify your email address below.</p>
         </div>
        <a style="font-size: 1rem; background-color: #4b69fd; padding: 0.75rem 1.75rem; border-radius: 0.75rem; margin-top: 1rem; color: #fff; text-decoration: none;" href="{{ $resetPasswordUrl }}">Verify Your Email Address</a>
    </div>
</div>