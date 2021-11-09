
@component('mail::message')
# Welcome to the Post Management System

Dear {{$email}},

Thankyou so much for your registration, please click the button below to verify your email
{{$url}}
@component('mail::button', ['url' => '{{$url}}'])
Verify Now
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
