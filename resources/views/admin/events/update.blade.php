@extends('layouts.admin')
@section('styles')
<style>
    #vid{
        height: 400px;
    }
</style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>UPDATE EVENT</strong>

                </div>
                <div class="card-body">
                    <form action="{{ route('update.event',$event->id) }}" method="POST">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Event ID</label>
                                    <input type="text" name="event_id" class="form-control" value="{{$event->event_id}}" readonly>
                                </div>
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label>Event Name</label>
                                    <input type="text" name="name" class="form-control" value="{{$event->name}}">
                                </div>

                                <div class="form-group">
                                    <label>Live Fight URL</label>
                                    <input type="text" name="live_url" class="form-control" value="{{$event->live_url}}">
                                </div>

                                <button class="btn btn-success btn-sm float-right">UPDATE EVENT</button>
                            </div>
                            <div class="col-md-7 offset-md-1">
                                <strong>Video Preview</strong>
                                <div id="vid">
                                    <iframe width="100%" height="400" src="{{$event->live_url}}" title="Live Cockfight" frameborder="1" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
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
{{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/clappr/latest/clappr.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/clappr.level-selector/latest/level-selector.min.js"></script>
<script>
    $(document).ready(function () {
        $('.table').DataTable();

        var player = new Clappr.Player({
            source: "{{$event->live_url}}",
            mimeType: "application/x-mpegURL",
            autoPlay: true,
            height: "100%",
            width: "100%",
            plugins: {"core": [LevelSelector]},
            parentId: "#vid"
        });
    });
</script> --}}
@endsection
