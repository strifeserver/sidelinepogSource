@extends('layouts.admin')
@section('styles')

@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>COMMISSION HISTORY - {{strtoupper($user->name)}}</strong>
                    <strong class="float-right">{{strtoupper($ev->name)}}</strong>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <th>#</th>
                                <th>Bet ID</th>
                                <th>Fight No.</th>
                                <th>Bet Amount</th>
                                <th>Earned Commission</th>
                                <th>Bet</th>
                                <th>Fight Result</th>
                                <th>Date/Time</th>
                            </thead>
                            <tbody>
                                @foreach ($myCommissions as $i => $comm)
                                <tr>
                                    <td>{{$i+1}}</td>
                                    <td>{{$comm->id}}</td>
                                    <td>{{$comm->fight_number}}</td>
                                    <td>{{$comm->amount}}</td>

                                    @if ($user->type == 'operator')
                                        <td>{{findValue($comm->result,$comm->bet) ? $comm->operator_commission : '0.00'}}</td>
                                    @elseif ($user->type == 'sub-operator')
                                        <td>{{findValue($comm->result,$comm->bet) ? $comm->sub_operator_commission : '0.00'}}</td>
                                    @elseif ($user->type == 'master-agent')
                                        <td>{{findValue($comm->result,$comm->bet) ? $comm->master_agent_commission : '0.00'}}</td>
                                    @elseif ($user->type == 'gold-agent')
                                        <td>{{findValue($comm->result,$comm->bet) ? $comm->gold_agent_commission : '0.00'}}</td>
                                    @elseif ($user->type == 'silver-agent')
                                        <td>{{findValue($comm->result,$comm->bet) ? $comm->silver_agent_commission : '0.00'}}</td>
                                    @endif

                                    <td>{{strtoupper($comm->bet)}}</td>
                                    <td>{{strtoupper($comm->result)}}</td>
                                    <td>{{date('M d,Y h:i A',strtotime($comm->created_at))}}</td>
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

        $('tr').on('click','.btn-approve',function(){
            let x = confirm('Approve this load request?')
            let id = $(this).data('id');
            let url = $(this).data('url');
            if(x){
                window.location.href = url;
            }
        })
    });
</script>
@endsection
