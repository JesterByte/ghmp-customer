<!-- Pay 6 Months Modal -->
<div class="modal fade" id="paySixMonths" tabindex="-1" aria-labelledby="paySixMonthsLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paySixMonthsLabel">Pay 6 Months</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="paySixMonthsForm">
                    <!-- Select Asset -->
                    <div class="mb-3">
                        <label for="assetSelectSixMonths" class="form-label">Select Asset</label>
                        <select id="assetSelectSixMonths" class="form-select" required>
                            <option value="" disabled selected>Select an asset</option>
                            <!-- Assets will be dynamically added here -->
                        </select>
                    </div>

                    <!-- Display Amount Payable -->
                    <div class="mb-3">
                        <label for="amountPayableSixMonths" class="form-label">Amount Payable</label>
                        <input type="text" id="amountPayableSixMonths" class="form-control" readonly>
                    </div>

                    <!-- Upload Receipt -->
                    <div class="mb-3">
                        <label for="paymentReceiptSixMonths" class="form-label">Upload Payment Receipt</label>
                        <input type="file" id="paymentReceiptSixMonths" class="form-control" accept=".pdf, .jpg, .png" required>
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
    let assetSelectSixMonths = document.getElementById("assetSelectSixMonths");
    let amountPayableSixMonths = document.getElementById("amountPayableSixMonths");

    fetch("<?= base_url("api/six_months") ?>")
        .then(response => response.json())
        .then(data => {
            assetSelectSixMonths.innerHTML = '<option value="" disabled selected>Select an asset</option>';

            data.forEach(asset => {
                let option = document.createElement("option");
                option.value = asset.asset_id;
                option.textContent = `${asset.asset_type.toUpperCase()} - ${asset.asset_id} (₱${parseFloat(asset.payment_amount).toLocaleString()})`;
                option.dataset.amount = asset.payment_amount;
                assetSelectSixMonths.appendChild(option);
            });
        })
        .catch(error => console.error("Error fetching assets:", error));

    // Update amount payable when selecting an asset
    assetSelectSixMonths.addEventListener("change", function () {
        let selectedOption = assetSelectSixMonths.options[assetSelectSixMonths.selectedIndex];
        amountPayableSixMonths.value = `₱${parseFloat(selectedOption.dataset.amount).toLocaleString()}`;
    });

    // Handle form submission (replace with actual AJAX request)
    document.getElementById("paySixMonthsForm").addEventListener("submit", function (e) {
        e.preventDefault();

        let formData = new FormData();
        formData.append("asset_id", assetSelectSixMonths.value);
        formData.append("payment_amount", amountPayableSixMonths.value.replace("₱", "").replace(",", ""));
        formData.append("receipt", paymentReceiptSixMonths.files[0]);

        fetch("<?= base_url("api/pay_six_months") ?>", {
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
