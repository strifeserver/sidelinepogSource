@extends('layouts.admin')
@section('styles')
<style>
    .not_open{
        color: #ffc107;
        font-weight: bolder;
    }

    .last_call{
        color: #ffc107;
        font-weight: bolder;
    }

    .finished{
        color: #28a745;
        font-weight: bolder;
    }

    .open{
        color: #28a745;
        font-weight: bolder;
    }
    .closed{
        color: #dc3545;
        font-weight: bolder;
    }

    .wala{
        color: #007bff;
        font-weight: bolder;
        text-transform: uppercase;
    }
    .meron{
        color: #dc3545;
        font-weight: bolder;
        text-transform: uppercase;
    }
    .cancelled{
        color: #6c757d;
        font-weight: bolder;
        text-transform: uppercase;
    }
    .draw{
        color: #ffc107;
        font-weight: bolder;
        text-transform: uppercase;
    }

</style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>ALL FIGHTS</strong>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <th>ID</th>
                            <th>Fight Number</th>
                            <th>Fight Result</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action/s</th>
                        </thead>
                        <tbody>
                            @php
                                $fightLen = count($fights);
                            @endphp
                            @foreach ($fights as $i => $f)
                            @if($f->result != NULL)
                                <tr>
                                    <td>{{$f->id}}</td>
                                    <td>{{$f->fight_number}}</td>
                                    <td>
                                        @if($f->result == NULL)
                                            <span class="draw">UNDECLARED FIGHT</span>
                                        @else
                                            <span class="{{$f->result}}">{{$f->result}}</span>
                                        @endif

                                    </td>
                                    <td>{{date('m/d/Y',strtotime($f->created_at))}}</td>
                                    <td><span class="{{$f->status}}">{{strtoupper($f->status)}}</span></td>
                                    <td>
                                        <a href="{{ route('show.bets', $f->id) }}" class="btn btn-secondary btn-sm"><strong>VIEW BETS</strong></a>
                                    </td>
                                </tr>
                            @endif
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
    });
</script>
@endsection
