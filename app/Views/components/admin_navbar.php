<header class="navbar sticky-top bg-primary flex-md-nowrap p-0 shadow" data-bs-theme="auto">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 text-white" href="#">GHMP</a>

  <!-- Notification Bell Icon with Dropdown -->
  <ul class="navbar-nav ms-auto me-3">
    <li class="nav-item dropdown">
      <a class="nav-link text-white position-relative me-3" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" id="notificationBell">
        <i class="bi bi-bell" id="bellIcon"></i>
        <!-- Notification Count Badge -->
        <span id="notificationCount" class="position-absolute top-0 start-100 translate-top badge rounded-circle bg-danger">
          <!-- <span class="visually-hidden">unread notifications</span> -->
        </span>
      </a>
      <!-- Notification Dropdown (Responsive Width) -->
      <ul class="dropdown-menu dropdown-menu-end shadow position-absolute"
        style="
          top: 40px; right: 0;
          width: 70vw;          /* Default for small screens (mobile) */
          max-width: 500px;     /* Limit the maximum width */
          max-height: 300px; 
          overflow-y: auto;
        " id="notificationList">
        <li class="dropdown-header">Notifications</li>
        <li>
          <div class="dropdown-item">Loading...</div>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>
        <!-- <li><a class="dropdown-item text-center" href="<?= base_url('notifications') ?>">View All</a></li> -->
      </ul>
    </li>
  </ul>

  <ul class="navbar-nav flex-row d-md-none">
    <li class="nav-item text-nowrap">
      <button class="nav-link px-3 text-white" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <i class="bi bi-list"></i>
      </button>
    </li>
  </ul>
</header>

<script>
  window.addEventListener("DOMContentLoaded", () => {
    const customerId = <?= (int) $session->get("user_id") ?>;

    if (customerId) {
      fetchNotifications(customerId);
    }
  });

  function fetchNotifications(customerId) {
    fetch(`<?= base_url("notification/fetchNotifications/") ?>${customerId}`)
      .then(response => {
        if (!response.ok) {
          throw new Error("Failed to fetch notifications.");
        }
        return response.json();
      })
      .then(notifications => {
        const notificationList = document.getElementById("notificationList");
        const notificationCount = document.getElementById("notificationCount");

        notificationList.innerHTML = `<li class="dropdown-header">Notifications</li>`;

        if (notifications.length > 0) {
          notificationCount.textContent = notifications.length;
          notificationCount.style.display = "block"; // Show badge

          notifications.forEach(notification => {
            notificationList.innerHTML += `
            <li class="d-flex justify-content-between align-items-center">
              <a class="dropdown-item text-wrap" href="<?= BASE_URL ?>${notification.link}" style="white-space: normal;">
                ${notification.message}
              </a>
              <button class="btn btn-sm btn-link text-primary mark-read" style="text-decoration: none;" onclick="markAsRead(${notification.id})">
                Mark as Read
              </button>
            </li>
          `;
          });

          // Add "Mark All as Read" option
          notificationList.innerHTML += `
          <li><hr class="dropdown-divider"></li>
          <li><button class="dropdown-item text-center" onclick="markAllAsRead(${customerId})">Mark All as Read</button></li>
        `;
        } else {
          notificationList.innerHTML += `<li class="dropdown-item">No new notifications</li>`;
          notificationCount.style.display = "none"; // Hide badge if no unread notifications
        }
      })
      .catch(error => console.error("Error fetching notifications:", error));
  }

  function markAsRead(notificationId) {
    fetch(`<?= base_url('notification/markAsRead/') ?>${notificationId}`)
      .then(response => {
        if (!response.ok) {
          throw new Error("Failed to mark notification as read.");
        }
        return response.json();
      })
      .then(data => {
        if (data.status === "success") {
          const customerId = <?= (int) $session->get("user_id") ?>;
          fetchNotifications(customerId); // Refresh notifications
        }
      })
      .catch(error => console.error("Error marking notification as read:", error));
  }

  function markAllAsRead(customerId) {
    fetch(`<?= base_url('notification/markAllAsRead/') ?>${customerId}`)
      .then(response => {
        if (!response.ok) {
          throw new Error("Failed to mark all notifications as read.");
        }
        return response.json();
      })
      .then(data => {
        if (data.status === "success") {
          fetchNotifications(customerId); // Refresh notifications
        }
      })
      .catch(error => console.error("Error marking all notifications as read:", error));
  }
</script>

<!-- JavaScript to toggle bell icon and mark as read -->
<script>
  const bellIcon = document.getElementById('bellIcon');
  const notificationBell = document.getElementById('notificationBell');
  const markReadButtons = document.querySelectorAll('.mark-read');
  const notificationCount = document.getElementById('notificationCount');

  notificationBell.addEventListener('show.bs.dropdown', () => {
    bellIcon.classList.remove('bi-bell');
    bellIcon.classList.add('bi-bell-fill');
  });

  notificationBell.addEventListener('hide.bs.dropdown', () => {
    bellIcon.classList.remove('bi-bell-fill');
    bellIcon.classList.add('bi-bell');
  });

  markReadButtons.forEach(button => {
    button.addEventListener('click', (e) => {
      e.preventDefault();
      const notificationItem = button.closest('li');
      notificationItem.remove();

      // Update notification count
      let count = parseInt(notificationCount.textContent);
      if (count > 1) {
        notificationCount.textContent = count - 1;
      } else {
        notificationCount.style.display = 'none'; // Hide if no more notifications
      }
    });
  });
</script>