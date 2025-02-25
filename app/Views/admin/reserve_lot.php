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

                // Bind a popup with lot details and Reserve button
                rectangle.bindPopup(`
                    <b>Lot ID:</b> ${lot.formatted_lot_id}<br>
                    <div class="text-center">
                        <button class="btn btn-primary" onclick="showReserveModal('${lot.lot_id}')">Reserve</button>
                    </div>
                `);
            });
        })
        .catch(error => console.error("Error fetching lot data:", error));

    // Function to show the reserve confirmation modal
    function showReserveModal(lotId) {
        $('#reserveModal').modal('show');
        $('#reserveLotId').val(lotId);
    }
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