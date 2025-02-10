<!-- Pay Cash Sale Modal -->
<div class="modal fade" id="payCashSale" tabindex="-1" aria-labelledby="payCashSaleLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="payCashSaleLabel">Pay Cash Sale</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="payCashSaleForm">
                    <!-- Select Asset -->
                    <div class="mb-3">
                        <label for="assetSelectCashSale" class="form-label">Select Asset</label>
                        <select id="assetSelectCashSale" class="form-select" required>
                            <option value="" disabled selected>Select an asset</option>
                            <!-- Assets will be dynamically added here -->
                        </select>
                    </div>

                    <!-- Display Amount Payable -->
                    <div class="mb-3">
                        <label for="amountPayableCashSale" class="form-label">Amount Payable</label>
                        <input type="text" id="amountPayableCashSale" class="form-control" readonly>
                    </div>

                    <!-- Upload Receipt -->
                    <div class="mb-3">
                        <label for="paymentReceiptCashSale" class="form-label">Upload Payment Receipt</label>
                        <input type="file" id="paymentReceiptCashSale" class="form-control" accept=".pdf, .jpg, .png" required>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Confirm Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let assetSelectCashSale = document.getElementById("assetSelectCashSale");
    let amountPayableCashSale = document.getElementById("amountPayableCashSale");

    fetch("<?= base_url("api/cash_sales") ?>")
        .then(response => response.json())
        .then(data => {
            assetSelectCashSale.innerHTML = '<option value="" disabled selected>Select an asset</option>';

            data.forEach(asset => {
                let option = document.createElement("option");
                option.value = asset.asset_id;
                option.textContent = `${asset.asset_type.toUpperCase()} - ${asset.asset_id} (₱${parseFloat(asset.payment_amount).toLocaleString()})`;
                option.dataset.amount = asset.payment_amount;
                assetSelectCashSale.appendChild(option);
            });
        })
        .catch(error => console.error("Error fetching assets:", error));

    // Update amount payable when selecting an asset
    assetSelectCashSale.addEventListener("change", function () {
        let selectedOption = assetSelectCashSale.options[assetSelectCashSale.selectedIndex];
        amountPayableCashSale.value = `₱${parseFloat(selectedOption.dataset.amount).toLocaleString()}`;
    });

    // Handle form submission (replace with actual AJAX request)
    document.getElementById("payCashSaleForm").addEventListener("submit", function (e) {
        e.preventDefault();

        let formData = new FormData();
        formData.append("asset_id", assetSelectCashSale.value);
        formData.append("payment_amount", amountPayableCashSale.value.replace("₱", "").replace(",", ""));
        formData.append("receipt", paymentReceiptCashSale.files[0]);

        fetch("<?= base_url("api/pay_cash_sale") ?>", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Payment submitted successfully!");
                location.reload();
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(error => console.error("Error submitting payment:", error));

    });
});
</script>
