@extends('layouts.app')

@section('content')


@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif



<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="wrap">
                <h2>Comisiones Telcel</h2>
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>

                @endif
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your file.
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ URL::to('/comisiones') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                        <input type="file" name="telcel[]" multiple>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-info">Upload</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>    
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="wrap">
                <h2>Comisiones Movistar</h2>
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>

                @endif
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your file.
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ URL::to('/comisiones') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                        <input type="file" name="movistar[]" multiple>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-info">Upload</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>    
</div>

<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="wrap">
                <h2>Cargar Clientes genericos para porta</h2>
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>

                @endif
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your file.
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ URL::to('/cargar-clientes-porta') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                        <input type="file" name="clientes" multiple>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-info">Upload</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>    
</div>

@endsection