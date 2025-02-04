<div class="sidebar border border-right col-md-3 col-lg-2 p-0 bg-body-tertiary">
      <div class="offcanvas-md offcanvas-end bg-body-tertiary" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title" id="sidebarMenuLabel">Green Haven Memorial Park</h5>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center gap-2 active" aria-current="page" href="dashboard">
                <i class="bi bi-speedometer2"></i>
                Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center gap-2" aria-current="page" href="my_lots_and_estates">
                <i class="bi bi-bag"></i>                
                My Lots & Estates
              </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2" data-bs-toggle="collapse" href="#reserveSubmenu" role="button" aria-expanded="false" aria-controls="reserveSubmenu">
                    <i class="bi bi-calendar"></i> Reserve <i class="bi bi-caret-down"></i>
                </a>
                <div class="collapse" id="reserveSubmenu">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li><a href="reserve_lot" class="nav-link d-flex align-items-center gap-2" ><i class="bi bi-caret-right"></i> Reserve a Lot</a></li>
                        <li><a href="#" class="nav-link d-flex align-items-center gap-2"><i class="bi bi-caret-right"></i> Reserve an Estate</a></li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2" data-bs-toggle="collapse" href="#memorialServicesSubmenu" role="button" aria-expanded="false" aria-controls="memorialServicesSubmenu">
                    <i class="bi bi-umbrella"></i> Memorial Services (Burial) <i class="bi bi-caret-down"></i>
                </a>
                <div class="collapse" id="memorialServicesSubmenu">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li><a href="#" class="nav-link d-flex align-items-center gap-2" ><i class="bi bi-caret-right"></i> Schedule a Memorial Service </a></li>
                        <li><a href="#" class="nav-link d-flex align-items-center gap-2"><i class="bi bi-caret-right"></i> My Memorial Services </a></li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center gap-2" aria-current="page" href="#">
                <i class="bi bi-credit-card"></i>                
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
              <a class="nav-link d-flex align-items-center gap-2" href="signout">
                <i class="bi bi-door-closed"></i>                
                Sign out
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>