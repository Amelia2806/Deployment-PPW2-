@extends('layout')

@section('title', 'About Us')

@section('content')
    <h1>Halaman About</h1>
    <h2>Nama: {{ $name }}</h2>
    <h2>Email: {{ $email }}</h2>
    <button>Learn More</button>
@endsection