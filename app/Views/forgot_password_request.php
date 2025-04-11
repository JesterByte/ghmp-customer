<?= $this->extend("components/brochure_template") ?>

<?= $this->section("content") ?>
<div class="container-sm mt-5">
    <div class="row d-flex justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <img src="<?= base_url('img/ghmp_logo.png') ?>" alt="Company Logo" class="mb-3" style="height: 60px;">
                        <h2 class="h4 mb-3">Forgot Password</h2>
                        <p class="text-muted">Enter your email to receive an OTP code</p>
                    </div>

                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger"><?= session('error') ?></div>
                    <?php endif; ?>

                    <?php if (session()->has('success')): ?>
                        <div class="alert alert-success"><?= session('success') ?></div>
                    <?php endif; ?>

                    <form action="<?= base_url('forgot-password/send-otp') ?>" method="post" class="needs-validation" novalidate>
                        <?= csrf_field() ?>
                        
                        <div class="form-floating mb-4">
                            <input type="email" 
                                   class="form-control rounded-3" 
                                   id="email" 
                                   name="email" 
                                   placeholder="Email Address"
                                   required>
                            <label for="email">Email Address</label>
                            <div class="invalid-feedback">
                                Please provide a valid email address.
                            </div>
                        </div>

                        <button class="btn btn-primary w-100 py-2 mb-3 rounded-3" type="submit">
                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                            Send OTP Code
                        </button>

                        <div class="text-center">
                            <a href="<?= base_url('signin') ?>" class="text-decoration-none">
                                <i class="bi bi-arrow-left"></i> Back to Sign In
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            submitBtn.disabled = true;
            submitBtn.querySelector('.spinner-border').classList.remove('d-none');
        }
        
        form.classList.add('was-validated');
    });
});
</script>
<?= $this->endSection() ?>