<?= $this->extend('components/brochure_template'); ?>

<?= $this->section('content'); ?>

<div class="container py-5">
    <h1 class="display-4 text-center">Contact Us</h1>
    <p class="lead text-center">
        Feel free to reach out to us with any questions or concerns.
    </p>

    <div class="row">
        <div class="col-md-6">
            <h3>Contact Form</h3>
            <!-- Contact Form -->
            <form action="<?= site_url('contact/submit') ?>" method="post">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Your Message</label>
                    <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send Message</button>
            </form>
        </div>

        <div class="col-md-6">
            <h3>Our Office</h3>
            <p>Visit us at our office or contact us via phone:</p>
            <ul>
                <li><strong>Address:</strong> 123 Memorial Park Road, Green Haven</li>
                <li><strong>Phone:</strong> +1 (123) 456-7890</li>
                <li><strong>Email:</strong> info@greenhaven.com</li>
            </ul>
        </div>
    </div>

    <div class="text-center mt-4">
        <h3>Follow Us</h3>
        <p>Stay connected via our social media channels:</p>
        <p>
            <a href="#">Facebook</a> | <a href="#">Twitter</a> | <a href="#">Instagram</a>
        </p>
    </div>
</div>

<?= $this->endSection(); ?>
