<?php 
    use App\Helpers\FormatterHelper;
?>
<?= $this->extend("layouts/admin") ?>

<?= $this->section("content") ?>

<!-- <div class="text-end">
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-credit-card-fill"></i> Pay
        </button>
        <ul class="dropdown-menu">
        <li><a role="button" class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#payCashSale">Cash Sale</a></li>
        <li><a role="button" class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#paySixMonths">6 Months</a></li>
        <li><a role="button" class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#payDownPayment">Installment: Down Payment</a></li>
        <li><a role="button" class="dropdown-item" href="#">Installment: Monthly Payment</a></li>
        </ul>
    </div>
</div> -->

<div class="table-responsive">
    <table class="table" id="table">
        <thead>
            <tr>
                <th>Payment Date</th>
                <th>Payment For</th>
                <th>Amount</th>
                <th>Payment Option</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                foreach ($payments as $payment) {
                    $paymentAmount = FormatterHelper::formatPrice($payment["payment_amount"]);

                    $assetType = FormatterHelper::determineIdType($payment["asset_id"]);

                    switch ($assetType) {
                        case "lot":
                            $assetId = FormatterHelper::formatLotId($payment["asset_id"]);
                            break;
                        case "estate":
                            $assetId = FormatterHelper::formatEstateId($payment["asset_id"]);
                            break;
                    }

                    $paymentDate = FormatterHelper::formatDate($payment["payment_date"]);
                    
                    echo "<tr>";
                    echo "<td>{$paymentDate}</td>";
                    echo "<td>{$assetId}</td>";
                    echo "<td>{$paymentAmount}</td>";
                    echo "<td>{$payment['payment_option']}</td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
</div>

<?= $this->endSection(); ?>
