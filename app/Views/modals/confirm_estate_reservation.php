<!-- Reserve Confirmation Modal -->
<div class="modal fade" id="reserveModal" tabindex="-1" aria-labelledby="reserveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reserveModalLabel">Confirm Reservation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to reserve this estate?
                <input type="hidden" id="reserveEstateId">
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
        var estateId = $('#reserveEstateId').val(); // Get the lot ID from the hidden input

        // Make an AJAX request to reserve the lot
        fetch("<?= base_url('reserve/submitReservationEstate') ?>", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ estate_id: estateId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Estate reserved successfully!');
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
