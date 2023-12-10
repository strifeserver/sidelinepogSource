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
                    <strong>VIEW WITHDRAWAL DETAILS</strong>

                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <td><label for="">Name:</label></td>
                                    <td>{{$wth->name}}</td>
                                </tr>

                                <tr>
                                    <td><label for="">Contact #:</label></td>
                                    <td>{{$wth->contact_number}}</td>
                                </tr>

                                <tr>
                                    <td><label for="">Amount:</label></td>
                                    <td>{{$wth->amount}}</td>
                                </tr>

                                <tr>
                                    <td><label for="">Withdraw Method:</label></td>
                                    <td>{{strtoupper($wth->withdraw_method)}}</td>
                                </tr>

                                <tr>
                                    <td><label for="">Account Name:</label></td>
                                    <td>{{$wth->account_name}}</td>
                                </tr>

                                <tr>
                                    <td><label for="">Account Number:</label></td>
                                    <td>{{$wth->account_number}}</td>
                                </tr>

                                <tr>
                                    <td><label for="">Date Requested:</label></td>
                                    <td>{{date('M d, Y - h:i A',strtotime('+8 hours',strtotime($wth->created_at)))}}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">

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
