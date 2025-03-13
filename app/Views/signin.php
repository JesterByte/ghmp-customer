<?= $this->extend("components/brochure_template"); ?>

<?= $this->section("content") ?>
    <div class="container-sm mt-5">
        <h2 class="mb-4"><?= $pageTitle ?></h2>
        <form class="needs-validation" novalidate  action="<?= base_url("signin/submit") ?>" method="post">
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
            <button type="submit" class="btn btn-primary">Sign In</button>
        </form>

        <p class="mt-3">Don't have an account? <a href="signup">Sign Up</a></p>
    </div>
<?= $this->endSection() ?>