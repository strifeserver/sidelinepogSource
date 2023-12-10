@extends('layouts.admin')
@section('styles')

@endsection
@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <strong>UPDATE MY ACCOUNT</strong>
                </div>
                <div class="card-body">
                    <form action="{{ route('update.account',Auth::id()) }}" method="POST">
                        <div class="row">
                            <div class="col-md-12">
                                {{ csrf_field() }}
                                {{-- <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" value="{{Auth::user()->name}}" name="name" class="form-control">
                                </div> --}}

                                {{-- <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" value="{{Auth::user()->username}}" name="username" class="form-control">
                                </div> --}}

                                {{-- <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" value="{{Auth::user()->email}}" name="email" class="form-control">
                                </div> --}}

                                <div class="form-group">
                                    <label>Contact Number</label>
                                    <input type="text" value="{{Auth::user()->contact_number}}" name="contact_number" class="form-control">
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
