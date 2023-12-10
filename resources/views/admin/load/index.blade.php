@extends('layouts.admin')
@section('styles')

@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>LOAD HISTORY</strong>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#load">CASH INS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#withdraw">CASH OUTS</a>
                        </li>
                    </ul>
                    <div class="tab-content mt-3">
                        <div class="tab-pane active" id="load">
                            <div class="table-responsive">
                                <table class="table table-bordered table-condensed">
                                    <thead>
                                        <th>#</th>
                                        <th>TO</th>
                                        <th>FROM</th>
                                        <th>AMOUNT</th>
                                        <th>BALANCE</th>
                                        <th>REMARKS</th>
                                        <th>NOTES</th>
                                        <th>DATE/TIME</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($loads as $i => $n)
                                            <tr>
                                                <td>{{$i+1}}</td>
                                                <td>{{($n->user_to)}}</td>
                                                <td>{{($n->user_from)}}</td>
                                                <td>{{bcdiv($n->amount,1,2)}}</td>
                                                <td>{{bcdiv($n->ending_balance,1,2)}}</td>
                                                <td>{{($n->remarks)}}</td>
                                                <td>{{($n->user_notes)}}</td>
                                                <td>{{date('m/d/Y h:i:s A',strtotime($n->created_at))}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="withdraw">
                            <div class="table-responsive">
                                <table class="table table-bordered table-condensed">
                                    <thead>
                                        <th>#</th>
                                        <th>FROM</th>
                                        <th>TO</th>
                                        <th>AMOUNT</th>
                                        <th>REMARKS</th>
                                        <th>NOTES</th>
                                        <th>DATE/TIME</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($withdraws as $i => $n)
                                            <tr>
                                                <td>{{$i+1}}</td>
                                                <td>{{($n->user_to)}}</td>
                                                <td>{{($n->user_from)}}</td>
                                                <td>{{floor($n->amount*100)/100}}</td>
                                                <td>{{($n->remarks)}}</td>
                                                <td>{{($n->user_notes)}}</td>
                                                <td>{{date('m/d/Y h:i:s A',strtotime($n->created_at))}}</td>
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
