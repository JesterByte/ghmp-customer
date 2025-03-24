<?= $this->extend("components/brochure_template"); ?>

<?= $this->section("content") ?>
<div class="container-sm mt-5">
    <h2 class="mb-4 text-center"><?= $pageTitle ?></h2>
    <div class="row d-flex justify-content-center">
        <div class="col-lg-12 w-50">

            <!-- Information Message -->
            <div class="alert alert-info text-center mb-4">
                Note: This request will only proceed if the original account owner has been inactive for a significant period.
            </div>

            <form class="needs-validation" novalidate action="<?= base_url("ownership_transfer/submit") ?>" method="post">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

                <!-- Email -->
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required>
                    <label for="email">Email Address</label>
                </div>

                <!-- Submit Button -->
                <div class="d-grid gap-2">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <p class="mt-3 text-center">
        <a href="<?= base_url("signin") ?>">Sign in instead?</a><br>
    </p>
</div>
<?= $this->endSection() ?>
