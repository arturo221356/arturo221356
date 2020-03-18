@extends('layouts.app')

@section('content')


<table>

@foreach ($iccs as $icc)

<tr>

<td>{{$icc->subproduct->name}}</td>
<td>{{$icc->subproduct->product->name}}</td>

</tr>


@endforeach
</table>

@endsection