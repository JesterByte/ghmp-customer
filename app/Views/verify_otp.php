<?= $this->extend("components/brochure_template") ?>

<?= $this->section("content") ?>
<div class="container-sm mt-5">
    <div class="row d-flex justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <h2 class="h4 mb-3">Verify OTP</h2>
                        <p class="text-muted">Enter the 6-digit code sent to your email</p>
                    </div>

                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger"><?= session('error') ?></div>
                    <?php endif; ?>

                    <form action="<?= base_url('verify-otp/validate') ?>" method="post" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="otp-input-group mb-4">
                            <div class="d-flex justify-content-center gap-2">
                                <?php for ($i = 1; $i <= 6; $i++): ?>
                                    <input type="text" 
                                           class="form-control text-center otp-input"
                                           required
                                           maxlength="1"
                                           style="width: 45px; height: 45px; font-size: 1.2rem;"
                                           data-index="<?= $i ?>"
                                           inputmode="numeric">
                                <?php endfor; ?>
                            </div>
                            <input type="hidden" name="otp" id="otpValue">
                            <div class="invalid-feedback text-center">
                                Please enter the complete 6-digit code
                            </div>
                        </div>

                        <button class="btn btn-primary w-100 py-2 mb-3 rounded-3" type="submit">
                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                            Verify OTP
                        </button>

                        <div class="text-center mt-3">
                            <p class="mb-0">Didn't receive the code?</p>
                            <form action="<?= base_url('forgot-password/resend-otp') ?>" method="post" class="d-inline">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-link p-0">Resend OTP</button>
                            </form>
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
    const otpInputs = document.querySelectorAll('.otp-input');
    const otpValue = document.getElementById('otpValue');
    const submitBtn = form.querySelector('button[type="submit"]');

    // Handle OTP input
    otpInputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            // Allow only numbers
            e.target.value = e.target.value.replace(/[^0-9]/g, '');

            // Auto focus next input
            if (e.target.value && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }

            // Update hidden input with complete OTP
            updateOTPValue();
        });

        // Handle backspace
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                otpInputs[index - 1].focus();
            }
        });
    });

    function updateOTPValue() {
        const otp = Array.from(otpInputs).map(input => input.value).join('');
        otpValue.value = otp;
    }

    // Form submission
    form.addEventListener('submit', function(event) {
        const otp = otpValue.value;
        if (otp.length !== 6) {
            event.preventDefault();
            event.stopPropagation();
            form.classList.add('was-validated');
        } else {
            submitBtn.disabled = true;
            submitBtn.querySelector('.spinner-border').classList.remove('d-none');
        }
    });
});
</script>

<style>
.otp-input:focus {
    box-shadow: none;
    border-color: #0d6efd;
}
.otp-input {
    border-radius: 8px;
}
</style>
<?= $this->endSection() ?>