<?php

use App\Helpers\FormatterHelper;

?>
<?= $this->extend("layouts/admin") ?>

<?= $this->section("content") ?>
<div class="d-flex justify-content-end">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url("my_lots_and_estates") ?>">My Lots and Estates</a></li>
            <li class="breadcrumb-item active" aria-current="page">Select Payment Option</li>
        </ol>
    </nav>
</div>
<div class="container mt-4">
    <!-- <h2 class="mb-3">Payment Options</h2> -->

    <?php
    if (!empty($lotType)) {
        $assetType = $lotType;
    } else if (!empty($estateType)) {
        $assetType = $estateType;
    }
    ?>

    <h2 class="text-center mb-3">
        <?= "Asset: $assetId ($assetType)" ?>
    </h2>

    <div class="row">
        <!-- Cash Sale -->
        <div class="col-md-4 mb-3">
            <div class="card shadow p-4 h-100">
                <h4 class="text-primary fw-bold mb-3">Cash Sale</h4>

                <div class="mb-2">
                    <small class="text-muted">Original Price</small><br>
                    <span class="fw-semibold"><?= FormatterHelper::formatPrice($pricing["total_purchase_price"]) ?></span>
                </div>

                <div class="mb-2">
                    <small class="text-muted">Cash Sale Discount</small><br>
                    <span class="text-success fw-semibold"><?= FormatterHelper::formatRate($pricing["cash_sale_discount"]) ?></span>
                </div>

                <div class="mb-2">
                    <small class="text-muted">Discounted Price</small><br>
                    <span class="text-success fw-semibold"><?= FormatterHelper::formatPrice($pricing["cash_sale"]) ?></span>
                </div>

                <div class="mb-2">
                    <small class="text-muted">Payment Deadline</small><br>
                    <span class="text-danger fw-semibold"><?= FormatterHelper::sevenDaysFromNow() ?></span>
                </div>

                <hr>

                <div class="mb-3">
                    <small class="text-muted">Total Payable Amount</small><br>
                    <span class="display-6 text-success fw-bold"><?= FormatterHelper::formatPrice($pricing["cash_sale"]) ?></span>
                </div>

                <form action="<?= base_url("payment_option_submit") ?>" method="post">
                    <input type="hidden" value="<?= $encryptedReservationId ?>" name="reservation_id">
                    <input type="hidden" value="<?= $encryptedAssetId ?>" name="asset_id">
                    <input type="hidden" value="<?= $encryptedAssetType ?>" name="reservation_type">
                    <input type="hidden" value="cash_sale" name="payment_option">

                    <button type="button" class="btn btn-primary w-100 review-button"
                        data-reservation-id="<?= $encryptedReservationId ?>"
                        data-option="Cash Sale"
                        data-amount="<?= FormatterHelper::formatPrice($pricing["cash_sale"]) ?>"
                        data-asset-id="<?= $encryptedAssetId ?>"
                        data-reservation-type="<?= $encryptedAssetType ?>"
                        data-payment-option="cash_sale">
                        Choose this option
                    </button>
                </form>
            </div>
        </div>

        <!-- 6 Months Plan -->
        <div class="col-md-4 mb-3">
            <div class="card shadow p-4 h-100">
                <h4 class="text-primary fw-bold mb-3">6 Months Plan</h4>

                <div class="mb-2">
                    <small class="text-muted">Original Price</small><br>
                    <span class="fw-semibold"><?= FormatterHelper::formatPrice($pricing["total_purchase_price"]) ?></span>
                </div>

                <div class="mb-2">
                    <small class="text-muted">6 Months Discount</small><br>
                    <span class="text-success fw-semibold"><?= FormatterHelper::formatRate($pricing["six_months_discount"]) ?></span>
                </div>

                <div class="mb-2">
                    <small class="text-muted">Discounted Price</small><br>
                    <span class="fw-semibold"><?= FormatterHelper::formatPrice($pricing["six_months"]) ?></span>
                </div>

                <div class="mb-2">
                    <small class="text-muted">Down Payment (<?= FormatterHelper::formatRate($pricing["down_payment_rate"]) ?>)</small><br>
                    <span class="fw-semibold"><?= FormatterHelper::formatPrice($pricing["six_months_down_payment"]) ?></span>
                </div>

                <div class="mb-2">
                    <small class="text-muted">Balance</small><br>
                    <span class="fw-semibold"><?= FormatterHelper::formatPrice($pricing["six_months_monthly_amortization"] * 6) ?></span>
                </div>



                <hr>

                <div class="mb-2">
                    <small class="text-muted">Monthly Payment</small><br>
                    <span class="h5 text-info fw-semibold" id="sixMonthsMonthlyPayment"><?= FormatterHelper::formatPrice($pricing["six_months_monthly_amortization"]) ?></span>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Total Payable Amount</small><br>
                    <span class="display-6 text-success fw-bold" id="sixMonthsTotalPayable"><?= FormatterHelper::formatPrice(($pricing["six_months_monthly_amortization"] * 6) + $pricing["six_months_down_payment"]) ?></span>
                </div>

                <form action="<?= base_url("payment_option_submit") ?>" method="post">
                    <p><b>Original Price:</b> <?= FormatterHelper::formatPrice($pricing["total_purchase_price"]) ?></p>
                    <p><b>Down Payment (<?= FormatterHelper::formatRate($pricing["down_payment_rate"]) ?>):</b> <?= FormatterHelper::formatPrice($pricing["down_payment"]) ?></p>
                    <p><b>Balance:</b> <?= FormatterHelper::formatPrice($pricing["balance"]) ?></p>
                    <p><b>6 Months Discount:</b> <?= FormatterHelper::formatRate($pricing["six_months_discount"]) ?></p>
                    <p><b>Monthly Payment:</b> <span id="sixMonthsMonthlyPayment"></span></p>
                    <p><b>Total Payable Amount:</b> <span id="sixMonthsTotalPayable"></span></p>
                    
                    <input type="hidden" value="<?= $encryptedReservationId ?>" name="reservation_id">
                    <input type="hidden" value="<?= $encryptedAssetId ?>" name="asset_id">
                    <input type="hidden" value="<?= $encryptedAssetType ?>" name="reservation_type">
                    <input type="hidden" value="six_months" name="payment_option">

                    <button type="button" class="btn btn-primary w-100 review-button"
                        data-reservation-id="<?= $encryptedReservationId ?>"
                        data-option="6 Months Plan"
                        data-asset-id="<?= $encryptedAssetId ?>"
                        data-reservation-type="<?= $encryptedAssetType ?>"
                        data-payment-option="six_months">
                        Choose this option
                    </button>
                </form>
            </div>
        </div>

        <!-- Installment Plan -->
        <div class="col-md-4 mb-3">
            <div class="card shadow p-4 h-100">
                <h4 class="text-primary fw-bold mb-3">Installment Plan (1–5 Years)</h4>

                <div class="mb-3">
                    <label for="payment_option" class="form-label text-muted">Select Duration</label>
                    <select id="payment_option" name="payment_option" class="form-select">
                        <option value="1">1 Year (0% Interest)</option>
                        <option value="2">2 Years (10% Interest)</option>
                        <option value="3">3 Years (15% Interest)</option>
                        <option value="4">4 Years (20% Interest)</option>
                        <option value="5">5 Years (25% Interest)</option>
                    </select>
                </div>

                <div class="mb-2">
                    <small class="text-muted">Original Price</small><br>
                    <span class="fw-semibold"><?= FormatterHelper::formatPrice($pricing["total_purchase_price"]) ?></span>
                </div>

                <div class="mb-2">
                    <small class="text-muted">Down Payment (<?= FormatterHelper::formatRate($pricing["down_payment_rate"]) ?>)</small><br>
                    <span class="fw-semibold"><?= FormatterHelper::formatPrice($pricing["down_payment"]) ?></span>
                </div>

                <div class="mb-2">
                    <small class="text-muted">Balance</small><br>
                    <span class="fw-semibold" id="installmentBalance"><?= FormatterHelper::formatPrice($pricing["monthly_amortization_one_year"] * 12) ?></span>
                </div>

                <div class="mb-2">
                    <small class="text-muted">Interest Rate</small><br>
                    <span id="interestRate" class="fw-semibold text-warning">0%</span>
                </div>

                <hr>

                <div class="mb-2">
                    <small class="text-muted">Monthly Payment</small><br>
                    <span id="monthlyPayment" class="h5 text-info fw-semibold"></span>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Total Payable Amount</small><br>
                    <span id="totalPayableAmount" class="display-6 text-success fw-bold"></span>
                </div>

                <form action="<?= base_url("payment_option_submit") ?>" method="post">
                    <input type="hidden" value="<?= $encryptedReservationId ?>" name="reservation_id">
                    <input type="hidden" value="<?= $encryptedAssetId ?>" name="asset_id">
                    <input type="hidden" value="<?= $encryptedAssetType ?>" name="reservation_type">

                    <button type="button" class="btn btn-primary w-100 review-button"
                        data-reservation-id="<?= $encryptedReservationId ?>"
                        data-option="Installment Plan"
                        data-asset-id="<?= $encryptedAssetId ?>"
                        data-reservation-type="<?= $encryptedAssetType ?>">
                        Choose this option
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="reviewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Review Payment Option</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><b>Selected Option:</b> <span id="selectedOption"></span></p>

                <div id="reviewDetails">
                    <!-- Dynamic content will go here -->
                </div>

                <p><b>Total Payable Amount:</b> <span id="reviewPayableAmount" class="fw-bold text-success"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="confirmationForm" action="<?= base_url("payment_option_submit") ?>" method="post">
                    <input type="hidden" id="confirmReservationId" name="reservation_id">
                    <input type="hidden" id="confirmAssetId" name="asset_id">
                    <input type="hidden" id="confirmReservationType" name="reservation_type">
                    <input type="hidden" id="confirmPaymentOption" name="payment_option">
                    <button type="submit" class="btn btn-primary">Confirm</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('review-button')) {
            const option = event.target.dataset.option;
            const reservationId = event.target.dataset.reservationId;
            const assetId = event.target.dataset.assetId;
            const reservationType = event.target.dataset.reservationType;
            const paymentOption = event.target.dataset.paymentOption || document.getElementById('payment_option').value;

            document.getElementById('selectedOption').innerText = option;
            document.getElementById("confirmReservationId").value = reservationId;
            document.getElementById('confirmAssetId').value = assetId;
            document.getElementById('confirmReservationType').value = reservationType;
            document.getElementById('confirmPaymentOption').value = paymentOption;

            const detailsContainer = document.getElementById("reviewDetails");
            let html = "";

            if (paymentOption === "cash_sale") {
                html = `
                <p><b>Payment Deadline:</b> <?= FormatterHelper::sevenDaysFromNow() ?></p>
                <p><b>Discount:</b> <?= FormatterHelper::formatRate($pricing["cash_sale_discount"]) ?></p>
            `;
                document.getElementById('reviewPayableAmount').innerText = "<?= FormatterHelper::formatPrice($pricing["cash_sale"]) ?>";
            } else if (paymentOption === "six_months") {
                const monthly = document.getElementById('sixMonthsMonthlyPayment').innerText;
                const total = document.getElementById('sixMonthsTotalPayable').innerText;

                html = `
                <p><b>Down Payment:</b> <?= FormatterHelper::formatPrice($pricing["down_payment"]) ?></p>
                <p><b>Monthly Payment:</b> ${monthly}</p>
                <p><b>Duration:</b> 6 Months</p>
                <p><b>Discount:</b> <?= FormatterHelper::formatRate($pricing["six_months_discount"]) ?></p>
            `;
                document.getElementById('reviewPayableAmount').innerText = total;
            } else {
                const years = document.getElementById('payment_option').value;
                const interest = document.getElementById('interestRate').innerText;
                const monthly = document.getElementById('monthlyPayment').innerText;
                const total = document.getElementById('totalPayableAmount').innerText;

                html = `
                <p><b>Down Payment:</b> <?= FormatterHelper::formatPrice($pricing["down_payment"]) ?></p>
                <p><b>Monthly Payment:</b> ${monthly}</p>
                <p><b>Duration:</b> ${years} Year(s)</p>
                <p><b>Interest Rate:</b> ${interest}</p>
            `;
                document.getElementById('reviewPayableAmount').innerText = total;
            }

            detailsContainer.innerHTML = html;

            const modal = new bootstrap.Modal(document.getElementById('reviewModal'));
            modal.show();
        }
    });
</script>

<script>
    function numberFormat(num, decimals = 2) {
        return num.toLocaleString(undefined, {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        });
    }

    function updateInstallmentDetails() {
        const years = parseInt(document.getElementById('payment_option').value);
        const interestRates = {
            1: <?= (float) $pricing["one_year_interest_rate"] ?>,
            2: <?= (float) $pricing["two_years_interest_rate"] ?>,
            3: <?= (float) $pricing["three_years_interest_rate"] ?>,
            4: <?= (float) $pricing["four_years_interest_rate"] ?>,
            5: <?= (float) $pricing["five_years_interest_rate"] ?>
        };

        const totalPurchasePrice = <?= (float) $pricing["total_purchase_price"] ?>;
        const downPayment = <?= (float) $pricing["down_payment"] ?>;
        const interestRate = interestRates[years];

        const months = years * 12;
        const monthlyInterestRate = interestRate / 12;

        let monthlyPayment = 0;

        if (monthlyInterestRate > 0) {
            // Amortization formula
            monthlyPayment = (totalPurchasePrice - downPayment) * monthlyInterestRate * Math.pow(1 + monthlyInterestRate, months) /
                (Math.pow(1 + monthlyInterestRate, months) - 1);
        } else {
            monthlyPayment = (totalPurchasePrice - downPayment) / months;
        }

        // Round to 2 decimal places to prevent floating point errors
        monthlyPayment = parseFloat(monthlyPayment.toFixed(2));
        const recalculatedBalance = parseFloat((monthlyPayment * months).toFixed(2));
        const totalPayableAmount = parseFloat((downPayment + recalculatedBalance).toFixed(2));

        // Format and display
        document.getElementById("installmentBalance").innerText = "₱" + numberFormat(recalculatedBalance);
        document.getElementById('interestRate').innerText = (interestRate * 100).toFixed(0) + "%";
        document.getElementById('monthlyPayment').innerText = "₱" + numberFormat(monthlyPayment);
        document.getElementById('totalPayableAmount').innerText = "₱" + numberFormat(totalPayableAmount);
    }



    function calculateSixMonthsPayments() {
        const totalPurchasePrice = <?= (float) $pricing["total_purchase_price"] ?>;
        const discountedPrice = <?= $pricing["six_months"] ?>;
        const downPayment = <?= (float) $pricing["down_payment"] ?>;
        const discountRate = <?= (float) $pricing["six_months_discount"] ?>;

        const balance = discountedPrice - downPayment;
        const discountedBalance = balance * (1 - discountRate);
        const monthlyPayment = discountedBalance / 6;
        const totalPayable = downPayment + discountedBalance;

        // Format and display
        // document.getElementById('sixMonthsMonthlyPayment').innerText = "₱" + numberFormat(monthlyPayment);
        // document.getElementById('sixMonthsTotalPayable').innerText = "₱" + numberFormat(totalPayable);
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateInstallmentDetails();
        calculateSixMonthsPayments();
        document.getElementById('payment_option').addEventListener('change', updateInstallmentDetails);
    });
</script>


<?= $this->endSection(); ?>