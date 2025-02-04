<!-- Reserve Confirmation Modal -->
<div class="modal fade" id="reserveModal" tabindex="-1" aria-labelledby="reserveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reserveModalLabel">Confirm Reservation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to reserve this lot?
                <input type="hidden" id="reserveLotId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitReservation()">Yes, Reserve</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Submit reservation via AJAX
    function submitReservation() {
        var lotId = $('#reserveLotId').val(); // Get the lot ID from the hidden input

        // Make an AJAX request to reserve the lot
        fetch("<?= base_url('reserve/submitReservation') ?>", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ lot_id: lotId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Lot reserved successfully!');
                $('#reserveModal').modal('hide');
                // Optionally, you can reload the map or update the UI
                window.location.reload();  // Reload the page to update the available lots
            } else {
                alert('Reservation failed. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error during reservation:', error);
            alert('An error occurred. Please try again.');
        });
    }
</script>
