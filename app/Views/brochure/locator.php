<?= $this->extend('components/brochure_template') ?>

<?= $this->section('content') ?>
    <div class="container mt-4">
        <h1>Locator Map</h1>
        <p>Find the estate locations on the map below.</p>

        <!-- Map Container -->
        <div class="rounded shadow" id="map" style="height: 500px; width: 100%;"></div>
    </div>

    <!-- Leaflet.js CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Initialize map
            var map = L.map('map').setView([14.5995, 120.9842], 12); // Set to Manila as default

            // Add OpenStreetMap Tile Layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Example: Add a marker (adjust coordinates)
            var marker = L.marker([14.5995, 120.9842]).addTo(map)
                .bindPopup("<b>Example Location</b><br>Estate Location.")
                .openPopup();
        });
    </script>
<?= $this->endSection() ?>
