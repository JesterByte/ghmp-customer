<!-- Reserve Confirmation Modal -->
<div class="modal fade" id="confirmSignout" tabindex="-1" aria-labelledby="confirmSignoutLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmSignoutLabel">Signout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to sign out?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="<?= base_url("signout") ?>" role="button" class="btn btn-primary">Yes</a>
            </div>
        </div>
    </div>
</div>