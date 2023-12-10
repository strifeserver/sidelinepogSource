<div class="modal fade" id="addCreditsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="{{ route('request.load') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Credits</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    @if(Auth::user()->type == 'master_agent')
                        <div class="form-group">
                            <label>Select Loader</label>
                            <select class="form-control select" name="requested_to">
                                @foreach ($loaders as $l)
                                    <option value="{{$l->id}}">{{$l->name}}</option>
                                @endforeach
                            </select>
                        </div>

                    @endif
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label>Amount</label>
                        <input type="number" class="form-control" name="amount">
                    </div>
                    <div class="form-group">
                        <label>GCash Reference Number</label>
                        <input type="text" class="form-control" name="reference_number">
                    </div>

                    <div class="form-group">
                        <label>GCash Screenshot</label>
                        <input type="file" class="form-control-file" accept="image/*" name="screenshot">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
