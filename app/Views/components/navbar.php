<header data-bs-theme="auto">
  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <img src="<?= base_url("img/ghmp_logo.png") ?>" height="50" width="100">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav me-auto mb-2 mb-md-0">
          <li class="nav-item">
            <a class="nav-link <?= echo_if_route($pageTitle, "Home", "active") ?>" <?= echo_if_route($pageTitle, "Home", 'aria-current="page"') ?> href="<?= base_url("home") ?>">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= echo_if_route($pageTitle, "Lots & Estates", "active") ?>" <?= echo_if_route($pageTitle, "Lots & Estates", 'aria-current="page"') ?> href="<?= base_url("locator") ?>">Lots & Estates</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= echo_if_route($pageTitle, "Pricing", "active") ?>" <?= echo_if_route($pageTitle, "Pricing", 'aria-current="page"') ?> href="<?= base_url("pricing") ?>">Pricing</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= echo_if_route($pageTitle, "About", "active") ?>" <?= echo_if_route($pageTitle, "About", 'aria-current="page"') ?> href="<?= base_url("about") ?>">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= echo_if_route($pageTitle, "Contact", "active") ?>" <?= echo_if_route($pageTitle, "Contact", 'aria-current="page"') ?> href="<?= base_url("contact") ?>">Contact</a>
          </li>
          <!-- <li class="nav-item">
              <a class="nav-link disabled" aria-disabled="true">Disabled</a>
            </li> -->
        </ul>
        <!-- Replacing the search bar with Sign Up and Sign In buttons -->
        <div class="d-flex">
          <a href="<?= base_url("signup") ?>" class="btn btn-outline-light me-2 <?= echo_if_route($pageTitle, "Sign Up", "active") ?>" <?= echo_if_route($pageTitle, "Sign Up", 'aria-current="page"') ?>>Sign Up</a>
          <a href="<?= base_url("signin") ?>" class="btn btn-outline-light <?= echo_if_route($pageTitle, "Sign In", "active") ?>" <?= echo_if_route($pageTitle, "Sign In", 'aria-current="page"') ?>>Sign In</a>
        </div>
      </div>
    </div>
  </nav>
</header>