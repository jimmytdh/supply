<form action="{{ route('submit.delivery') }}" method="post">
{{ csrf_field() }}
<input type="hidden" name="item_id" value="{{ $item->id }}">
<input type="hidden" name="po_id" value="{{ $po->id }}">
<div class="modal-body">
    <div class="row">
        <table class="table border">
            <tr>
                <th class="bg-info" colspan="2">Description</th>
            </tr>
            <tr>
                <td colspan="2">{!! nl2br($item->description) !!}</td>
            </tr>
            <tr>
                <th class="bg-info" width="50%">Unit Cost</th>
                <td>{{ number_format($item->unit_cost,2) }}</td>
            </tr>
            <?php
            $deliver = \App\Http\Controllers\DeliveryController::unDeliveredItems($po->id,$item->id,$purchaseItem->qty);
            ?>
            <tr>
                <th class="bg-info">For Delivery</th>
                <td>{{ $deliver }} {{ $purchaseItem->unit }}</td>
            </tr>
            @if($deliver > 0)
            <tr>
                <th class="bg-info">Qty Delivered</th>
                <td class="bg-warning">
                    <input type="number" max="{{ $deliver }}" min="1" value="1" class="form-control" name="qty" placeholder="Enter Qty Delivered" required>
                </td>
            </tr>
            @endif
        </table>
    </div>
    @if($deliver > 0)
    <label for="inspectors">Select Inspectors:<br>
        <small class="text-muted"><em>(Hold CTRL Key to Multi Select)</em></small>
    </label>
    <select multiple name="inspectors[]" size="5" id="inspectors" class="form-control">
        @foreach($inspectors as $ins)
            <option value="{{ $ins->user_id }}">{{ $ins->lname }}, {{ $ins->fname }}</option>
        @endforeach
    </select>
    <div class="form-group">
        <label for="remarks">Remarks</label>
        <textarea name="remarks" style="resize: none;" id="remarks" rows="3" class="form-control" placeholder="Enter Remarks"></textarea>
    </div>
    @else
    <div class="alert alert-success">
        <i class="fa fa-check-circle"></i> Complete Delivery
    </div>
    @endif
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    @if($deliver > 0)
    <button type="submit" class="btn btn-primary">Submit</button>
    @endif
</div>
</form>
