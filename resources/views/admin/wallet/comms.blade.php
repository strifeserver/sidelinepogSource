@extends('layouts.admin')
@section('styles')
<style>
    .select2-container{
        display: block !important;
    }
    .select2-selection{
        height: 38px !important;
    }

    .in{
        color: green;
        font-weight: 700;
        text-transform: uppercase;
    }
    .out{
        color: red;
        font-weight: 700;
        text-transform: uppercase;
    }

    .card-body{
        padding: 5px !important;
    }
</style>
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <strong>COMMISSION LOGS</strong>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <th>#</th>
                            <th>Event Name</th>
                            <th>Fight Number</th>
                            <th>Bet Amount</th>
                            <th>Earned Commission</th>
                            <th>Date/Time</th>
                        </thead>
                        <tbody>
                            @foreach ($myCommissions as $i => $comm)
                                @if ($user->type == 'operator')
                                    @if($comm->operator_commission > 0)
                                        <tr>
                                            <td>{{$i+1}}</td>
                                            <td>{{strtoupper($comm->event_name)}}</td>
                                            <td>{{$comm->fight_number}}</td>
                                            <td>{{bcdiv($comm->amount,1,2)}}</td>
                                            <td>{{bcdiv($comm->operator_commission,1,2)}}</td>
                                            <td>{{date('M d,Y h:i A',strtotime($comm->created_at))}}</td>
                                        </tr>
                                    @endif
                                @elseif ($user->type == 'sub-operator')
                                    @if($comm->sub_operator_commission > 0)
                                    <tr>
                                        <td>{{$i+1}}</td>
                                        <td>{{strtoupper($comm->event_name)}}</td>
                                        <td>{{$comm->fight_number}}</td>
                                        <td>{{bcdiv($comm->amount,1,2)}}</td>
                                        <td>{{bcdiv($comm->sub_operator_commission,1,2)}}</td>
                                        <td>{{date('M d,Y h:i A',strtotime($comm->created_at))}}</td>
                                    </tr>
                                    @endif
                                @elseif ($user->type == 'master-agent')
                                    @if($comm->master_agent_commission > 0)
                                        <tr>
                                            <td>{{$i+1}}</td>
                                            <td>{{strtoupper($comm->event_name)}}</td>
                                            <td>{{$comm->fight_number}}</td>
                                            <td>{{bcdiv($comm->amount,1,2)}}</td>
                                            <td>{{bcdiv($comm->master_agent_commission,1,2)}}</td>
                                            <td>{{date('M d,Y h:i A',strtotime($comm->created_at))}}</td>
                                        </tr>
                                    @endif
                                @elseif ($user->type == 'gold-agent')
                                    @if($comm->gold_agent_commission > 0)
                                        <tr>
                                            <td>{{$i+1}}</td>
                                            <td>{{strtoupper($comm->event_name)}}</td>
                                            <td>{{$comm->fight_number}}</td>
                                            <td>{{bcdiv($comm->amount,1,2)}}</td>
                                            <td>{{bcdiv($comm->gold_agent_commission,1,2)}}</td>
                                            <td>{{date('M d,Y h:i A',strtotime($comm->created_at))}}</td>
                                        </tr>
                                    @endif
                                @elseif ($user->type == 'silver-agent')
                                    @if($comm->silver_agent_commission > 0)
                                        <tr>
                                            <td>{{$i+1}}</td>
                                            <td>{{strtoupper($comm->event_name)}}</td>
                                            <td>{{$comm->fight_number}}</td>
                                            <td>{{bcdiv($comm->amount,1,2)}}</td>
                                            <td>{{bcdiv($comm->silver_agent_commission,1,2)}}</td>
                                            <td>{{date('M d,Y h:i A',strtotime($comm->created_at))}}</td>
                                        </tr>
                                    @endif
                                @endif

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
        $('.table').DataTable({
            "ordering": false,
            "pageLength" : 50
        });
        $('.select').select2();
    });
</script>
@endsection
