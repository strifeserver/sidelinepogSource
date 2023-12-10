@extends('layouts.admin')
@section('styles')

@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>LOADING HISTORY</strong>
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
                            <div class="table-responsive">
                                <table class="table table-bordered table-condensed">
                                    <thead>
                                        <th>Name</th>
                                        <th>Contact</th>
                                        <th>Amount</th>
                                        <th>Reference Number</th>
                                        <th>Date Requested</th>
                                        <th>Action/s</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($new as $n)
                                            <tr>
                                                <td>{{ucwords($n->name)}}</td>
                                                <td>{{$n->contact_number}}</td>
                                                <td>{{floor($n->amount*100)/100}}</td>
                                                <td>{{strtoupper($n->reference_number)}}</td>
                                                <td>{{date('m/d/Y',strtotime($n->created_at))}}</td>
                                                <td>
                                                    <a href="{{ route('show.deposits',$n->id) }}" class="btn btn-primary btn-sm">VIEW</a>
                                                    <button href="#" class="btn btn-success btn-approve btn-sm" data-url="{{ route('approve.load', $n->id) }}" data-id="{{$n->id}}">APPROVE</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="completed">
                            <div class="table-responsive">
                                <table class="table table-bordered table-condensed">
                                    <thead>
                                        <th>Name</th>
                                        <th>Contact</th>
                                        <th>Amount</th>
                                        <th>Reference Number</th>
                                        <th>Date Requested</th>
                                        <th>Action/s</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($complete as $n)
                                            <tr>
                                                <td>{{ucwords($n->name)}}</td>
                                                <td>{{$n->contact_number}}</td>
                                                <td>{{floor($n->amount*100)/100}}</td>
                                                <td>{{strtoupper($n->reference_number)}}</td>
                                                <td>{{date('m/d/Y',strtotime($n->created_at))}}</td>
                                                <td>
                                                    <a href="{{ route('show.deposits',$n->id) }}" class="btn btn-primary btn-sm">VIEW</a>
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
