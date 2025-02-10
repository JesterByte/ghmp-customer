<?= $this->extend("layouts/admin") ?>

<?= $this->section("content") ?>

<div id="map" class="rounded shadow" style="height: 500px;"></div>

<!-- Include Leaflet CSS and JS -->
<link rel="stylesheet" href="<?= base_url("css/leaflet.css") ?>" />
<script src="<?= base_url("js/leaflet.js") ?>"></script>

<?= $this->include("modals/confirm_estate_reservation.php") ?>

<script>
    // Initialize the map
    var map = L.map("map").setView([14.871318, 120.976566], 18); // Set default coordinates and zoom level

    // Add OpenStreetMap tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 20
    }).addTo(map);

    // Fetch lot data from the API
    fetch("<?= base_url('api/available_estates') ?>")
        .then(response => response.json())
        .then(estates => {
            estates.forEach(estate => {
                // Define rectangle bounds
                var bounds = [
                    [estate.latitude_start, estate.longitude_start], // Bottom-left corner
                    [estate.latitude_end, estate.longitude_end]  // Top-right corner
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
                    <b>Estate ID:</b> ${estate.formatted_estate_id}<br>
                    <b>Area:</b> ${estate.sqm}SQM<br>
                    <b>Number of Lots:</b> ${estate.number_of_lots}<br>
                    <div class="text-center">
                        <button class="btn btn-primary" onclick="showReserveModal('${estate.estate_id}')">Reserve</button>
                    </div>
                `);
            });
        })
        .catch(error => console.error("Error fetching lot data:", error));

    // Function to show the reserve confirmation modal
    function showReserveModal(estateId) {
        $('#reserveModal').modal('show');
        $('#reserveEstateId').val(estateId);
    }
</script>

<?= $this->endSection(); ?>