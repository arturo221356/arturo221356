@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @role('externo')
            <div class="alert alert-light mt-5" role="alert">
                <a href="/activa-chip" class="alert-link">Activa Chip</a>
                <div class="float-right">
                    <a href="/porta-express" class="alert-link">Portabilidad</a>
                </div>

            </div>
            @endrole

            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">Consulta Interconexion</div>

                        <div class="card-body">
                            <check-itx-component></check-itx-component>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        
                        <div class="card-header">Consulta Compañia</div>
                        <div class="card-body">
                            <check-company-component></check-company-component>
                        </div>
                    </div>
                </div>

            </div>
            @can('store stock')         
            <div class="row mt-5">
                <div class="col-lg-6">
                    <div class="card">
                        
                        <div class="card-header">Calculadora de Icc por caja</div>

                        <div class="card-body">
                            <b-alert show variant="warning">Inserta los Icc sin el ultimo numero.</b-alert>
                            
                            <icc-calculator-component></icc-calculator-component>
                        </div>
                    </div>
                </div>
            </div>

            @endcan

        </div>
    </div>
</div>
@endsection