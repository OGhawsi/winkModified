@component('mail::message')

# Greetings, Admin

## Here are your login details

- USER NAME : *{{$name}}*

- USER EMAIL : *{{$email}}*

- BIO : *{{$bio}}*


Pleaase keep your detials secret and try to update it time to time. 

 
@component('mail::button', ['url' => 'https://science4all.blog/editor', 'color' => 'success'])
    Login Here To Add User
@endcomponent

Thanks, 

{{config('app.name')}}

@endcomponent

