@extends('layouts.admin')
@section('styles')
<style>
    .select2-container{
        display: block !important;
    }
    .select2-selection{
        height: 38px !important;
    }
</style>
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <strong>COMMISSION HISTORY</strong>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#ma">My Commissions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#agents">My Sub-Agents Commissions</a>
                    </li>
                </ul>
                <div class="tab-content mt-3">
                    <div class="tab-pane active" id="ma">
                        <div class="table-responsive">
                            <table class="table table-bordered table-condensed">
                                <thead>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Player Bet</th>
                                    <th>Commission Earned</th>
                                    <th>Fight Result</th>
                                    <th>Date/Time</th>
                                </thead>
                                <tbody>
                                    @foreach ($maCommissions as $i => $bet)
                                        @if($bet->master_agent_commission > 0)
                                        <tr>
                                            <td>{{$i + 1}}</td>
                                            <td>{{$bet->name}}</td>
                                            <td>{{$bet->amount}}</td>
                                            <td>{{$bet->master_agent_commission}}</td>
                                            <td>{{strtoupper($bet->result)}}</td>
                                            <td>{{date('M d,Y h:i A',strtotime($bet->created_at))}}</td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="agents">
                        <div class="table-responsive">
                            <table class="table table-bordered table-condensed">
                                <thead>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Player Bet</th>
                                    <th>Commission Earned</th>
                                    <th>Fight Result</th>
                                    <th>Date/Time</th>
                                </thead>
                                <tbody>
                                    @foreach ($aCommissions as $i => $bet)
                                        @if($bet->agent_commission > 0)
                                            <tr>
                                                <td>{{$i + 1}}</td>
                                                <td>{{$bet->name}}</td>
                                                <td>{{$bet->amount}}</td>
                                                <td>{{$bet->agent_commission}}</td>
                                                <td>{{strtoupper($bet->result)}}</td>
                                                <td>{{date('M d,Y h:i A',strtotime($bet->created_at))}}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
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
