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
                    <strong>CREATE EVENT</strong>

                </div>
                <div class="card-body">
                    <form action="{{ route('event.create') }}" method="POST">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Event ID</label>
                                    <input type="text" name="event_id" class="form-control" value="{{uniqid()}}" readonly>
                                </div>
                                {{ csrf_field() }}

                                <div class="form-group">
                                    <label>Game</label>
                                    <select name="game_id" class="form-control" required>
                                        <option value="">Select Game</option>
                                        @foreach ($games as $g)
                                            <option value="{{$g->id}}">{{strtoupper($g->name)}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Event Name</label>
                                    <input type="text" maxlength="35" name="name" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label>Live Fight URL</label>
                                    <input type="text" name="live_url" class="form-control" required>
                                </div>

                                <button class="btn btn-success btn-sm float-right">SAVE EVENT</button>
                            </div>
                            <div class="col-md-7 offset-md-1">
                                <strong>Video Preview</strong>
                                <div id="vid"></div>
                                {{-- <iframe width="100%" height="450" src="" title="Live Cockfight" frameborder="1" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> --}}
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
