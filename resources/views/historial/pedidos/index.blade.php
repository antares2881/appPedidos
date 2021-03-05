@extends('layouts.app')

@section('historial')
    active
@endsection
@section('content')
    <pedidoscalox-component :ventas="{{json_encode($ventas)}}"/>
@endsection