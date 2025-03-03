<?= $this->extend("layouts/admin") ?>

<?= $this->section("content") ?>

<div id="map" class="rounded shadow" style="height: 500px;"></div>

<!-- Include Leaflet CSS and JS -->
<link rel="stylesheet" href="<?= base_url("css/leaflet.css") ?>" />
<script src="<?= base_url("js/leaflet.js") ?>"></script>

<?= $this->include("modals/schedule_burial") ?>

<script>
    // Initialize the map
    var map = L.map("map").setView([14.871318, 120.976566], 18); // Set default coordinates and zoom level

    // Add OpenStreetMap tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 20
    }).addTo(map);

    // Fetch lot data from the API
    fetch("<?= base_url('api/owned_assets') ?>")
        .then(response => response.json())
        .then(assets => {
            assets.forEach(asset => {
                // Define rectangle bounds
                var bounds = [
                    [asset.latitude_start, asset.longitude_start], // Bottom-left corner
                    [asset.latitude_end, asset.longitude_end] // Top-right corner
                ];

                // Draw rectangle
                var rectangle = L.rectangle(bounds, {
                    color: "green", // Border color
                    weight: 2, // Border thickness
                    fillColor: "#00ff00", // Fill color
                    fillOpacity: 0.4 // Transparency
                }).addTo(map);

                // Bind a popup with lot details and Reserve button
                rectangle.bindPopup(`
                    <b>Asset ID:</b> ${asset.formatted_asset_id}<br>
                    <b>Occupancy:</b> ${asset.occupancy || "N/A"}<br>
                    <b>Capacity:</b> ${asset.capacity || "N/A"}<br>
                    <div class="text-center my-3">
                        <button class="btn btn-primary" onclick="showReserveModal('${asset.asset_id}', '${asset.asset_type}')">Schedule Burial</button>
                    </div>
                `);
            });
        })
        .catch(error => console.error("Error fetching lot data:", error));

    // Function to show the reserve confirmation modal
    function showReserveModal(assetId, category) {
        $('#scheduleBurial').modal('show');
        $('#assetId').val(assetId);
        $("#category").val(category);

        // Update burial type options based on category
        updateBurialType(category);
    }

    function updateBurialType(category) {
        const burialType = document.getElementById("burialType");

        // Clear previous options
        burialType.innerHTML = '<option value="" selected disabled>Select Burial Type</option>';

        // Burial type options based on category
        let options = [];
        if (category === "lot") {
            options = [{
                    value: "Standard",
                    text: "Standard"
                },
                {
                    value: "Cremation",
                    text: "Cremation"
                },
                {
                    value: "Bone Transfer",
                    text: "Bone Transfer"
                }
            ];
        } else if (category === "estate") {
            options = [{
                    value: "Standard",
                    text: "Standard Burial"
                },
                {
                    value: "Mausoleum",
                    text: "Mausoleum"
                },
                {
                    value: "Bone Transfer",
                    text: "Bone Transfer"
                }
            ];
        }

        // Append new options
        options.forEach(option => {
            let opt = document.createElement("option");
            opt.value = option.value;
            opt.textContent = option.text;
            burialType.appendChild(opt);
        });
    }
</script>

<?= $this->endSection(); ?>