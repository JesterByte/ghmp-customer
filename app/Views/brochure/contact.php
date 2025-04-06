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
            <form class="needs-validation" novalidate action="<?= base_url('contact/submit') ?>" method="post">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="name" name="name" placeholder="John Doe" required>
                    <label for="name">Full Name</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                    <label for="email">Email Address</label>
                </div>
                <div class="form-floating mb-3">
                    <textarea class="form-control" id="message" name="message" placeholder="Your message here" style="height: 150px" required></textarea>
                    <label for="message">Your Message</label>
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

            <div class="ratio ratio-4x3">
                <iframe class="rounded shadow" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d810.6625630933522!2d120.97707441172314!3d14.871056034673574!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397abf61bed6905%3A0x803cc12c18f09187!2sGreen%20Haven%20Memorial%20Park!5e0!3m2!1sen!2sph!4v1743931321412!5m2!1sen!2sph" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>

    <div class="text-center mt-4">
        <h3>Follow Us</h3>
        <p>Stay connected via our social media channel:</p>
        <p>
            <a target="_blank" href="https://www.facebook.com/greenhaven.memorialpark2017"><i class="bi bi-facebook"></i></a>
        </p>
    </div>
</div>

<?= $this->endSection(); ?>