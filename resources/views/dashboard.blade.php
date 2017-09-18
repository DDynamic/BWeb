@extends('master')

@section('title', 'Overview')

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
                <div class="alert alert-{{ $alert }}" role="alert">
                    {!! $brief !!}
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <td>Class Name</td>
                            <td>Grade</td>
                            <td>Instructor</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($classes as $class)
                            <tr>
                                <td>{{ $class['name'] }}</td>
                                <td>{{ $class['grade'] }}</td>
                                <td><a href="mailto:{{ $class['email'] }}">{{ $class['instructor'] }}</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
