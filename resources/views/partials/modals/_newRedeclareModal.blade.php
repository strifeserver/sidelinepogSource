<div class="modal fade" id="redeclareFightModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Redeclare Winner</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label>Declare Winner</label>
                        <select class="form-control" id="select-result" name="result">
                            <option value="" selected>Choose...</option>
                            <option value="meron">Meron</option>
                            <option value="wala">Wala</option>
                            <option value="draw">Draw</option>
                            <option value="cancelled">Cancel</option>
                        </select>
                        <input type="hidden" id="fight_id" name="fight_id" value="{{$fight->id}}">
                        <input type="hidden" id="event_id" name="event_id" value="{{$fight->event_id}}">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-redeclare">Redeclare Winner</button>
                </div>
        </div>
    </div>
</div>
