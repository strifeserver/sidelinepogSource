@extends('layouts.admin')
@section('styles')

@endsection
@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <strong>UPDATE USER ACCOUNT</strong>
                </div>
                <div class="card-body">
                    <form action="{{ route('update.account',$user->id) }}" method="POST">
                        <div class="row">
                            <div class="col-md-12">
                                {{ csrf_field() }}
                                {{-- <div class="form-group">
                                    <label>Upline</label>
                                    <input type="text" value="{{\App\User::find($user->created_by)->username}}" name="name" class="form-control">
                                </div> --}}

                                <div class="form-group">
                                    <label>ACCOUNT TYPE</label>
                                    <input type="text" value="{{ucwords($user->type)}}" class="form-control" disabled>
                                </div>

                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" value="{{$user->name}}" name="name" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" value="{{$user->username}}" name="username" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" value="{{$user->email}}" name="email" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label>Contact Number</label>
                                    <input type="text" value="{{$user->contact_number}}" name="contact_number" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>

                                <button class="btn btn-success btn-sm float-right">UPDATE ACCOUNT</button>
                            </div>
                        </div>
                    </form>
                </div>
              </div>
        </div>
    </div>
@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        $('.table').DataTable();
    });
</script>
@endsection
