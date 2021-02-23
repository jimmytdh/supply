<div class="modal-body">
    <div class="row">
        <div class="col-md-7">
            <table class="table border table-sm">
                <tr>
                    <th class="bg-gray" colspan="2">Description</th>
                </tr>
                <tr>
                    <td colspan="2">{!! nl2br($item->description) !!}</td>
                </tr>
                <tr>
                    <th class="bg-gray">Unit Cost</th>
                    <td>{{ number_format($item->unit_cost,2) }}</td>
                </tr>
                <?php
                    $deliver = \App\Http\Controllers\DeliveryController::unDeliveredItems($po->id,$item->id,$purchaseItem->qty);
                ?>
                <tr>
                    <th class="bg-gray">For Delivery</th>
                    <td>{{ $deliver }}</td>
                </tr>
            </table>
        </div>
        <div class="col-md-5">
            <table class="table border table-sm">
                <tr>
                    <th class="bg-gray">Select Inspectors</th>
                </tr>
                @foreach($inspectors as $ins)
                <tr>
                    <td>
                        <label>
                            <input type="checkbox" name="inspectors[]" value="{{ $ins->user_id }}">
                            {{ $ins->lname }}, {{ $ins->fname }}
                        </label>
                    </td>
                </tr>
                @endforeach
            </table>

            <table class="table border table-sm">
                <tr>
                    <th class="bg-gray">Select Item Type</th>
                </tr>
                <tr>
                    <td>
                        <label>
                            <input type="radio" name="type" value="equipment" checked>
                            Equipment & Semi-Expendables
                        </label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>
                            <input type="radio" name="type" value="supplies">
                            Supplies & Materials
                        </label>
                    </td>
                </tr>
            </table>
        </div>
    </div>

</div>

<div class="modal-footer">
    <div class="input-group">
        {{ csrf_field() }}
        <input type="number" min="1" value="1" class="form-control" name="qty" placeholder="Enter Qty Delivered" required>
        <span class="input-group-append">
            <button type="submit" class="btn btn-info">
                <i class="fa fa-save"></i> Save
            </button>
        </span>
    </div>
</div>
