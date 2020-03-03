@extends('layouts.app')

@section('content')

<example-component
    
:fields="          
    [{ key: 'id', label: '#', sortable: true, sortDirection: 'desc' },
    { key: 'imei', label: 'Imei', sortable: true, class: 'text-center' },
    { key: 'marca', label: 'Marca', sortable: true, class: 'text-center' },
    { key: 'modelo', label: 'Modelo', sortable: true, class: 'text-center' },
    { key: 'sucursal', label: 'Sucursal', sortable: true, class: 'text-center' },
    { key: 'status', label: 'Status', sortable: true, class: 'text-center' },
    { key: 'editar', label: 'Editar', class: 'text-center' },]"
fetch-url="/admin/inventario/equipos"
navbar-name="Inventario"


>
</example-component>


@endsection