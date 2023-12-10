<div class="modal fade" id="commissionPercentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="{{ route('set.commission') }}" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Set Commission</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label>Current Commission (%)</label>
                        <input type="number" min="1" max="30" step="0.1" class="form-control" name="commission" required>
                        <input type="hidden" class="form-control" name="user_id" id="commission_user_id" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Set Commission</button>
                </div>
            </form>
        </div>
    </div>
</div>
