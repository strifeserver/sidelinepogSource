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
                <strong>LOAD LOGS</strong>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <th>#</th>
                            <th>TO</th>
                            <th>FROM</th>
                            <th>AMOUNT</th>
                            <th>ENDING BALANCE</th>
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
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        $('.table').DataTable({
            "ordering": false
        });
        $('.select').select2();
    });
</script>
@endsection
