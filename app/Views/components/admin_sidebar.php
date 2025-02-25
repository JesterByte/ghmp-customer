<div class="sidebar border border-right col-md-3 col-lg-2 p-0 bg-body-tertiary">
      <div class="offcanvas-md offcanvas-end bg-body-tertiary" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title" id="sidebarMenuLabel">Green Haven Memorial Park</h5>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center gap-2 <?= echo_if_route($pageTitle, "Dashboard", "active text-bg-primary") ?>" <?= echo_if_route($pageTitle, "Dashboard", 'aria-current="page"') ?> href="<?= base_url("dashboard") ?>">
                <i class="bi bi-speedometer2"></i>
                Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center gap-2 <?= echo_if_route($pageTitle, "My Lots & Estates", "active text-bg-primary") ?>" <?= echo_if_route($pageTitle, "My Lots & Estates", 'aria-current="page"') ?> href="<?= base_url("my_lots_and_estates") ?>">
                <i class="bi bi-bag<?= echo_if_route($pageTitle, "My Lots & Estates", "-fill") ?>"></i>                
                My Lots & Estates
              </a>
            </li>
            <?php $reserveList = ["Reserve a Lot", "Reserve an Estate"]; ?>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2" data-bs-toggle="collapse" href="#reserveSubmenu" role="button" aria-expanded="<?= page_in_List($pageTitle, $reserveList, "true", "false") ?>" aria-controls="reserveSubmenu">
                    <i class="bi bi-calendar<?= page_in_List($pageTitle, $reserveList, "-fill") ?>"></i> Reserve <i class="bi bi-caret-down<?= page_in_List($pageTitle, $reserveList, "-fill") ?>"></i>
                </a>
                <div class="collapse <?= page_in_List($pageTitle, $reserveList, "show") ?>" id="reserveSubmenu">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li><a href="<?= base_url("reserve_lot") ?>" class="nav-link d-flex align-items-center gap-2 <?= echo_if_route($pageTitle, "Reserve a Lot", "active text-bg-primary") ?>" ><i class="bi bi-caret-right<?= echo_if_route($pageTitle, "Reserve a Lot", "-fill") ?>"></i> Reserve a Lot</a></li>
                        <li><a href="<?= base_url("reserve_estate") ?>" class="nav-link d-flex align-items-center gap-2 <?= echo_if_route($pageTitle, "Reserve an Estate", "active text-bg-primary") ?>"><i class="bi bi-caret-right<?= echo_if_route($pageTitle, "Reserve an Estate", "-fill") ?>"></i> Reserve an Estate</a></li>
                    </ul>
                </div>
            </li>
            <?php $memorialServicesList = ["Schedule a Memorial Service", "My Memorial Services"]; ?>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2" data-bs-toggle="collapse" href="#memorialServicesSubmenu" role="button" aria-expanded="<?= page_in_List($pageTitle, $memorialServicesList, "true", "false") ?>" aria-controls="memorialServicesSubmenu">
                    <i class="bi bi-umbrella<?= page_in_List($pageTitle, $memorialServicesList, "-fill") ?>"></i> Memorial Services (Burial) <i class="bi bi-caret-down<?= page_in_List($pageTitle, $memorialServicesList, "-fill") ?>"></i>
                </a>
                <div class="collapse <?= page_in_List($pageTitle, $memorialServicesList, "show") ?>" id="memorialServicesSubmenu">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li><a href="<?= base_url("schedule_memorial_service") ?>" class="nav-link d-flex align-items-center gap-2 <?= echo_if_route($pageTitle, "Schedule a Memorial Service", "active text-bg-primary") ?>"><i class="bi bi-caret-right<?= echo_if_route($pageTitle, "Schedule a Memorial Service", "-fill") ?>"></i> Schedule a Memorial Service </a></li>
                        <li><a href="#" class="nav-link d-flex align-items-center gap-2"><i class="bi bi-caret-right"></i> My Memorial Services </a></li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center gap-2 <?= echo_if_route($pageTitle, "Payment Management", "active text-bg-primary") ?>" <?= echo_if_route($pageTitle, "Payment Management", 'aria-current="page"') ?> href="<?= base_url("payment_management") ?>">
                <i class="bi bi-credit-card<?= echo_if_route($pageTitle, "Payment Management", "-fill") ?>"></i>                
                Payment Management
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center gap-2" aria-current="page" href="#">
                <i class="bi bi-file-earmark"></i>                
                Documents & Agreements
              </a>
            </li>
          </ul>

          <hr class="my-3">

          <ul class="nav flex-column mb-auto">
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center gap-2" href="#">
                <i class="bi bi-gear"></i>
                Settings
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center gap-2" href="<?= base_url("signout") ?>">
                <i class="bi bi-door-closed"></i>                
                Sign out
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>