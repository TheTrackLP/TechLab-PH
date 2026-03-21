<div class="tab-pane fade" id="pills-stock-report" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Stock Movements Report</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center Datables">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Date</th>
                            <th class="text-start">Product</th>
                            <th class="text-center">Type</th>
                            <th class="text-center">Qty</th>
                            <th class="text-center">Reference</th>
                            <th class="text-center">Created By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stockMove as $move)
                        <tr>
                            <td>{{ $stockMovenum++ }}</td>
                            <td>{{ date('M d, Y', strtotime($move->created_at)) }}</td>
                            <td class="text-start">{{ $move->name }}</td>
                            <td>
                                @if($move->type == 'sale')
                                <span class="badge bg-danger">Sale</span>
                                @elseif($move->type == 'restock')
                                <span class="badge bg-success">Restock</span>
                                @elseif($move->type == 'adjustment')
                                <span class="badge bg-warning">Adjustment</span>
                                @elseif($move->type == 'return')
                                <span class="badge bg-primary">Return</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($move->type == 'sale')
                                <span class="text-danger fw-bold">{{ $move->quantity }}</span>
                                @else
                                <span class="text-success fw-bold">{{ $move->quantity }}</span>
                                @endif
                            </td>
                            <td>Reference:
                                <span class="fw-bold">{{ $move->reference_no }}</span>
                            </td>
                            <td>Admin</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>