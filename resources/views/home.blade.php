@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @role('externo')
            <div class="alert alert-light mt-5" role="alert">
                <a href="/activa-chip" class="alert-link">Activa Chip</a>
                <div class="float-right">
                   <a href="/activa-chip" class="alert-link" >Portabilidad</a>
                </div>
             
             </div>
             @endrole  
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
