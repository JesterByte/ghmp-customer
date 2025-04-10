<?= $this->extend("layouts/admin") ?>

<?= $this->section("content") ?>
<div class="container">
    <!-- Customer Overview Cards -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card text-bg-success mb-3">
                <div class="card-header">My Assets</div>
                <div class="card-body">
                    <h5 class="card-title"><?= $ownedPropertiesCount ?> Assets</h5>
                    <p class="card-text">
                        <?php

                                            use App\Helpers\FormatterHelper;

                        if (empty($nextPaymentDueDate)) {
                            $nextPaymentDueDate = "N/A";
                        } else {
                            $nextPaymentDueDate = date("M d, Y", strtotime($nextPaymentDueDate));
                        }
                        ?>
                        Next Payment Due: <?= $nextPaymentDueDate ?>
                    </p>
                    <a href="<?= base_url('my_lots_and_estates') ?>" class="btn btn-light btn-sm">View Properties</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-bg-info mb-3">
                <div class="card-header">Memorial Services</div>
                <div class="card-body">
                    <h5 class="card-title"><?= $scheduledMemorialServices != "0" ? $scheduledMemorialServices : "No" ?> Scheduled</h5>
                    <p class="card-text">View memorial service details</p>
                    <a href="<?= base_url('my_memorial_services') ?>" class="btn btn-light btn-sm">View Services</a>
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
                                <?php 
                                    foreach ($lastTwoPayments as $row) {
                                        $date = date("M d, Y", strtotime($row["date"]));
                                        $description = $row["description"];
                                        $amount = FormatterHelper::formatPrice($row["amount"]);
                                        $status = '<span class="badge bg-success">' . $row["status"] . '</span>';

                                        echo "<tr>";
                                        echo "<td>$date</td>";
                                        echo "<td>$description</td>";
                                        echo "<td>$amount</td>";
                                        echo "<td>$status</td>";
                                        echo "</tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Payment History Chart -->
        <div class="col-md-6">
            <div class="card" style="height: 300px;">
                <div class="card-header">
                    <i class="fas fa-chart-line"></i> Payment History
                </div>
                <div class="card-body">
                    <canvas id="paymentChart" style="max-height: 220px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Property Distribution Chart -->
        <div class="col-md-6">
            <div class="card" style="height: 300px;">
                <div class="card-header">
                    <i class="fas fa-chart-pie"></i> My Properties
                </div>
                <div class="card-body">
                    <canvas id="propertyChart" style="max-height: 220px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Payment History Line Chart
    const paymentCtx = document.getElementById('paymentChart').getContext('2d');
    new Chart(paymentCtx, {
        type: 'line',
        data: {
            labels: <?= $chartData['paymentMonths'] ?>,
            datasets: [{
                label: 'Monthly Payments',
                data: <?= $chartData['paymentAmounts'] ?>,
                borderColor: '#0d6efd',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                title: {
                    display: true,
                    text: '6-Month Payment History'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Property Distribution Pie Chart
    const propertyCtx = document.getElementById('propertyChart').getContext('2d');
    new Chart(propertyCtx, {
        type: 'doughnut',
        data: {
            labels: ['Lawn Lots', 'Estate Mausoleums'],
            datasets: [{
                data: <?= $chartData['propertyCounts'] ?>,
                backgroundColor: [
                    '#198754',
                    '#0dcaf0'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Property Distribution'
                },
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
<?= $this->endSection() ?>