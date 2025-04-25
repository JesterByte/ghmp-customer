<div class="modal fade" id="restructureRequestModal" tabindex="-1" aria-labelledby="restructureRequestModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="restructureRequestModalLabel">Confirm Request</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="<?= base_url("my_lots_and_estates/restructure_request") ?>" class="needs-validation" novalidate>
        <div class="modal-body">
          <p>You are about to request to restructure this installment plan into a one-time payment.</p>
          <p class="text-muted">This is only a request. It must be reviewed and approved by an admin.</p>

          <div class="mb-3">
            <label for="restructureReason" class="form-label">Reason for Restructure</label>
            <textarea class="form-control" id="restructureReason" name="restructure_reason" rows="4" placeholder="Explain why you’re requesting this change..." required></textarea>
          </div>

          <input type="hidden" name="reservation_id" id="reservation_id" value="<!-- dynamically fill -->">
          <input type="hidden" name="asset_id" id="asset_id" value="<!-- dynamically fill -->">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Submit Request</button>
        </div>
      </form>
    </div>
  </div>
</div>
