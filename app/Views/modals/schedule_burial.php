<!-- Schedule Burial Modal -->
<div class="modal fade" id="scheduleBurial" tabindex="-1" aria-labelledby="scheduleBurialLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scheduleBurialLabel">Schedule a Memorial Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" method="post" class="needs-validation" novalidate>
                    <input type="hidden" name="asset_id" id="assetId">

                    <!-- Personal Information -->
                    <div class="mb-3">
                        <p class="form-text fw-bold">Your Relationship to the Deceased</p>
                        <div class="form-floating">
                            <select name="relationship" id="relationship" class="form-select" required>
                                <option value="" disabled selected>Select Relationship</option>
                                <option value="Spouse">Spouse</option>
                                <option value="Child">Child</option>
                                <option value="Parent">Parent</option>
                                <option value="Sibling">Sibling</option>
                                <option value="Other">Other (Please Specify)</option>
                            </select>
                            <label for="relationship">Relationship</label>
                        </div>

                        <!-- Show input field if "Other" is selected -->
                        <div id="otherRelationshipInput" class="mt-3" style="display: none;">
                            <div class="form-floating">
                                <input type="text" name="other_relationship" placeholder="Specify your relationship" id="otherRelationship" class="form-control">
                                <label for="otherRelationship">Specify Your Relationship</label>
                            </div>
                        </div>
                    </div>

                    <!-- Deceased Information -->
                    <div class="mb-3">
                        <p class="form-text fw-bold">Deceased's Information</p>

                        <div class="form-floating mb-3">
                            <input type="text" name="first_name" placeholder="Enter first name" required id="firstName" class="form-control">
                            <label for="firstName">First Name</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" name="middle_name" placeholder="Enter middle name (if applicable)" id="middleName" class="form-control">
                            <label for="middleName">Middle Name (Optional)</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" name="last_name" placeholder="Enter last name" required id="lastName" class="form-control">
                            <label for="lastName">Last Name</label>
                        </div>

                        <div class="form-floating mb-3">
                            <select name="suffix" id="suffix" class="form-select">
                                <option value="" selected>No Suffix</option>
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
                            <label for="dateOfBirth">Date of Birth (Cannot be in the future)</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="date" name="date_of_death" id="dateOfDeath" class="form-control">
                            <label for="dateOfDeath">Date of Death (Cannot be in the future)</label>
                        </div>

                        <div class="form-floating">
                            <textarea name="obituary" required placeholder="Share a short message" id="obituary" class="form-control" style="height: 100px"></textarea>
                            <label for="obituary">Obituary (Brief Message)</label>
                        </div>
                    </div>

                    <!-- Service Details -->
                    <div class="mb-3">
                        <p class="form-text fw-bold">Service Details</p>
                        <input type="hidden" name="category" id="category">

                        <div class="form-floating mb-3">
                            <select name="burial_type" id="burialType" class="form-select" required>
                                <option value="" selected disabled>Select Burial Type</option>
                            </select>
                            <label for="burialType">Burial Type (Based on selected category)</label>
                        </div>

                        <div class="form-floating">
                            <input type="datetime-local" name="datetime" placeholder="Select date & time" required id="datetime" class="form-control">
                            <label for="datetime">Burial Date & Time (At least 3 days from today)</label>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submitBtn" onclick="submitReservation()">
                    <span id="submitSpinner" class="spinner-border spinner-border-sm me-2" style="display: none;"></span>
                    Yes, Reserve
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const dateOfBirth = document.getElementById("dateOfBirth");
        const dateOfDeath = document.getElementById("dateOfDeath");
        const datetime = document.getElementById("datetime");

        const today = new Date().toISOString().split("T")[0];
        dateOfBirth.setAttribute("max", today);
        dateOfDeath.setAttribute("max", today);

        // Set minimum burial date (3 days from today)
        let minBurialDate = new Date();
        minBurialDate.setDate(minBurialDate.getDate() + 3);
        datetime.setAttribute("min", minBurialDate.toISOString().slice(0, 16));

        dateOfBirth.addEventListener("change", function() {
            dateOfDeath.setAttribute("min", dateOfBirth.value || "");
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        const relationshipSelect = document.getElementById("relationship");
        const otherRelationshipInput = document.getElementById("otherRelationshipInput");
        const otherRelationshipField = document.getElementById("otherRelationship");

        relationshipSelect.addEventListener("change", function() {
            otherRelationshipInput.style.display = relationshipSelect.value === "Other" ? "block" : "none";
            otherRelationshipField.toggleAttribute("required", relationshipSelect.value === "Other");
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("scheduleBurial").addEventListener("show.bs.modal", updateBurialType);
    });

    function updateBurialType() {
        const category = document.getElementById("category").value;
        const burialType = document.getElementById("burialType");

        burialType.innerHTML = '<option value="" selected disabled>Select Burial Type</option>';
        let options = category === "lot" ? [{
            value: "Standard",
            text: "Standard"
        }, {
            value: "Columbarium",
            text: "Columbarium"
        }, {
            value: "Bone Transfer",
            text: "Bone Transfer"
        }] : [{
            value: "Mausoleum",
            text: "Mausoleum"
        }, {
            value: "Bone Transfer",
            text: "Bone Transfer"
        }];

        options.forEach(option => burialType.add(new Option(option.text, option.value)));
    }
</script>