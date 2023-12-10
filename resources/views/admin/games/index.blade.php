@extends('layouts.admin')
@section('styles')

@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>GAMES LIST</strong>
                    <a class="btn btn-success float-right" href="{{ route('create.game') }}">CREATE NEW GAME</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <th>Name</th>
                            <th>Banner</th>
                            <th>Game URL</th>
                            <th>PLASADA (%)</th>
                            <th>Status</th>
                            <th>Action/s</th>
                        </thead>
                        <tbody>
                            @foreach ($games as $game)
                                <tr>
                                    <td>{{strtoupper($game->name)}}</td>
                                    <td><img src="{{ asset($game->banner) }}" width="150" height="50" alt=""></td>
                                    <td>{{strtoupper($game->url)}}</td>
                                    <td>{{strtoupper($game->plasada)}}%</td>
                                    <td>{{strtoupper($game->status)}}</td>
                                    <td>
                                        <a href="{{ route('edit.game', $game->id) }}" class="btn btn-success btn-sm">EDIT</a>
                                        <button class="btn btn-danger btn-sm btn-delete" data-url="{{ route('delete.game', ['id'=>$game->id]) }}">DELETE</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
              </div>
        </div>
    </div>
    @include('partials.modals._depositAmount')
@endsection
@section('scripts')
@include('partials._jsvariables')
<script src="{{ asset('js/main.js') }}"></script>
<script>
    $('tr').on('click','.btn-delete',function(){
        let url = $(this).data('url');
        Swal.fire({
        title: 'Do you want to delete this game?',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: `No`,
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                window.location.href = url;
            }
        })
    })
</script>
@endsection
