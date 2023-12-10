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
                    <strong>ALL EVENTS</strong>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
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
                                        <a href="{{ route('players.bets', ['id'=>$id,'eventID'=>$event->id]) }}" class="btn btn-primary btn-sm"><strong>VIEW BETTING HISTORY</strong></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
