@extends('master')

@section('title', 'Login')

@section('content')
    <div class="col-md-4"></div>
    <div class="col-12">
        <div class="card">
            <h4 class="card-header">Dashboard</h4>
            <div class="card-body">
                Welcome to the Dashboard.<br><br>

                District: {{ session('district') }}<br>
                Username: {{ session('username') }}<br>
                Password: {{ session('password') }}
            </div>
        </div>
    </div>
@endsection
