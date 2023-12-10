@extends('layouts.admin')
@section('styles')

@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>WITHDRAWALS</strong>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#new">New Requests</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#completed">Completed</a>
                        </li>
                    </ul>

                    <div class="tab-content mt-3">
                        <div class="tab-pane active" id="new">
                            <table class="table table-bordered table-condensed">
                                <thead>
                                    <th>Name</th>
                                    <th>Method</th>
                                    <th>Acc No.</th>
                                    <th>Acc Name</th>
                                    <th>Amount</th>
                                    <th>Date Requested</th>
                                    <th>Action/s</th>
                                </thead>
                                <tbody>
                                    @foreach ($new as $n)
                                        <tr>
                                            <td>{{strtoupper($n->name)}}</td>
                                            <td>{{strtoupper($n->withdraw_method)}}</td>
                                            <td>{{$n->account_number}}</td>
                                            <td>{{strtoupper($n->account_name)}}</td>
                                            <td>{{$n->amount}}</td>
                                            <td>{{date('m/d/Y - h:i A',strtotime('+8 hours',strtotime($n->created_at)))}}</td>
                                            <td>
                                                <a href="{{ route('show.withdrawal', $n->id) }}" class="btn btn-primary btn-sm">VIEW</a>
                                                <button class="btn btn-success btn-confirm btn-sm" data-id="{{$n->id}}" data-url="{{ route('approve.credits', $n->id) }}">APPROVE</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="completed">
                            <table class="table table-bordered table-condensed">
                                <thead>
                                    <th>Name</th>
                                    <th>Method</th>
                                    <th>Acc No.</th>
                                    <th>Acc Name</th>
                                    <th>Amount</th>
                                    <th>Date Requested</th>
                                    <th>Action/s</th>
                                </thead>
                                <tbody>
                                    @foreach ($complete as $n)
                                        <tr>
                                            <td>{{strtoupper($n->name)}}</td>
                                            <td>{{strtoupper($n->withdraw_method)}}</td>
                                            <td>{{$n->account_number}}</td>
                                            <td>{{strtoupper($n->account_name)}}</td>
                                            <td>{{$n->amount}}</td>
                                            <td>{{date('m/d/Y - h:i A',strtotime('+8 hours',strtotime($n->created_at)))}}</td>
                                            <td>
                                                <a href="{{ route('show.withdrawal', $n->id) }}" class="btn btn-primary btn-sm">VIEW</a>
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
    </div>
@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        $('.table').DataTable();

        $('tr').on('click','.btn-confirm',function(){
            let x = confirm('Approve this withdraw request?')
            let id = $(this).data('id');
            let url = $(this).data('url');
            if(x){
                window.location.href = url;
            }
        })
    });
</script>
@endsection
