@extends('layouts.app')

@section('content')

@foreach ($iccs as $icc)
{{$icc->sucursal->nombre_sucursal}}
{{$icc->icc_status->status}}
@endforeach


@endsection