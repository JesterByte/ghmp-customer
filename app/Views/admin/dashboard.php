<?= $this->extend("layouts/admin") ?>

<?= $this->section("content") ?>
<div class="container">
    <!-- Customer Overview Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-bg-success mb-3">
                <div class="card-header">My Properties</div>
                <div class="card-body">
                    <h5 class="card-title"><?= $ownedPropertiesCount ?></h5>
                    <p class="card-text">View your owned lots and estates</p>
                    <a href="<?= base_url('my_lots_and_estates') ?>" class="btn btn-light btn-sm">View Details</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-primary mb-3">
                <div class="card-header">Property Status</div>
                <div class="card-body">
                    <h5 class="card-title">With Installment</h5>
                    <p class="card-text">Next Due: May 15, 2025</p>
                    <a href="<?= base_url('payments') ?>" class="btn btn-light btn-sm">View Details</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-info mb-3">
                <div class="card-header">Memorial Services</div>
                <div class="card-body">
                    <h5 class="card-title"><?= $scheduledMemorialServices != "0" ? $scheduledMemorialServices : "No" ?> Scheduled</h5>
                    <p class="card-text">View memorial service details</p>
                    <a href="<?= base_url('services') ?>" class="btn btn-light btn-sm">View Services</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction History -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-history"></i> Recent Transactions
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Apr 05, 2025</td>
                                    <td>Monthly Payment - Lot A123</td>
                                    <td>₱15,000.00</td>
                                    <td><span class="badge bg-success">Paid</span></td>
                                </tr>
                                <tr>
                                    <td>Mar 05, 2025</td>
                                    <td>Monthly Payment - Lot A123</td>
                                    <td>₱15,000.00</td>
                                    <td><span class="badge bg-success">Paid</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
