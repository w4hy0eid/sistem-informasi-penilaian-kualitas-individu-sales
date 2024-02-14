@extends('layouts.app')


@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-6">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            @if ($message = Session::get('error'))
            <div class="alert alert-danger">
                <ul>
                    <li>{{ $message }}</li>
                </ul>
            </div>
            @endif
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Change Password Form</h6>
                <form action="{{ route('/action/change-password') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="currentPassword" class="form-label">Current Password</label>
                        <input type="password" class="form-control" name="password" aria-describedby="emailHelp">
                    </div>
                    <div class="mb-3">
                        <label for="currentPassword" class="form-label">New Password</label>
                        <input type="password" class="form-control" name="newPassword" aria-describedby="emailHelp">
                    </div>
                    <div class="mb-3">
                        <label for="currentPassword" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" name="confirmNewPassword" aria-describedby="emailHelp">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
