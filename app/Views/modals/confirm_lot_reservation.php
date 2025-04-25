<!-- Reserve Confirmation Modal -->
<div class="modal fade" id="reserveLotModal" tabindex="-1" aria-labelledby="reserveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reserveModalLabel">Confirm Reservation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to reserve this lot?
                <input type="hidden" id="reserveLotId">
                <input type="hidden" id="reserveLotType">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitLotReservation()">Yes, Reserve</button>
            </div>
        </div>
    </div>
</div>

<script>

</script>