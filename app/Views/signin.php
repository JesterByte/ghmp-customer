<?= $this->extend("components/brochure_template"); ?>

<?= $this->section("content") ?>
    <div class="container mt-5">
        <h2 class="mb-4">Sign In</h2>
        <form action="<?= base_url("signin/submit") ?>" method="post">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Sign In</button>
        </form>

        <p class="mt-3">Don't have an account? <a href="signup">Sign Up</a></p>
    </div>
<?= $this->endSection() ?>