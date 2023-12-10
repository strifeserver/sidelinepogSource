@extends('layouts.admin')
@section('styles')
<style>
    .alert-text{
        font-size: 13px;
        font-style: italic;
    }
</style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>SYSTEM SETTINGS</strong>

                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <span class="alert-text">Changes are automatically saved upon typing. Changes here will be applied to all OPERATORS</span>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Max Player Deduction</label>
                                    <input type="text" name="value" id="player_deduction" value="{{$deduction->value}}" onkeyup="saveChanges({{$deduction->id}},this);" class="form-control deduction-values">
                                </div>
                                {{ csrf_field() }}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Base Operator Commission</label>
                                    <input type="text" name="value" id="operator_commission" value="{{$operator->value}}" onkeyup="saveChanges({{$operator->id}},this);" class="form-control deduction-values">
                                </div>
                                {{ csrf_field() }}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Bet Multiplier</label>
                                    <input type="text" name="value" id="multiplier" value="{{$timer->value}}" onkeyup="saveChanges({{$timer->id}},this);" class="form-control deduction-values">
                                </div>
                                {{ csrf_field() }}
                            </div>
                        </div>
                    </form>
                </div>
              </div>
        </div>
    </div>
@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        $('.table').DataTable();

        // $('.deduction-values').on('keyup',function(){
        //     var sum = 0;
        //     $('.deduction-values').each(function(){
        //         sum += parseFloat(this.value);
        //     });

        //     $('#player_deduction').val(sum);
        // })
    });

    function ValidateNumber(strNumber) {
        var regExp = new RegExp("^\\d+");
        var isValid = regExp.test(strNumber);
        return isValid;
    }

    function saveChanges(id,el){
        let formData = {
            id : id,
            value : el.value,
            _token : "{{Session::token()}}"
        }

        if(ValidateNumber(el.value)){
            $.ajax({
                type : 'POST',
                url : "{{route('save.settings')}}",
                data : formData,
                success:function(res){
                    console.log(res);
                },
                error:function(err){
                    console.log(err);
                }
            })
        }

    }
</script>
@endsection
