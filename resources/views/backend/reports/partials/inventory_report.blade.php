<div class="tab-pane fade" id="pills-inventory-report" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
    <div class="card shadow-sm">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h3 class="mb-0">Inventory Report</h3>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Category</label>
                    <select class="select2" id="iCategory" name="iCategory">
                        <option></option>
                        @foreach ($categories as $row)
                        <option value="{{ $row->id }}">{{ $row->category_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select class="select2" id="iStatus" name="iStatus">
                        <option></option>
                        <option value="normal">Normal</option>
                        <option value="low">Low Stocks</option>
                        <option value="out">Out of Stocks</option>
                    </select>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-outline-dark px-5" onclick="PrintInventoryReport()">
                        <i class="fa-solid fa-print me-1"></i> Print
                    </button>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle Datables">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Product</th>
                        <th class="text-center">SKU</th>
                        <th class="text-center">Category</th>
                        <th class="text-center">Stock Qty</th>
                        <th class="text-center">Minimum Stock</th>
                        <th class="text-center">Cost Price</th>
                        <th class="text-center">Selling Price</th>
                        <th class="text-center">Stock Value</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($inventories as $product)
                    <tr>
                        <td class="text-center">{{ $productNum++ }}</td>
                        <td>{{ $product->name }}</td>
                        <td class="text-center">{{ $product->sku }}</td>
                        <td class="text-center">{{ $product->category_name }}</td>
                        <td class="text-center">{{ $product->stock_quantity }}</td>
                        <td class="text-center">{{ $product->minimum_stock }}</td>
                        <td class="text-end">₱{{ number_format($product->cost_price, 2) }}</td>
                        <td class="text-end">₱ {{ number_format($product->selling_price,2) }}</td>
                        <td class="text-end">₱
                            {{ number_format($product->stock_quantity * $product->selling_price, 2) }}</td>
                        <td class="text-center">
                            @if ($product->stock_quantity == 0)
                            <span class="badge bg-danger">Out of Stock</span>
                            @elseif($product->stock_quantity <= $product->minimum_stock)
                                <span class="badge bg-warning">Low Stocks</span>
                                @elseif($product->stock_quantity > $product->minimum_stock)
                                <span class="badge bg-success">Normal</span>
                                @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function PrintInventoryReport() {
    const inventoryCategory = document.getElementById("iCategory").value;
    const inventoryStatus = document.getElementById("iStatus").value;

    let url = `{{ route('inventory.report') }}?`;
    if (inventoryCategory) {
        url += `iCategory=${inventoryCategory}&`;
    }
    if (inventoryStatus) {
        url += `iStatus=${inventoryStatus}`;
    }

    printNewWindow(url);
}
</script>