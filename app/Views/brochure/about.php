<?= $this->extend('components/brochure_template'); ?>

<?= $this->section('content'); ?>

<div class="container py-5">
    <h1 class="display-4 text-center">About Us</h1>
    <p class="lead text-center">
        Welcome to our company. We are dedicated to providing the best service in the industry. 
        Our mission is to bring value to our clients by offering innovative solutions.
    </p>

    <div class="row">
        <div class="col-md-6">
            <h2>Our Story</h2>
            <p>
                Our journey began over a decade ago with a small team of passionate individuals dedicated to making a difference. 
                Today, we are proud of the progress we've made and the impact we've had on our clients' success.
            </p>
        </div>
        <div class="col-md-6">
            <h2>Our Vision</h2>
            <p>
                We strive to become a global leader in our industry, providing exceptional services that meet the needs of a diverse clientele.
            </p>
        </div>
    </div>

    <div class="text-center mt-4">
        <h3>Meet Our Team</h3>
        <p>Our team consists of experts with years of experience in the field. Together, we bring a unique set of skills to the table to serve you better.</p>
    </div>
</div>

<?= $this->endSection(); ?>
