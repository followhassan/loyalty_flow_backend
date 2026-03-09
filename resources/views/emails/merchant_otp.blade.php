@component('mail::message')

# Hello {{ $name }} 👋

Thank you for registering.

Your verification OTP is:

@component('mail::panel')
# {{ $otp }}
@endcomponent

Please use this OTP to verify your account.

This OTP will expire soon.

Thanks,<br>
{{ config('app.name') }}

@endcomponent
