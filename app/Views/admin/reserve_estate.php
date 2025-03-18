<?= $this->extend("layouts/admin") ?>

<?= $this->section("content") ?>

<div id="map" class="rounded shadow" style="height: 500px;"></div>

<!-- Include Leaflet CSS and JS -->
<link rel="stylesheet" href="<?= base_url("css/leaflet.css") ?>" />
<script src="<?= base_url("js/leaflet.js") ?>"></script>

<?= $this->include("modals/confirm_estate_reservation") ?>

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

    let estateRectangles = {};

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
                        <div class="text-center my-3">
                            <button class="btn btn-primary" onclick="showReserveModal('${estate.estate_id}')">Reserve</button>
                        </div>
                    `);
                });
            })
            .catch(error => console.error("Error fetching estate data:", error));
    }

    function showReserveModal(estateId) {
        $('#reserveModal').modal('show');
        $('#reserveEstateId').val(estateId);
    }

    function submitReservation() {
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
        if (!this.toastContainer) {
            this.toastContainer = document.createElement('div');
            this.toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            document.body.appendChild(this.toastContainer);
        }

        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');

        const toastHeader = document.createElement('div');
        toastHeader.className = 'toast-header';
        toastHeader.innerHTML = `
            ${htmlIcon}
            <strong class="me-auto">${title}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        `;

        const toastBody = document.createElement('div');
        toastBody.className = 'toast-body';
        if (link) {
            toastBody.innerHTML = `<a href="${link}">${message}</a>.`;
        } else {
            toastBody.textContent = message;
        }

        toast.appendChild(toastHeader);
        toast.appendChild(toastBody);
        this.toastContainer.appendChild(toast);

        const bootstrapToast = new bootstrap.Toast(toast, {
            delay: delay
        });
        bootstrapToast.show();

        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }

    loadAvailableEstates();
    setInterval(loadAvailableEstates, 30000);
</script>

<?= $this->endSection(); ?>
