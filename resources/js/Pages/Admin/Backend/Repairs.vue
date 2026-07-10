<template>
<div>
    <div class="container-fluid mt-3">
        <div class="card shadow-sm">
            <div class="card-header">
                <button type="button" class="btn btn-primary float-end px-4" data-bs-toggle="modal" data-bs-target="#modalRepairForm"><i class="fa-solid fa-plus">Add Repair</i></button>
                <h4>Repairs</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Repair No.</th>
                            <th class="text-center">Customer</th>
                            <th class="text-center">Device</th>
                            <th class="text-center">Total Amount</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Pickup Deadline</th>
                            <th class="text-center">Action</th>
                        </tr>
                        <tbody>
                            <td class="text-center align-middle>
                                <p></p>
                            </td>
                            <td class="text-center align-middle>
                                <p></p>
                            </td>
                            <td class="text-center align-middle>
                                <p></p>
                            </td>
                            <td class="text-center align-middle>
                                <p></p>
                            </td>
                            <td class="text-center align-middle>
                                <p></p>
                            </td>
                            <td class="text-center align-middle>
                                <p></p>
                            </td>
                            <td class="text-center align-middle>
                                <p></p>
                            </td>
                            <td class="text-center align-middle>
                                <button class="btn btn-sm btn-info text-white me-1" data-bs-toggle="modal" data-bs-target="#editRepairForm"><i class="fa-solid fa-eye"></i></button>
                            </td>
                        </tbody>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalRepairForm" tabindex="-1">
        <div class="modal-dialog modal-center">
            <div class="modal-content">
                <form>
                    <div class="modal-header bg-dark text-white">
                        Add Repair
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Customer Name:</label>
                                <input type="text" class="form-control"/>
                            </div>
                            <div class="col-md-6">
                                <label>Contact Number:</label>
                                <input type="text" class="form-control"/>
                            </div>
                            <div class="col-md-6">
                                <label>Device Type:</label>
                                <select class="form-select">
                                    <option value="laptop">Laptop</option>
                                    <option value="printer">Printer</option>
                                    <option value="desktop">Desktop</option>
                                    <option value="router">Router</option>
                                    <option value="others">Others</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Device Brand/Model:</label>
                                <input type="text" class="form-control"/>
                            </div>
                            <div class="col-md-12">
                                <label>Contact Number:</label>
                                <textarea class="form-control" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger px-3">Close</button>
                        <button class="btn btn-success px-3">Save Repair</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editRepairForm" tabindex="-1">
        <div class="modal-dialog modal-center">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Repair Details</h4>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <p><strong>Cusomter: </strong>{{ Data }}</p>
                                    <p><strong>Contact: </strong>{{ Data }}</p>
                                    <p><strong>Device: </strong>{{ Data }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <p><strong>Status: </strong>{{ Data }}</p>
                                    <p><strong>Received: </strong>{{ Data }}</p>
                                    <p><strong>Pickup Date: </strong>{{ Data }}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <p>Issue Description:</p>
                                <p>{{ Data }}</p>
                            </div>
                            <div class="form-group">
                                <p>Diagnosis</p>
                                <p>{{ Data }}</p>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="bg-light p-3 rounded border">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Labor Fee</span>
                                            <span class="labor_fee fw-semibold"></span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Parts Total</span>
                                            <span class="parts_amount fw-semibold"></span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Change</span>
                                            <span class="changeDisplayAmount fw-semibold">P 0.00</span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between fs-5">
                                            <strong>Total Amount</strong>
                                            <strong class="overallAmount text-success"></strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="bg-white p-3 rounded border">
                                        <div class="mb-3">
                                            <label class="form-label">Payment Type</label>
                                            <select class="form-select" id="payment_type">
                                                <option value="cash">Cash</option>
                                                <option value="gcash">GCash</option>
                                                <option value="bank_transfer">Bank Transfer</option>
                                            </select>
                                            </div>
                                        <div class="mb-3">
                                            <label class="form-label">Amount Paid</label>
                                            <input type="number" step="0.01" class="form-control"id="amount_paid">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
