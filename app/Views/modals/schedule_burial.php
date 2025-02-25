<!-- Schedule Burial Modal -->
<div class="modal fade" id="scheduleBurial" tabindex="-1" aria-labelledby="scheduleBurialLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scheduleBurialLabel">Schedule Memorial Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" method="post">
                    <input type="hidden" name="" id="assetId">
                    <div class="mb-3">
                        <p class="form-text">Personal Information</p>
                        <div class="form-floating">
                            <select name="relationship" id="relationship" class="form-select" required>
                                <option value="" disabled selected></option>
                                <option value="Spouse">Spouse</option>
                                <option value="Child">Child</option>
                                <option value="Parent">Parent</option>
                                <option value="Sibling">Sibling</option>
                                <option value="Other">Other</option>
                            </select>
                            <label for="relationship">Relationship to the Deceased</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <p class="form-text">Deceased Information</p>
                        <div class="form-floating mb-3">
                            <input type="text" name="first_name" placeholder="First Name" required id="firstName" class="form-control">
                            <label for="firstName">First Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="middle_name" placeholder="Middle Name (Optional)" id="middleName" class="form-control">
                            <label for="middleName">Middle Name (Optional)</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="last_name" placeholder="Last Name" required id="lastName" class="form-control">
                            <label for="lastName">Last Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <select name="suffix" id="suffix" class="form-select">
                                <option value=""></option>
                                <option value="Sr.">Sr.</option>
                                <option value="Jr.">Jr.</option>
                                <option value="I">I</option>
                                <option value="II">II</option>
                                <option value="III">III</option>
                                <option value="IV">IV</option>
                                <option value="V">V</option>
                            </select>
                            <label for="suffix">Suffix</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="date" name="date_of_birth" id="dateOfBirth" class="form-control">
                            <label for="dateOfBirth">Date of Birth</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="date" name="date_of_death" id="dateOfDeath" class="form-control">
                            <label for="dateOfDeath">Date of Death</label>
                        </div>
                        <div class="form-floating">
                            <textarea name="obituary" required placeholder="Obituary" id="obituary" class="form-control" style="height: 100px"></textarea>
                            <label for="obituary">Obituary</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <p class="form-text">Service Details</p>
                        <div class="form-floating mb-3">
                            <input type="datetime-local" name="datetime" placeholder="Date & Time" required id="datetime" class="form-control">
                            <label for="datetime">Date & Time</label>
                        </div>
                    </div>
                    <!-- <div class="form-floating mb-3">
                        <input type="date" name="date" id="date" class="form-control" placeholder="Date" required>
                        <label for="date">Date</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="time" name="time" id="time" class="form-control" placeholder="Time" required>
                        <label for="time">Time</label>
                    </div> -->
                </form>
                <input type="hidden" id="reserveEstateId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitReservation()">Yes, Reserve</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Submit reservation via AJAX
    function submitReservation() {
        // Debug: Log data before sending
        console.log("Sending:", {
            asset_id: $('#assetId').val(),
            relationship: $("#relationship").val(),
            first_name: $("#firstName").val(),
            middle_name: $("#middleName").val(),
            last_name: $("#lastName").val(),
            suffix: $("#suffix").val(),
            dateOfBirth: $("#dateOfBirth").val(),
            dateOfDeath: $("#dateOfDeath").val(),
            obituary: $("#obituary").val(),
            date_time: $("#datetime").val()
        });

        fetch("<?= base_url('reserve/submitMemorialService') ?>", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ 
                asset_id: $("#assetId").val(),
                relationship: $("#relationship").val(),
                first_name: $("#firstName").val(),
                middle_name: $("#middleName").val(),
                last_name: $("#lastName").val(),
                suffix: $("#suffix").val(),
                date_of_birth: $("#dateOfBirth").val(),
                date_of_death: $("#dateOfDeath").val(),
                obituary: $("#obituary").val(),
                date_time: $("#datetime").val()
            })
        })
        .then(response => response.text()) // Use text() to catch raw errors
        .then(data => {
            console.log("Raw Response:", data); // Log raw response

            try {
                let jsonData = JSON.parse(data);
                console.log("Parsed JSON:", jsonData);
                alert("Server Response: " + jsonData.message);
            } catch (e) {
                console.error("Error parsing JSON:", e, "Raw Response:", data);
                alert("Server returned non-JSON data: " + data);
            }
        })
        .catch(error => {
            console.error("Fetch error:", error);
            alert("Network error or server is unreachable.");
        });

    }
</script>
