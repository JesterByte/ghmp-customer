<?= $this->extend("components/brochure_template") ?>

<?= $this->section("content") ?>
    <div class="container mt-5">
        <h2 class="mb-4"><?= $pageTitle ?></h2>
        <form action="<?= base_url('signup/submit') ?>" method="post">
            <!-- Full Name -->
            <div class="mb-3">
                <label for="fullName" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="fullName" name="fullName" required>
            </div>

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

            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Sign Up</button>
        </form>

        <p class="mt-3">Already have an account? <a href="signin">Sign In</a></p>
    </div>
<?= $this->endSection() ?>
