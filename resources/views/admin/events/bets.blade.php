@extends('layouts.admin')
@section('styles')
<style>
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
        color: #494949;
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
                    <strong>FIGHT #{{$fight->fight_number}} - <span class="{{$fight->result}}">{{$fight->result}} {{$fight->result == 'wala' || $fight->result == 'meron' ? "WINS" : 'FIGHT'}}</span></strong>
                    {{-- <span><label for="">Floating Points</label> <span class="points"></span></span> --}}
                    <button class="btn btn-success btn-sm float-right" data-target="#redeclareFightModal" data-toggle="modal">REDECLARE WINNER</button>

                    <span class="float-right mr-3">
                        <label for="">ODDS:</label>
                        <span class="text-danger">
                            <strong>{{$odds['meron']}}%</strong>
                        </span> /
                        <span class="text-primary">
                            <strong>{{$odds['wala']}}%</strong>
                        </span>
                    </span>

                </div>
                <div class="card-body">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <th>ID</th>
                            <th>Player</th>
                            <th>Bet Amount</th>
                            <th>Amount Won</th>
                            <th>Bet</th>
                            <th>Bet Result</th>
                            <th>Op Comm</th>
                            <th>SubOp Comm</th>
                            <th>MA Comm</th>
                            <th>GA Comm</th>
                            <th>SA Comm</th>
                            <th>Date</th>
                            <th>Action/s</th>
                        </thead>
                        <tbody>
                            @php
                                $floating = 0
                            @endphp
                            @foreach ($bets as $b)
                                @if ($b->amount_won == 0)
                                   {{-- {{ $floating += $b->amount}} --}}
                                @endif
                            <tr>
                                <td>{{$b->id}}</td>
                                <td>{{$b->username}}</td>
                                <td>{{$b->amount}}</td>
                                <td>{{$b->amount_won}}</td>
                                <td><span class="{{$b->bet}}">{{$b->bet}}</span></td>
                                <td><span class="{{$b->result}}">{{$b->result}}</span></td>
                                <td>{{number_format($b->operator_commission,2,'.',',')}}</td>
                                <td>{{number_format($b->sub_operator_commission,2,'.',',')}}</td>
                                <td>{{number_format($b->master_agent_commission,2,'.',',')}}</td>
                                <td>{{number_format($b->gold_agent_commission,2,'.',',')}}</td>
                                <td>{{number_format($b->silver_agent_commission,2,'.',',')}}</td>
                                <td>{{date('m/d/Y',strtotime($b->created_at))}}</td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-sm"><strong>VIEW BETS</strong></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
              </div>
        </div>
    </div>
@include('partials.modals._newRedeclareModal')
@endsection
@section('scripts')

<script>
var redeclareWin = "{{route('redeclare.winner')}}";
var token = "{{Session::token()}}";
// var floating = "{{$floating}}";
$(document).ready(function () {
    $('.table').DataTable();

    $('.points').text(floating);
});

$('.btn-redeclare').on('click',function(){
    let fight_id = $('#fight_id').val();
    let event_id = $('#event_id').val();
    let result = $('#select-result').val();

    if(fight_id == "" || result == ""){
        Swal.fire('Please select fight number and result','','warning');
    }else{

        var data = {
            fight_id : fight_id,
            result : result,
            event_id : event_id,
            _token : token
        }

        $.ajax({
            type : "POST",
            url : redeclareWin,
            data : data,
            success:function(res){
                location.reload();
            },
            error: function(err){
                location.reload();
            }
        });
    }

})
</script>
@endsection
