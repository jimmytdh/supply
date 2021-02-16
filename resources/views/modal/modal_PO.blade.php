<div class="modal" tabindex="-1" role="dialog" id="print">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title">Schedule Date Range:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ url('/po/report') }}" method="post">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label>Select Date:</label>
                        <input type="date" class="form-control" name="date" value="{{ date("Y-m-d") }}" required>
                    </div>
                    <div class="form-group">
                        <label>Select Time:</label>
                        <input type="time" class="form-control" name="time" value="09:00:00" required>
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
