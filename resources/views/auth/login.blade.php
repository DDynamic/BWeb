@extends('layouts.master')

@section('title', 'Login')

@section('content')
    <div class="col-md-3"></div>
    <div class="col-12 col-lg-6">
        <div class="card">
            <h4 class="card-header">Login</h4>
            <div class="card-body">
                <div class="alert alert-warning">
                    <p><b>Privacy Notice:</b> Your password is never saved and the site administrator does not have access to your credientals. This website is for educational purposes only.</p>
                </div>
                <form method="post">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="district">District Code</label>
                        <input type="text" class="form-control" id="district" name="district" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="custom-controls-stacked">
                        <label class="custom-control custom-radio">
                            <input id="student" name="role" value="PARENTSWEB-STUDENT" type="radio" class="custom-control-input" checked>
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Student</span>
                        </label>
                        <label class="custom-control custom-radio">
                            <input id="parent" name="role" value="'PARENTSWEB-PARENT" type="radio" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Parent</span>
                        </label>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary btn-block">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection