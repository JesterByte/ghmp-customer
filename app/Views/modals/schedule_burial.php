<!-- Schedule Burial Modal -->
<div class="modal fade" id="scheduleBurial" tabindex="-1" aria-labelledby="scheduleBurialLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scheduleBurialLabel">Schedule Memorial Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" method="post" class="needs-validation" novalidate>
                    <input type="hidden" name="asset_id" id="assetId">
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
                        <!-- Placeholder for dynamic input field -->
                        <div id="otherRelationshipInput" class="mt-3" style="display: none;">
                            <div class="form-floating">
                                <input type="text" name="other_relationship" placeholder="Please specify" id="otherRelationship" class="form-control">
                                <label for="otherRelationship">Please specify</label>
                            </div>
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
                        <input type="hidden" name="category" id="category">
                        <div class="form-floating mb-3">
                            <select name="burial_type" id="burialType" class="form-select" required>
                                <option value="" selected disabled>Select Burial Type</option>
                            </select>
                            <label for="burialType">Burial Type</label>
                        </div>
                        <div class="form-floating">
                            <input type="datetime-local" name="datetime" placeholder="Date & Time" required id="datetime" class="form-control">
                            <label for="datetime">Date & Time</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitReservation()">Yes, Reserve</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const dateOfBirth = document.getElementById("dateOfBirth");
        const dateOfDeath = document.getElementById("dateOfDeath");
        const datetime = document.getElementById("datetime");

        // Disable future dates for birth date and death date
        const today = new Date().toISOString().split("T")[0];
        dateOfBirth.setAttribute("max", today);
        dateOfDeath.setAttribute("max", today);

        // Disable past dates for burial date & time
        datetime.setAttribute("min", new Date().toISOString().slice(0, 16));

        // Update the max attribute of the death date based on the birth date
        dateOfBirth.addEventListener("change", function() {
            if (dateOfBirth.value) {
                dateOfDeath.setAttribute("min", dateOfBirth.value);
            } else {
                dateOfDeath.removeAttribute("min");
            }
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const relationshipSelect = document.getElementById("relationship");
        const otherRelationshipInput = document.getElementById("otherRelationshipInput");
        const otherRelationshipField = document.getElementById("otherRelationship");

        // Event listener for relationship dropdown change
        relationshipSelect.addEventListener("change", function() {
            if (relationshipSelect.value === "Other") {
                // Show the input field and make it required
                otherRelationshipInput.style.display = "block";
                otherRelationshipField.setAttribute("required", true);
            } else {
                // Hide the input field and remove the required attribute
                otherRelationshipInput.style.display = "none";
                otherRelationshipField.removeAttribute("required");
            }
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const scheduleBurial = document.getElementById("scheduleBurial");

        scheduleBurial.addEventListener("show.bs.modal", function() {
            updateBurialType();

        });
    });

    function updateBurialType() {
        const category = document.getElementById("category").value;
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
                    text: "Standard"
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

    // Attach change event to category
    document.getElementById("category").addEventListener("change", updateBurialType);
</script>