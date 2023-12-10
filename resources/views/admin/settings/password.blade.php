@extends('layouts.admin')
@section('styles')
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>PASSWORD SETTINGS</strong>

                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('update.password') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="password" name="password" class="form-control" value="">
                                </div>

                                <div class="form-group">
                                    <label>Confirm New Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" value="">
                                </div>
                                {{ csrf_field() }}
                                <div class="text-right">
                                    <button type="submit" class="btn btn-success">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
              </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
