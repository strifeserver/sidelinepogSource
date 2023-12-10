@extends('layouts.admin')
@section('styles')
<style>
    .select2-container .select2-selection--single {
        height: calc(2.25rem + 2px) !important;
    }
</style>
@endsection
@section('content')
<div class="row">
    {{-- <div class="col-12 col-sm-6 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Select Agent</label>
                            <select name="agent" class="select2 form-control"></select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Select Date</label>
                            <input type="date" class="form-control">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div> --}}
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <strong>MY WITHDRAWALS</strong>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-condensed">
                    <thead>
                        <th>Method</th>
                        <th>Acc No.</th>
                        <th>Acc Name</th>
                        <th>Amount</th>
                        <th>Date Requested</th>
                        <th>Action/s</th>
                    </thead>
                    <tbody>
                        @foreach ($withdrawals as $n)
                            <tr>
                                <td>{{strtoupper($n->withdraw_method)}}</td>
                                <td>{{$n->account_number}}</td>
                                <td>{{strtoupper($n->account_name)}}</td>
                                <td>{{$n->amount}}</td>
                                <td>{{date('m/d/Y',strtotime($n->created_at))}}</td>
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

@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        $('.table').DataTable();
        $('.select2').select2();
    });
</script>
@endsection
