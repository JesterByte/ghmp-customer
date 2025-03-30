<?= $this->extend("layouts/admin") ?>

<?= $this->section("content") ?>

<div id="map" class="rounded shadow" style="height: 500px;"></div>

<!-- Include Leaflet CSS and JS -->
<link rel="stylesheet" href="<?= base_url("css/leaflet.css") ?>" />
<script src="<?= base_url("js/leaflet.js") ?>"></script>

<?= $this->include("modals/schedule_burial") ?>

<script>
    // Initialize the map
    var map = L.map("map").setView([14.871318, 120.976566], 19);

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
                var bounds = [
                    [asset.latitude_start, asset.longitude_start],
                    [asset.latitude_end, asset.longitude_end]
                ];

                var occupancy = asset.occupancy || "0";
                var capacity = asset.capacity || "1";

                var rectangle = L.rectangle(bounds, {
                    color: "green",
                    weight: 2,
                    fillColor: "#00ff00",
                    fillOpacity: 0.4
                }).addTo(map);

                rectangle.bindPopup(`
                    <b>Asset ID:</b> ${asset.formatted_asset_id}<br>
                    <b>Capacity:</b> ${occupancy}/${capacity}<br>
                    <div class="text-center my-3">
                        <button class="btn btn-primary" onclick="showReserveModal('${asset.asset_id}', '${asset.asset_type}')">Schedule Burial</button>
                    </div>
                `);
            });
        })
        .catch(error => console.error("Error fetching lot data:", error));

    function showReserveModal(assetId, category) {
        $('#scheduleBurial').modal('show');
        $('#assetId').val(assetId);
        $("#category").val(category);
        updateBurialType(category);
    }

    function submitReservation() {
        const form = document.querySelector("#scheduleBurial .needs-validation");
        const submitBtn = document.getElementById("submitBtn");
        const submitSpinner = document.getElementById("submitSpinner");

        // Check if the form is valid
        if (form.checkValidity() === false) {
            form.classList.add("was-validated");
            return; // Stop further execution if the form is invalid
        }

        // Disable button and show spinner
        submitBtn.disabled = true;
        submitSpinner.style.display = "inline-block";

        // Determine the relationship value
        let relationship = $("#relationship").val();
        if (relationship === "Other") {
            relationship = $("#otherRelationship").val();
        }

        fetch("<?= base_url('reserve/submitMemorialService') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    asset_id: $("#assetId").val(),
                    relationship: relationship,
                    first_name: $("#firstName").val(),
                    middle_name: $("#middleName").val(),
                    last_name: $("#lastName").val(),
                    suffix: $("#suffix").val(),
                    date_of_birth: $("#dateOfBirth").val(),
                    date_of_death: $("#dateOfDeath").val(),
                    obituary: $("#obituary").val(),
                    category: $("#category").val(),
                    burial_type: $("#burialType").val(),
                    date_time: $("#datetime").val()
                })
            })
            .then(response => response.json())
            .then(jsonData => {
                if (jsonData.success) {
                    localStorage.setItem("toastMessage", JSON.stringify({
                        icon: "<i class='bi bi-check-lg text-success'></i>",
                        message: "Memorial service scheduled successfully!",
                        title: "Operation Completed"
                    }));
                    location.reload();
                } else {
                    showToast("<i class='bi bi-x-lg text-danger'></i>", "Scheduling failed.", "Operation Failed");
                }
            })
            .catch(error => {
                console.error("Fetch error:", error);
                showToast("<i class='bi bi-x-lg text-danger'></i>", "Network error or server is unreachable.", "Operation Failed");
            })
            .finally(() => {
                // Re-enable the button and hide spinner after request completes
                submitBtn.disabled = false;
                submitSpinner.style.display = "none";
            });
    }


    function showToast(htmlIcon, message, title = 'Notification', delay = 5000) {
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
        toastBody.textContent = message;

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

    document.addEventListener("DOMContentLoaded", function() {
        var toastData = localStorage.getItem("toastMessage");
        if (toastData) {
            toastData = JSON.parse(toastData);
            showToast(toastData.icon, toastData.message, toastData.title);
            localStorage.removeItem("toastMessage");
        }
    });
</script>

<?= $this->endSection(); ?>