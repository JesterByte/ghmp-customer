<?= $this->extend("components/brochure_template"); ?>

<?= $this->section("content") ?>
<div class="container-sm mt-5">
    <h2 class="mb-4 text-center"><?= $pageTitle ?></h2>
    <div class="row d-flex justify-content-center">
        <div class="col-lg-12 w-50">
            <form class="needs-validation" novalidate action="<?= base_url("signin/submit") ?>" method="post">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

                <!-- Email -->
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required>
                    <label for="email">Email Address</label>
                </div>

                <!-- Password -->
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password">Password</label>
                </div>

                <!-- Submit Button -->
                <div class="d-grid gap-2">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <p class="mt-3 text-center">
        Don't have an account? <a href="signup">Sign Up</a><br>
        <a href="<?= base_url('ownership_transfer/request') ?>" class="btn btn-link mt-2">Account Ownership Transfer Request</a>
    </p>
</div>
<?= $this->endSection() ?>
