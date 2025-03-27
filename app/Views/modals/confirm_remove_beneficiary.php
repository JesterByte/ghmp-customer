<!-- Confirmation Modal -->
<div class="modal fade" id="removeBeneficiary" tabindex="-1" aria-labelledby="removeBeneficiaryLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeBeneficiaryLabel">Remove Beneficiary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="removeBeneficiaryBody">
                Are you sure you want to remove this beneficiary?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="<?= base_url("settings/remove_beneficiary") ?>" method="post">
                    <input type="hidden" name="beneficiary_id" id="beneficiaryId" >
                    <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Yes, Remove</button>
                </form>
            </div>
        </div>
    </div>
</div>