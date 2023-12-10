@extends('layouts.admin')
@section('styles')

@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong><i class="fas fa-bars"></i> List of REMOVED PLAYERS</strong>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Points</th>
                            <th>Action/s</th>
                        </thead>
                        <tbody>
                            @foreach ($deleted_players as $dp )
                                <tr>
                                    <td>{{$dp->name}}</td>
                                    <td>{{$dp->contact_number}}</td>
                                    <td>{{$dp->wallet->balance}}</td>
                                    <td>
                                        <button type="button" class="btn btn-restore-account btn-outline-success btn-sm" data-url="{{route('restore.account',$dp->id)}}">
                                            <i class="fas fa-circle"></i> RESTORE ACCOUNT
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
              </div>
        </div>
    </div>
@endsection
@section('scripts')
@include('partials._jsvariables')
<script src="{{ asset('js/main.js') }}"></script>.
<script>
$('.table').on('click','.btn-restore-account',function(){
        let urlDelete = $(this).data('url');
        Swal.fire({
            title: "Are you sure you want to do this?",
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type : 'GET',
                    url : urlDelete,
                    success : function(res){
                        Swal.fire(
                            res.msg,
                            '',
                            'success'
                        );
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    },
                    error : function(err){
                        console.log(err)
                    }
                })
            }
        })
    })
</script>
@endsection
