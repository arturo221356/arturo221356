@extends('layouts.app')
@section('content')
        <example-component
        
        @if($userRole=='seller')
            user-role="{{$userRole}}"
            user-sucursal="{{$userSucursal}}"
            navbar-name="Inventario Seller"
        
        
        
            @elseif($userRole='admin')
            user-role="{{$userRole}}"
            navbar-name="Inventario Admin"
        
            @elseif($userRole='supervisor')
            user-role="{{$userRole}}"
            navbar-name="Inventario Supoervidor"


        @endif
        
        


        >
        </example-component>

        

            

@endsection