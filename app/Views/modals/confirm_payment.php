<!-- Payment Confirmation Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Confirm Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Asset ID:</strong> <span id="modalAssetId"></span></p>
                <p><strong>Asset Type:</strong> <span id="modalAssetType"></span></p>
                <p><strong>Payment Option:</strong> <span id="modalPaymentOption"></span></p>
                <p><strong>Reservation Status:</strong> <span id="modalReservationStatus"></span></p>
                
                <form id="confirmPaymentForm" action="" method="POST">
                    <input type="hidden" name="asset_id" id="inputAssetId">
                    <input type="hidden" name="asset_type" id="inputAssetType">
                    <input type="hidden" name="payment_option" id="inputPaymentOption">
                    
                    <div class="mb-3">
                        <label for="receiptUpload" class="form-label">Upload Payment Receipt</label>
                        <input type="file" class="form-control" name="receipt" id="receiptUpload" required>
                    </div>
                    
                    <button type="submit" class="btn btn-success">Confirm Payment</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var paymentModal = document.getElementById('paymentModal');
        paymentModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget; // Button that triggered the modal
            var assetId = button.getAttribute('data-asset-id');
            var assetType = button.getAttribute('data-asset-type');
            var paymentOption = button.getAttribute('data-payment-option');
            var reservationStatus = button.getAttribute('data-reservation-status');

            // Update modal content
            document.getElementById('modalAssetId').textContent = assetId;
            document.getElementById('modalAssetType').textContent = assetType;
            document.getElementById('modalPaymentOption').textContent = paymentOption;
            document.getElementById('modalReservationStatus').textContent = reservationStatus;

            // Update form hidden fields
            document.getElementById('inputAssetId').value = assetId;
            document.getElementById('inputAssetType').value = assetType;
            document.getElementById('inputPaymentOption').value = paymentOption;
        });
    });
</script>
