<!-- Cancel Reservation Modal -->
<div class="modal fade" id="cancelReservation" tabindex="-1" aria-labelledby="cancelReservationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-bg-primary">
                <h5 class="modal-title" id="cancelReservationModalLabel">Cancel Reservation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Are you sure you want to cancel this reservation?</p>
                <form action="<?= base_url("cancel_reservation") ?>" id="cancelReservationForm" novalidate method="post" class="needs-validation">
                    <input type="hidden" name="asset_id" id="assetId">
                    <div class="form-floating mb-3">
                        <input type="text" name="reason" id="reason" class="form-control" placeholder="Reason" required>
                        <label for="reason">Reason</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="cancelReservationForm" class="btn btn-primary">Yes</button>
            </div>
        </div>
    </div>
</div>