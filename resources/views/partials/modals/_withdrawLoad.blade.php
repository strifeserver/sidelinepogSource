<div class="modal fade" id="returnCreditsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="{{ route('return.credits') }}" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Withdraw Load</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label>Amount to Withdraw</label>
                        <input type="number" class="form-control" name="amount" required>
                        <input type="hidden" class="form-control" name="user_id" id="userIdRet" required>
                    </div>
                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="remarks" class="form-control" rows="5"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
