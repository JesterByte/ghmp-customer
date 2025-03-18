<?= $this->extend("layouts/admin") ?>

<?= $this->section("content") ?>
<div id="map" class="rounded shadow" style="height: 500px;"></div>

<!-- Include Leaflet CSS and JS -->
<link rel="stylesheet" href="<?= base_url("css/leaflet.css") ?>" />
<script src="<?= base_url("js/leaflet.js") ?>"></script>

<?= $this->include("modals/confirm_lot_reservation") ?>

<script>
    // Initialize the map
    var map = L.map("map").setView([14.871318, 120.976566], 18); // Set default coordinates and zoom level

    // Add OpenStreetMap tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 20
    }).addTo(map);

    document.addEventListener("DOMContentLoaded", function() {
        var toastData = localStorage.getItem("toastMessage");
        if (toastData) {
            toastData = JSON.parse(toastData);
            showToast(toastData.icon, toastData.message, toastData.title);
            showToast("<i class='bi bi-check-lg text-success'></i>", "Go to My Lots & Estates", "My Lots & Estates Updated", 5000, "<?= base_url("/my_lots_and_estates") ?>");
            localStorage.removeItem("toastMessage"); // Clear it after showing
        }
    });

    let lotRectangles = {};

    function loadAvailableLots() {
        fetch("<?= base_url('api/available_lots') ?>")
            .then(response => response.json())
            .then(lots => {
                // Remove previous lot markers before adding new ones
                // Object.values(lotRectangles).forEach(rectangle => map.removeLayer(rectangle));
                for (let lotId in lotRectangles) {
                    if (map.hasLayer(lotRectangles[lotId])) {
                        map.removeLayer(lotRectangles[lotId]);
                    }
                }
                lotRectangles = {}; // Reset stored rectangles

                lots.forEach(lot => {
                    var bounds = [
                        [lot.latitude_start, lot.longitude_start],
                        [lot.latitude_end, lot.longitude_end]
                    ];

                    var rectangle = L.rectangle(bounds, {
                        color: "green",
                        weight: 2,
                        fillColor: "#00ff00",
                        fillOpacity: 0.4
                    }).addTo(map);

                    // Store the rectangle reference
                    lotRectangles[lot.lot_id] = rectangle;

                    rectangle.bindPopup(`
                        <b>Lot ID:</b> ${lot.formatted_lot_id}<br>
                        <div class="text-center my-3">
                            <button class="btn btn-primary" onclick="showReserveModal('${lot.lot_id}')">Reserve</button>
                        </div>
                    `);
                });
            })
            .catch(error => console.error("Error fetching lot data:", error));
    }

    // Function to show the reserve confirmation modal
    function showReserveModal(lotId) {
        $('#reserveModal').modal('show');
        $('#reserveLotId').val(lotId);
    }

    // Submit reservation via AJAX
    function submitReservation() {
        var lotId = document.getElementById('reserveLotId').value;

        fetch("<?= base_url('reserve/submitReservation') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    lot_id: lotId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Store toast message before refreshing
                    localStorage.setItem("toastMessage", JSON.stringify({
                        icon: "<i class='bi bi-check-lg text-success'></i>",
                        message: "Lot reserved successfully!",
                        title: "Operation Completed"
                    }));

                    location.reload(); // Refresh the page
                } else {
                    showToast("<i class='bi bi-x-lg text-danger'></i>", "Lot reservation has failed.", "Operation Failed");
                }
            })
            .catch(error => {
                console.error('Error during reservation:', error);
                showToast("<i class='bi bi-x-lg text-danger'></i>", "An error has occurred.", "Operation Failed");
            });
    }

    function showToast(htmlIcon, message, title = 'Notification', delay = 5000, link = '') {
        // Create toast container if it doesn't exist
        if (!this.toastContainer) {
            this.toastContainer = document.createElement('div');
            this.toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            document.body.appendChild(this.toastContainer);
        }

        // Create toast element
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');

        // Create toast header
        const toastHeader = document.createElement('div');
        toastHeader.className = 'toast-header';
        toastHeader.innerHTML = `
            ${htmlIcon}
            <strong class="me-auto">${title}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        `;

        // Create toast body
        const toastBody = document.createElement('div');
        toastBody.className = 'toast-body';
        if (link) {
            toastBody.innerHTML = `<a href="${link}">${message}</a>.`;
        } else {
            toastBody.textContent = message;
        }

        // Append header and body to toast
        toast.appendChild(toastHeader);
        toast.appendChild(toastBody);

        // Append toast to toast container
        this.toastContainer.appendChild(toast);

        // Initialize and show the toast
        const bootstrapToast = new bootstrap.Toast(toast, {
            delay: delay
        });
        bootstrapToast.show();

        // Remove toast from DOM after it hides
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }

    // Load available lots on page load
    loadAvailableLots();

    // Optional: Auto-refresh lots every 30 seconds (for real-time updates)
    setInterval(loadAvailableLots, 30000);
</script>


<!-- <script>
    // Initialize the map
    var map = L.map("map").setView([14.871318, 120.976566], 18);

    // Add OpenStreetMap tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 20
    }).addTo(map);

    // Fetch lot data from the API
    fetch("<?= base_url('api/available_lots') ?>")
        .then(response => response.json())
        .then(lots => {
            lots.forEach(lot => {
                // Define rectangle bounds
                var bounds = [
                    [lot.latitude_start, lot.longitude_start], // Bottom-left corner
                    [lot.latitude_end, lot.longitude_end]  // Top-right corner
                ];

                // Draw rectangle
                var rectangle = L.rectangle(bounds, {
                    color: "green",      // Border color
                    weight: 2,           // Border thickness
                    fillColor: "#00ff00", // Fill color
                    fillOpacity: 0.4     // Transparency
                }).addTo(map);

                // Bind a popup with lot details and a reserve button
                rectangle.bindPopup(`
                    <b>Lot ID:</b> ${lot.formatted_lot_id}<br>
                    <div class="text-center">
                        <button class="reserve-btn btn btn-primary" data-lot-id="${lot.lot_id}">Reserve</button>
                    </div>
                `);
            });

            // Add event listener to handle reservation button clicks
            map.on("popupopen", function (e) {
                document.querySelectorAll(".reserve-btn").forEach(button => {
                    button.addEventListener("click", function () {
                        var lotId = this.getAttribute("data-lot-id");

                        // Redirect to reservation page
                        window.location.href = "<?= base_url('reserve') ?>/" + lotId;
                    });
                });
            });
        })
        .catch(error => console.error("Error fetching lot data:", error));
</script> -->

<!-- <script>
    // Initialize the map
    var map = L.map("map").setView([14.871318, 120.976566], 18);; // Set default coordinates and zoom level

    // Add OpenStreetMap tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 20
    }).addTo(this.map);

    // Fetch lot data from the API
    fetch("<?= base_url('api/available_lots') ?>")
        .then(response => response.json())
        .then(lots => {
            lots.forEach(lot => {
                // Define rectangle bounds
                var bounds = [
                    [lot.latitude_start, lot.longitude_start], // Bottom-left corner
                    [lot.latitude_end, lot.longitude_end]  // Top-right corner
                ];

                // Draw rectangle
                var rectangle = L.rectangle(bounds, {
                    color: "green",      // Border color
                    weight: 2,           // Border thickness
                    fillColor: "#00ff00", // Fill color
                    fillOpacity: 0.4     // Transparency
                }).addTo(map);

                // Bind a popup with lot details
                rectangle.bindPopup(`
                    <b>Lot ID:</b> ${lot.formatted_lot_id}<br>
                `);
            });
        })
        .catch(error => console.error("Error fetching lot data:", error));
</script> -->

<?= $this->endSection(); ?>