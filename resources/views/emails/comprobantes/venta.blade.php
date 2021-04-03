@component('mail::message')
# Hola {{$cliente_name}} 

Te compartimos tu comprobante de compra.



{{-- @component('mail::button', ['url' => $url])
Descargar Comprobante
@endcomponent --}}

Gracias por su preferencia,<br>
{{$distribution_name}}
@endcomponent
