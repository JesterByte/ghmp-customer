<?= $this->extend("components/brochure_template"); ?>

<?= $this->section("content") ?>
<div class="container-sm mt-5">
    <h2 class="text-center"><?= $pageTitle ?></h2>
    <p class="mb-4 text-center">OTP Verification</p>
    <div class="row d-flex justify-content-center">
        <div class="col-lg-12 w-50">
            <form class="needs-validation" novalidate action="<?= base_url("ownership_transfer/verify_otp") ?>" method="post">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

                <input type="hidden" name="email" value="<?= $email ?>">

                <!-- OTP -->
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="otp" name="otp" placeholder="OTP" required>
                    <label for="otp">OTP</label>
                </div>

                <!-- Submit Button -->
                <div class="d-grid gap-2">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>

</div>
<?= $this->endSection() ?>
