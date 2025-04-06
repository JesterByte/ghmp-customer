<?= $this->extend('components/brochure_template'); ?>

<?= $this->section('content'); ?>

<div class="container py-5">
    <h1 class="display-4 text-center">About Us</h1>
    <p class="lead text-center">
        At Green Haven Memorial Park, we believe in honoring life with dignity, serenity, and lasting legacy. 
        Our mission is to provide families with a peaceful resting place and compassionate service, every step of the way.
    </p>

    <div class="row">
        <div class="col-md-6">
            <h2>Our Story</h2>
            <p>
                Founded with the vision of creating a serene, enduring memorial sanctuary, Green Haven began as a family-led initiative 
                to redefine how we celebrate life and remembrance. What started as a modest park has now grown into a thoughtfully planned estate 
                featuring various lot types, estate mausoleums, and tailored burial services—all supported by a dedicated team that truly cares.
            </p>
        </div>
        <div class="col-md-6">
            <h2>Our Vision</h2>
            <p>
                We envision a future where memorial parks offer more than just a final resting place—they offer peace, legacy, and comfort 
                to the generations left behind. Green Haven is committed to leading this vision by maintaining beautifully landscaped phases, 
                modern facilities, and compassionate service for every family we serve.
            </p>
        </div>
    </div>

    <div class="text-center mt-5">
        <img src="<?= base_url("img/ghmp_logo.png") ?>" 
             alt="Green Haven Memorial Park Logo" 
             class="img-fluid w-100 w-md-75 w-lg-50 mx-auto d-block"
             style="max-width: 600px;">
    </div>
</div>

<?= $this->endSection(); ?>
