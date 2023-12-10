@extends('layouts.admin')
@section('styles')
<style>
    .trend-item {
        width: 25px;
        height: 25px;
        line-height: 25px;
        font-size: 10px;
        text-align: center;
        border-radius: 50%;
    }

    .trend-table td, .trend-table th {
        padding: .1rem !important;
        border-top: none !important;
        height: 32px !important;
    }

    .btn-declare{
        width: 100px !important;
        max-width: 100% !important;
        max-height: 100% !important;
        height: 100px !important;
        text-align: center;
        padding: 0px;
        font-size:14px;
        margin-right: 55px;
    }

    .bg-disabled{
        background-color: rgb(230, 227, 227);
    }
</style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>VIEW DEPOSIT DETAILS</strong>

                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <strong>DEPOSIT DETAILS</strong>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td><label for="">Name:</label></td>
                                            <td>{{$dp->name}}</td>
                                        </tr>

                                        <tr>
                                            <td><label for="">Contact #:</label></td>
                                            <td>{{$dp->contact_number}}</td>
                                        </tr>

                                        <tr>
                                            <td><label for="">Reference No:</label></td>
                                            <td>{{$dp->reference_number}}</td>
                                        </tr>

                                        <tr>
                                            <td><label for="">Amount:</label></td>
                                            <td>{{$dp->amount}}</td>
                                        </tr>

                                        <tr>
                                            <td><label for="">Date Requested:</label></td>
                                            <td>{{date('M d, Y',strtotime($dp->created_at))}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <strong>GCASH SCREENSHOT</strong>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <img src="{{ asset($dp->screenshot) }}" class="img-fluid img-thumbnail">
                                        </div>
                                    </div>
                                </div>
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
    let trs = ''
    for (let index = 0; index < 6; index++) {
        trs+='<tr>'
            for (let index = 0; index < 45; index++) {
                trs +='<td><div class="trend-item bg-disabled"></div></td>'
            }
        trs+='</tr>'
    }
    $('.trend-table').append(trs);

</script>
@endsection
