<?= $this->extend("layouts/admin") ?>

<?= $this->section("content") ?>
<div id="map" class="rounded shadow" style="height: 500px;"></div>

<!-- Include Leaflet CSS and JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    // Initialize the map
    var map = L.map('map').setView([12.9716, 77.5946], 13); // Set default coordinates and zoom level

    // Add OpenStreetMap tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Data for available lots (latitude, longitude)
    var availableLots = [
        { lat: 12.9716, lon: 77.5946, lotNumber: 'A1-1' },
        { lat: 12.9736, lon: 77.5956, lotNumber: 'B1-1' },
        { lat: 12.9756, lon: 77.5966, lotNumber: 'C1-1' }
    ];

    // Loop through the available lots and add a marker for each
    availableLots.forEach(function(lot) {
        var marker = L.marker([lot.lat, lot.lon]).addTo(map);
        marker.bindPopup('<b>Lot: ' + lot.lotNumber + '</b>');
    });
</script>
<?= $this->endSection(); ?>