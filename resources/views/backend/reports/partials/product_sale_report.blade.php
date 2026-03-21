<div class="tab-pane fade" id="pills-product-sale-report" role="tabpanel" aria-labelledby="pills-contact-tab"
    tabindex="0">
    <div class="card shadow-sm mb-5">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Product Sales Report</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle Datables">
                    <thead class="table-dark text-center">
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Product</th>
                            <th class="text-center">Quantity Sold</th>
                            <th class="text-center">Total Revenue</th>
                            <th class="text-center">Total Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($product_sale as $data)
                        <tr>
                            <td class="text-center">{{ $prod_table++ }}</td>
                            <td>{{ $data->name }}</td>
                            <td class="text-center">{{ $data->total_sold }}</td>
                            <td class="text-end">₱ {{ number_format($data->total_amount, 2) }}</td>
                            <td class="text-end text-success">₱ {{ number_format($data->total_profit, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>