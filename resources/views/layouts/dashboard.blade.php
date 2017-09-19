@extends('layouts.master')

@section('title')
    @yield('section')
@endsection

@section('content')
    <div class="col-md-4"></div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-pills card-header-pills">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">Overview</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('exit') }}">Logout</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                @yield('display')
            </div>
        </div>
    </div>
@endsection
