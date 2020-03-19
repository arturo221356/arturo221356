@extends('layouts.app')
@section('content')
        <inventario-component
        
        @if($userRole=='seller')
            user-role="{{$userRole}}"
            user-sucursal="{{$userSucursal}}"
           
        
        
        
            @elseif($userRole='admin')
            user-role="{{$userRole}}"
            
        
            @elseif($userRole='supervisor')
            user-role="{{$userRole}}"
           


        @endif
        
        navbar-name="Inventario"


        >
        </inventario-component>

        

            

@endsection