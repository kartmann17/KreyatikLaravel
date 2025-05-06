@component('mail::message')
# Nouveau message de contact

**De:** {{ $name }}  
**Email:** {{ $email }}  
**Objet:** {{ $object_message }}

## Message:
{{ $message }}

@component('mail::button', ['url' => config('app.url')])
Aller sur le site
@endcomponent

Merci,<br>
{{ config('app.name') }}
@endcomponent 