@extends('layouts.dashboard')

@section('section', 'Overview')

@section('display')
<h4 class="display-5">{{ $user }}</h4>
<div class="alert alert-{{ $alert }}" role="alert">
    {!! $brief !!}
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead class="thead-inverse">
            <tr>
                <th>Course Name</th>
                <th>Grade</th>
                <th>Instructor</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($classes as $class)
                <tr>
                    <td>
                        <a href="{{ route('dashboard.course', ['id' => $class['course_id']]) }}">{{ $class['name'] }}</a>
                    </td>
                    <td class="
                    @if ($class['grade'] != 'N/A')
                        @switch($class['grade'])
                        @case($class['grade'] <= 70)
                            table-danger
                            @break
                        @case($class['grade'] <= 80)
                            table-warning
                            @break
                        @case($class['grade'] <= 90)
                            table-info
                            @break
                        @endswitch
                    @endif">
                        {{ $class['grade'] }}@if($class['grade'] == 'N/A')
                        <i class="fa fa-info-circle" data-toggle="tooltip" title="It appears this course does not have a grade. If this appears to be an error, please contact your instructor and tell them to finish setting up their gradebook."></i>
                        @endif
                    </td>
                    <td><a href="mailto:{{ $class['email'] }}">{{ $class['instructor'] }}</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
