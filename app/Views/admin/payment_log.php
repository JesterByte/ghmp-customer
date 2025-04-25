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
                <th class="text-center">Sorter</th>
                <th class="text-center">Payment Date</th>
                <th class="text-center">Payment For</th>
                <th class="text-center">Amount</th>
                <th class="text-center">Payment Option</th>
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
                    echo "<td class='text-center'>{$row["payment_date"]}</td>";
                    echo "<td class='text-center'>{$paymentDate}</td>";
                    echo "<td class='text-center'>{$assetId}</td>";
                    echo "<td class='text-center'>{$paymentAmount}</td>";
                    echo "<td class='text-center'>{$payment['payment_option']}</td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
</div>

<?= $this->endSection(); ?>
