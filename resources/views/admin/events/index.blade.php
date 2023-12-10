@extends('layouts.admin')
@section('styles')
<style>
    .completed{
        color: #dc3545;
        font-weight: bolder;
    }

    .open{
        color: #28a745;
        font-weight: bolder;
    }
    .closed{
        color: #ffc107;
        font-weight: bolder;
    }
</style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>CREATED EVENTS</strong>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <th>ID</th>
                            <th>Event Name</th>
                            <th>Fight URL</th>
                            <th>Date Created</th>
                            <th>Status</th>
                            <th>Action/s</th>
                        </thead>
                        <tbody>
                            @foreach ($events as $event)
                            <tr>
                                <td>{{$event->event_id}}</td>
                                <td>{{$event->name}}</td>
                                <td>{{$event->live_url}}</td>
                                <td>{{date('m/d/Y',strtotime($event->created_at))}}</td>
                                <td><span class="{{$event->status}}">{{strtoupper($event->status)}}</span></td>
                                <td>
                                    @if($event->game_id == 1)
                                    <a href="{{ route('show.event', $event->id) }}" class="btn btn-primary btn-sm">View</a>
                                    @elseif($event->game_id == 17)
                                    <a href="{{ route('show.event.rg', $event->id) }}" class="btn btn-primary btn-sm">View</a>
                                    @elseif($event->game_id == 14)
                                    <a href="{{ route('show.event.cg', $event->id) }}" class="btn btn-primary btn-sm">View</a>
                                    @elseif($event->game_id == 26)
                                    <a href="{{ route('show.event.pl', $event->id) }}" class="btn btn-primary btn-sm">View</a>
                                    @elseif($event->game_id == 27)
                                    <a href="{{ route('show.event.dp', $event->id) }}" class="btn btn-primary btn-sm">View</a>
                                    @else
                                    <a href="{{ route('game.declare', $event->id) }}" class="btn btn-primary btn-sm">View</a>
                                    @endif

                                    @if($event->status != 'completed')
                                    <a href="{{ route('edit.event', $event->id) }}" class="btn btn-success btn-sm">Edit</a>
                                    @endif
                                    @if($event->status == 'completed')
                                        <button data-url="{{ route('delete.event',$event->id) }}" class="btn btn-danger btn-del-event btn-sm">Delete</button>
                                    @endif
                                    <a href="{{ route('show.fights', $event->id) }}" class="btn btn-warning btn-sm">Fights</a>
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

<script>
    $(document).ready(function () {
        $('.table').DataTable();
        $('.btn-del-event').on('click',function(){
            let url = $(this).data('url');
            Swal.fire({
                title: 'Are you sure you want to delete this event?',
                text: "",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href=url;
                }
            })
        })
    });
</script>
@endsection
