@extends('admin.body.header')
@section('admin')
@php
$i = 1;
@endphp
<div class="container-fluid px-4">
    <h1 class="mt-4 mb-4">Stock Movements</h1>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">From Date</label>
                    <input type="date" name="fromDate" id="fromDate" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">To Date</label>
                    <input type="date" name="toDate" id="toDate" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Movement Type</label>
                    <select class="form-select" name="type" id="type">
                        <option value="">All</option>
                        <option value="sale">Sale</option>
                        <option value="restock">Restock</option>
                        <option value="return">Return</option>
                        <option value="adjustment">Adjustment</option>
                    </select>
                </div>
                <div class="col-md-3 d-grid">
                    <button class="btn btn-dark" onclick="GenerateDateRangeStockMovements()">
                        Generate
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
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
                            <td>{{ $i++ }}</td>
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

<script>
function GenerateDateRangeStockMovements() {
    const SelectfromDate = document.getElementById('fromDate').value;
    const SelecttoDate = document.getElementById('toDate').value;
    const SelectType = document.getElementById('type').value;

    if (!SelectfromDate || !SelecttoDate || !SelectType) {
        toastr.error('Error, Date Range and Movement Type Fields are Empty!');
        return;
    }
    const url =
        `{{ route('dateRange.stockMovements') }}?fromDate=${SelectfromDate}&toDate=${SelecttoDate}&type=${SelectType}`;
    printNewWindow(url);
}

function printNewWindow(url) {
    const printWindow = window.open(url, '_blank');
    printWindow.addEventListener('load', function() {
        printWindow.print();
    }, true);
}
</script>
@endsection