@component('mail::message')

# Greetings,

Your password reset was requested.

Follow this link to reset your password.

 
[Click Here to Get a New Password]({{$link}})



Thanks, 

{{config('app.name')}}

@endcomponent