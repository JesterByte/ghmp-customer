<?php 
    use App\Helpers\FormatterHelper;

?>
<?= $this->extend("layouts/admin") ?>

<?= $this->section("content") ?>
<div class="d-flex justify-content-start">
    <a href="<?= base_url("my_lots_and_estates") ?>" class="btn btn-primary shadow"><i class="bi bi-arrow-left"></i> Back</a>
</div>
<div class="container mt-4">
    <!-- <h2 class="mb-3">Payment Options</h2> -->


    <div class="row">
        <!-- Cash Sale -->
        <div class="col-md-4 mb-3">
            <div class="card shadow p-3 h-100">
                <h4>Cash Sale</h4>
                <p><b>Original Price:</b> <?= FormatterHelper::formatPrice($pricing["total_purchase_price"]) ?></p>
                <p><b>Cash Sale Discount:</b> <?= FormatterHelper::formatRate($pricing["cash_sale_discount"]) ?></p>
                <p><b>Total Payable Amount:</b> <?= FormatterHelper::formatPrice($pricing["cash_sale"]) ?></p>
                <p><b>Payment Deadline:</b> <?= FormatterHelper::sevenDaysFromNow() ?></p>
                <!-- <button class="btn btn-primary w-100 mt-3">Choose this option</button> -->
                <form action="<?= base_url("payment_option_submit") ?>" method="post">
                    <input type="hidden" value="<?= $encryptedAssetId ?>" name="asset_id">
                    <input type="hidden" value="<?= $encryptedAssetType ?>" name="reservation_type">
                    <input type="hidden" value="cash_sale" name="payment_option">
                    <button class="btn btn-primary w-100 mt-3" type="submit">Choose this option</button>
                </form>
                <!-- <a class="btn btn-primary w-100 mt-3" role="button" href="payment_option/cash_sale">Choose this option</a> -->
            </div>
        </div>

        <!-- 6 Months Plan -->
        <div class="col-md-4 mb-3">
            <div class="card shadow p-3 h-100">
                <h4>6 Months Plan</h4>
                <p><b>Original Price:</b> <?= FormatterHelper::formatPrice($pricing["total_purchase_price"]) ?></p>
                <p><b>6 Months Discount:</b> <?= FormatterHelper::formatRate($pricing["six_months_discount"]) ?></p>
                <p><b>Total Payable Amount:</b> <?= FormatterHelper::formatPrice($pricing["six_months"]) ?></p>
                <p><b>Payment Deadline:</b> <?= FormatterHelper::sixMonthsFromNow() ?></p>
                <!-- <button class="btn btn-primary w-100 mt-3">Choose this option</button> -->
                <form action="<?= base_url("payment_option_submit") ?>" method="post">
                    <input type="hidden" value="<?= $encryptedAssetId ?>" name="asset_id">
                    <input type="hidden" value="<?= $encryptedAssetType ?>" name="reservation_type">
                    <input type="hidden" value="six_months" name="payment_option">
                    <button class="btn btn-primary w-100 mt-3" type="submit">Choose this option</button>
                </form>
                <!-- <a class="btn btn-primary w-100 mt-3" role="button" href="payment_option/six_months">Choose this option</a> -->
            </div>
        </div>

        <!-- Installment Plan -->
        <div class="col-md-4">
            <div class="card shadow p-3 h-100">
                <h4>Installment Plan (1-5 Years)</h4>
                <form action="<?= base_url("payment_option_submit") ?>" method="post">
                    <label for="years">Select Installment Duration:</label>
                    <select id="years" class="form-control mb-3">
                        <option value="1">1 Year (0% Interest)</option>
                        <option value="2">2 Years (10% Interest)</option>
                        <option value="3">3 Years (15% Interest)</option>
                        <option value="4">4 Years (20% Interest)</option>
                        <option value="5">5 Years (25% Interest)</option>
                    </select>

                    <p><b>Original Price:</b> <?= FormatterHelper::formatPrice($pricing["total_purchase_price"]) ?></p>
                    <p><b>Down Payment (<?= FormatterHelper::formatRate($pricing["down_payment_rate"]) ?>):</b> <?= FormatterHelper::formatPrice($pricing["down_payment"]) ?></p>
                    <p><b>Balance:</b> <?= FormatterHelper::formatPrice($pricing["balance"]) ?></p>
                    <p><b>Interest Rate:</b> <span id="interestRate">0%</span></p>
                    <p><b>Monthly Payment:</b> <span id="monthlyPayment"></span></p>
                    <p><b>Total Payable Amount:</b> <span id="totalPayableAmount"></span></p>

                    <input type="hidden" value="<?= $encryptedAssetType ?>" name="asset_id">
                    <input type="hidden" value="<?= $encryptedAssetType ?>" name="reservation_type">

                    <button class="btn btn-primary w-100 mt-3" type="submit">Choose this option</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function updateInstallmentDetails() {
        let years = parseInt(document.getElementById('years').value);
        let interestRates = {1: <?= (float) $pricing["one_year_interest_rate"] ?>, 2: <?= (float) $pricing["two_years_interest_rate"] ?>, 3: <?= (float) $pricing["three_years_interest_rate"] ?>, 4: <?= (float) $pricing["four_years_interest_rate"] ?>, 5: <?= (float) $pricing["five_years_interest_rate"] ?>};
        let interestRate = interestRates[years];
        let balance = <?= (int) $pricing["balance"] ?>;
        let totalAmount = balance + (balance * interestRate);
        let monthlyPayment = totalAmount / (years * 12);
        let totalPayableAmount = numberFormat(monthlyPayment * (years * 12));
        let formattedMonthlyPayment = numberFormat(monthlyPayment);

        document.getElementById('interestRate').innerText = (interestRate * 100) + "%";
        document.getElementById('monthlyPayment').innerText = "₱" + formattedMonthlyPayment;
        document.getElementById('totalPayableAmount').innerText = "₱" + totalPayableAmount;
    }

    document.getElementById('years').addEventListener('change', updateInstallmentDetails);
    updateInstallmentDetails(); // Initialize on page load

    function numberFormat(num, decimals = 2) {
        return num.toLocaleString(undefined, { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
    }
</script>

<?= $this->endSection(); ?>
