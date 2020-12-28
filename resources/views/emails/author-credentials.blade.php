@component('mail::message')

# Greetings, {{$name}}

## Here are your login details

- USER NAME : *{{$email}}*

- PASSWORD : *{{$password}}*


Pleaase keep your detials secret and try to update it time to time. 

 
@component('mail::button', ['url' => 'https://science4all.blog/editor', 'color' => 'success'])
    Login Here
@endcomponent

Thanks, 

{{config('app.name')}}

@endcomponent





{{-- <!DOCTYPE html>
<html lang="en" class="font-sans antialiased">
<head>
    <!-- Meta Information -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Science4All Author's. — Login</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Merriweather|Nunito:200,200i,300,300i,400,400i,600,600i,800,800i,900,900i" rel="stylesheet">

    <!-- Style sheets-->
    <link href='{{mix('light.css', 'vendor/wink')}}' rel='stylesheet' type='text/css'>

</head>
<body>    
    <div class="max-w-2xl mx-auto">
        
        <div class="max-w-lg mx-auto text-center">
            <h1 class="text-lg text-green-500">
            Greetings,
        </h1>
        <h2 class="text-base my-4">Following are your login details, please update your password after login.</h2>
        <p>Your user name is: <span class="text-semibold"> {{$email}} </span></p>
        <p>Your password name is: <span class="text-semibold"> {{$password}} </span></p>
    </div>
    
    <h2>Thanks, SCIENCE4ALL.BLOG team. </h2>
</div>
</body> --}}