<!-- Pay Installment Modal -->
<div class="modal fade" id="payDownPayment" tabindex="-1" aria-labelledby="payDownPaymentLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="payDownPaymentLabel">Pay Down Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="payDownPaymentForm">
                    <!-- Select Asset -->
                    <div class="mb-3">
                        <label for="assetSelectInstallment" class="form-label">Select Asset</label>
                        <select id="assetSelectInstallment" class="form-select" required>
                            <option value="" disabled selected>Select an asset</option>
                            <!-- Assets will be dynamically added here -->
                        </select>
                    </div>

                    <!-- Display Amount Payable -->
                    <div class="mb-3">
                        <label for="amountPayableInstallment" class="form-label">Amount Payable</label>
                        <input type="text" id="amountPayableInstallment" class="form-control" readonly>
                    </div>

                    <!-- Upload Receipt -->
                    <div class="mb-3">
                        <label for="paymentReceiptInstallment" class="form-label">Upload Payment Receipt</label>
                        <input type="file" id="paymentReceiptInstallment" class="form-control" accept=".pdf, .jpg, .png" required>
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
    let assetSelectInstallment = document.getElementById("assetSelectInstallment");
    let amountPayableInstallment = document.getElementById("amountPayableInstallment");

    fetch("<?= base_url("api/installments/down_payments") ?>")
        .then(response => response.json())
        .then(data => {
            assetSelectInstallment.innerHTML = '<option value="" disabled selected>Select an asset</option>';

            data.forEach(asset => {
                let option = document.createElement("option");
                option.value = asset.asset_id;
                option.textContent = `${asset.asset_type.toUpperCase()} - ${asset.asset_id} (₱${parseFloat(asset.payment_amount).toLocaleString()})`;
                option.dataset.amount = asset.payment_amount;
                assetSelectInstallment.appendChild(option);
            });
        })
        .catch(error => console.error("Error fetching assets:", error));

    // Update amount payable when selecting an asset
    assetSelectInstallment.addEventListener("change", function () {
        let selectedOption = assetSelectInstallment.options[assetSelectInstallment.selectedIndex];
        amountPayableInstallment.value = `₱${parseFloat(selectedOption.dataset.amount).toLocaleString()}`;
    });

    // Handle form submission (replace with actual AJAX request)
    // document.getElementById("payDownPaymentForm").addEventListener("submit", function (e) {
    //     e.preventDefault();

    //     let formData = new FormData();
    //     formData.append("asset_id", assetSelectInstallment.value);
    //     formData.append("payment_amount", amountPayableInstallment.value.replace("₱", "").replace(",", ""));
    //     formData.append("receipt", paymentReceiptInstallment.files[0]);

    //     fetch("<?= base_url("api/pay_six_months") ?>", {
    //         method: "POST",
    //         body: formData
    //     })
    //     .then(response => response.json())
    //     .then(data => {
    //         if (data.success) {
    //             alert("Payment submitted successfully!");
    //             location.reload();
    //         } else {
    //             alert("Error: " + data.message);
    //         }
    //     })
    //     .catch(error => console.error("Error submitting payment:", error));

    // });
});
</script>
