<?= $this->extend('components/brochure_template') ?>

<?= $this->section('content') ?>
<div id="myCarousel" class="carousel slide mb-6" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <div class="position-relative">
        <img src="<?= base_url("img/carousel_1.jpg") ?>" alt="Peaceful Gardens">
        <div class="position-absolute top-0 left-0 w-100 h-100 bg-dark opacity-50"></div>
      </div>
      <div class="container">
        <div class="carousel-caption text-start text-white">
          <h1>Serenity, Dignity, Legacy</h1>
          <p class="opacity-75">A sacred place to honor your loved ones with grace and peace.</p>
          <p><a class="btn btn-lg btn-primary" href="<?= base_url("contact") ?>">Inquire Now</a></p>
        </div>
      </div>
    </div>
    <div class="carousel-item">
      <div class="position-relative">
        <img src="<?= base_url("img/carousel_2.jpg") ?>" alt="Garden View">
        <div class="position-absolute top-0 left-0 w-100 h-100 bg-dark opacity-50"></div>
      </div>
      <div class="container">
        <div class="carousel-caption text-white">
          <h1>Premium Memorial Lots</h1>
          <p>Choose from lawn lots and private estates (mausoleums) thoughtfully designed for enduring remembrance.</p>
          <p><a class="btn btn-lg btn-primary" href="#">View Lot Options</a></p>
        </div>
      </div>
    </div>
    <div class="carousel-item">
      <div class="position-relative">
        <img src="<?= base_url("img/carousel_3.jpg") ?>" alt="Estate Mausoleums">
        <div class="position-absolute top-0 left-0 w-100 h-100 bg-dark opacity-50"></div>
      </div>
      <div class="container">
        <div class="carousel-caption text-end text-white">
          <h1>Personalized Private Estates</h1>
          <p>Honor your legacy with exclusive mausoleum-style estates tailored to your family's wishes.</p>
          <p><a class="btn btn-lg btn-primary" href="#">Explore Estates</a></p>
        </div>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

<div class="container marketing">
  <div class="row">
    <!-- Lawn Lots Section -->
    <div class="col-lg-4">
      <a target="_blank" href="https://www.flaticon.com/free-icons/grave" title="grave icons">
        <img src="<?= base_url("img/grave.png") ?>" class="bd-placeholder-img rounded" width="140" height="140" alt="Lot">
      </a>
      <h2 class="fw-normal">Lawn Lots</h2>
      <p>Ideal for individual or companion memorials. Carefully landscaped with serene surroundings.</p>
      <p><a class="btn btn-secondary" href="<?= base_url("pricing?type=lot") ?>">View Lot Pricing &raquo;</a></p>
    </div>

    <!-- Estate Mausoleums Section -->
    <div class="col-lg-4">
      <a target="_blank" href="https://www.flaticon.com/free-icons/mausoleum" title="Mausoleum icons">
        <img src="<?= base_url("img/mausoleum.png") ?>" class="bd-placeholder-img rounded" width="140" height="140" alt="Mausoleum">
      </a>
      <h2 class="fw-normal">Estate Mausoleums</h2>
      <p>Available in sizes A, B, and C, our estates offer customizable private mausoleum spaces for families.</p>
      <p><a class="btn btn-secondary" href="<?= base_url("pricing?type=estate") ?>">View Estate Pricing &raquo;</a></p>
    </div>

    <!-- Burial Services Section -->
    <div class="col-lg-4">
      <a target="_blank" href="https://www.flaticon.com/free-icons/grief" title="grief icons">
        <img src="<?= base_url("img/funeral.png") ?>" class="bd-placeholder-img rounded" width="140" height="140" alt="Burial Services">
      </a>
      <h2 class="fw-normal">Burial Services</h2>
      <p>Comprehensive burial services including preparation, ceremony arrangements, and memorials for loved ones.</p>
      <p><a class="btn btn-secondary" href="<?= base_url("pricing?type=burial") ?>">View Burial Pricing &raquo;</a></p>
    </div>
  </div>



  <hr class="featurette-divider">

  <div class="row featurette">
    <div class="col-md-7">
      <h2 class="featurette-heading fw-normal lh-1">Lots & Estates Selection <span class="text-body-secondary">Find the perfect space.</span></h2>
      <p class="lead">Choose from a variety of options, from traditional lawn lots to exclusive family estates. Each lot is thoughtfully developed to provide a peaceful environment where legacy lives on.</p>
    </div>
    <div class="col-md-5">
      <a href="<?= base_url("locator") ?>" title="Click to check lot and estate availability">
        <img
          src="<?= base_url("img/ghmp_aerial_view.png") ?>"
          class="img-fluid rounded shadow"
          alt="Lot Selection">
      </a>
      <div class="text-center mt-2">
        <small class="text-muted">Click on the image to check the availability of lots and estates.</small>
      </div>
    </div>
  </div>


  <hr class="featurette-divider">

  <div class="row featurette">
    <div class="col-md-7 order-md-2">
      <h2 class="featurette-heading fw-normal lh-1">Transparent Pricing <span class="text-body-secondary">Tailored for every family.</span></h2>
      <p class="lead">Our prices are designed to be accessible, with options for every budget. Estate mausoleums start at <?= $lowestEstatePrice ?>. Lawn lots begin at <?= $lowestLotPrice ?>. Full details available on request.</p>
    </div>
    <div class="col-md-5 order-md-1">
      <a target="_blank" href="https://www.flaticon.com/free-icons/money" title="money icons">
        <img src="<?= base_url("img/pricing.png") ?>" class="img-fluid" alt="Pricing Info">
      </a>
    </div>
  </div>

  <hr class="featurette-divider">

  <div class="row featurette">
    <div class="col-md-7">
      <h2 class="featurette-heading fw-normal lh-1">Seamless Transactions <span class="text-body-secondary">Simple and secure.</span></h2>
      <p class="lead">Inquire, reserve, and settle payments through our guided process. Our staff will walk you through reservation, document processing, and payment submission, whether online or in person.</p>
    </div>
    <div class="col-md-5">
      <a target="_blank" href="https://www.flaticon.com/free-icons/transaction" title="transaction icons">
        <img src="<?= base_url("img/transaction.png") ?>" class="img-fluid" alt="Transaction Process">
      </a>
    </div>
  </div>

  <hr class="featurette-divider">
</div>
</div>

<?= $this->endSection() ?>