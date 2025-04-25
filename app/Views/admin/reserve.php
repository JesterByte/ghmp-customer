<?= $this->extend("layouts/admin") ?>

<?= $this->section("content") ?>
<div id="map" class="rounded shadow" style="height: 500px;"></div>

<!-- Include Leaflet CSS and JS -->
<link rel="stylesheet" href="<?= base_url("css/leaflet.css") ?>" />
<script src="<?= base_url("js/leaflet.js") ?>"></script>

<?= $this->include("modals/confirm_lot_reservation") ?>
<?= $this->include("modals/confirm_estate_reservation") ?>

<script>
    // Initialize the map
    var map = L.map("map").setView([14.871318, 120.976566], 19); // Set default coordinates and zoom level

    // Add OpenStreetMap tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 22
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
    let estateRectangles = {};

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
                        <b>Lot Code:</b> ${lot.lot_id}<br>
                        <b>Lot Type: </b> ${lot.lot_type} Lot<br>
                        <b>Price: </b> ${lot.price} (with VAT & Memorial Care Fee)
                        <div class="text-center my-3">
                            <button class="btn btn-primary" onclick="showReserveLotModal('${lot.lot_id}', '${lot.lot_type}')">Reserve</button>
                        </div>
                    `);
                });
            })
            .catch(error => console.error("Error fetching lot data:", error));
    }

    function loadChosenLots() {
        fetch("<?= base_url('api/chosen_lots') ?>")
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

                    let color;
                    let fillColor;

                    if (lot.status === "Reserved") {
                        color = "yellow";
                        fillColor = "yellow";
                    } else if (lot.status === "Sold") {
                        color = "red";
                        fillColor = "red";
                    } else if (lot.status === "Sold and Occupied") {
                        color = "gray";
                        fillColor = "gray";
                    }

                    var rectangle = L.rectangle(bounds, {
                        color: color,
                        weight: 2,
                        fillColor: fillColor,
                        fillOpacity: 0.4
                    }).addTo(map);

                    // Store the rectangle reference
                    lotRectangles[lot.lot_id] = rectangle;

                    rectangle.bindPopup(`
                        <b>Lot Code:</b> ${lot.lot_id}<br>
                        <b>Lot Status: </b> ${lot.status}<br>
                    `);
                });
            })
            .catch(error => console.error("Error fetching lot data:", error));
    }

    function loadChosenEstates() {
        fetch("<?= base_url('api/chosen_estates') ?>")
            .then(response => response.json())
            .then(estates => {
                // Remove previous lot markers before adding new ones
                // Object.values(lotRectangles).forEach(rectangle => map.removeLayer(rectangle));
                for (let estateId in estateRectangles) {
                    if (map.hasLayer(estateRectangles[estateId])) {
                        map.removeLayer(estateRectangles[estateId]);
                    }
                }
                estateRectangles = {}; // Reset stored rectangles

                estates.forEach(estate => {
                    var bounds = [
                        [estate.latitude_start, estate.longitude_start],
                        [estate.latitude_end, estate.longitude_end]
                    ];

                    let color;
                    let fillColor;
                    if (estate.status === "Reserved") {
                        color = "yellow";
                        fillColor = "yellow";
                    } else if (estate.status === "Sold") {
                        color = "red";
                        fillColor = "red";
                    } else if (estate.status === "Sold and Occupied") {
                        color = "gray";
                        fillColor = "gray";
                    }

                    var rectangle = L.rectangle(bounds, {
                        color: color,
                        weight: 2,
                        fillColor: fillColor,
                        fillOpacity: 0.4
                    }).addTo(map);

                    // Store the rectangle reference
                    estateRectangles[estate.estate_id] = rectangle;

                    rectangle.bindPopup(`
                        <b>Estate Code:</b> ${estate.estate_id}<br>
                        <b>Estate Status: </b> ${estate.status}<br>
                    `);
                });
            })
            .catch(error => console.error("Error fetching lot data:", error));
    }


    function loadAvailableEstates() {
        fetch("<?= base_url('api/available_estates') ?>")
            .then(response => response.json())
            .then(estates => {
                for (let estateId in estateRectangles) {
                    if (map.hasLayer(estateRectangles[estateId])) {
                        map.removeLayer(estateRectangles[estateId]);
                    }
                }
                estateRectangles = {}; // Reset stored rectangles

                estates.forEach(estate => {
                    var bounds = [
                        [estate.latitude_start, estate.longitude_start],
                        [estate.latitude_end, estate.longitude_end]
                    ];

                    var rectangle = L.rectangle(bounds, {
                        color: "green",
                        weight: 2,
                        fillColor: "#00ff00",
                        fillOpacity: 0.4
                    }).addTo(map);

                    // Store the rectangle reference
                    estateRectangles[estate.estate_id] = rectangle;

                    rectangle.bindPopup(`
                        <b>Estate ID:</b> ${estate.formatted_estate_id}<br>
                        <b>Area:</b> ${estate.sqm}SQM<br>
                        <b>Number of Lots:</b> ${estate.number_of_lots}<br>
                        <b>Price: </b> ${estate.price} (with VAT & Memorial Care Fee)
                        <div class="text-center my-3">
                            <button class="btn btn-primary" onclick="showReserveEstateModal('${estate.estate_id}')">Reserve</button>
                        </div>
                    `);
                });
            })
            .catch(error => console.error("Error fetching estate data:", error));
    }

    // Function to show the reserve confirmation modal
    function showReserveLotModal(lotId, lotType) {
        $('#reserveLotModal').modal('show');
        $('#reserveLotId').val(lotId);
        $("#reserveLotType").val(lotType);
    }

    function showReserveEstateModal(estateId) {
        $('#reserveEstateModal').modal('show');
        $('#reserveEstateId').val(estateId);
    }

    // Submit reservation via AJAX
    function submitLotReservation() {
        var lotId = document.getElementById('reserveLotId').value;
        var lotType = document.getElementById("reserveLotType").value;

        fetch("<?= base_url('reserve/submitReservationLot') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    lot_id: lotId,
                    lot_type: lotType
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

    function submitEstateReservation() {
        var estateId = document.getElementById('reserveEstateId').value;

        fetch("<?= base_url('reserve/submitReservationEstate') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    estate_id: estateId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    localStorage.setItem("toastMessage", JSON.stringify({
                        icon: "<i class='bi bi-check-lg text-success'></i>",
                        message: "Estate reserved successfully!",
                        title: "Operation Completed"
                    }));

                    location.reload();
                } else {
                    showToast("<i class='bi bi-x-lg text-danger'></i>", "Estate reservation has failed.", "Operation Failed");
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
    loadAvailableEstates();
    loadChosenLots();
    loadChosenEstates();

    // Optional: Auto-refresh lots every 30 seconds (for real-time updates)
    setInterval(loadAvailableLots, 30000);
    setInterval(loadAvailableEstates, 30000);
    setInterval(loadChosenLots, 30000);
    setInterval(loadChosenEstates, 30000);
</script>
<?= $this->endSection(); ?>