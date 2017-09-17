@extends('master')

@section('title', 'Login')

@section('content')
    <div class="col-md-4"></div>
    <div class="col-12 col-lg-4">
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
                    <button type="submit" class="btn btn-primary btn-block">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
