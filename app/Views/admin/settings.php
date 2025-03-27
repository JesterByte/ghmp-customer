<?= $this->extend("layouts/admin") ?>

<?= $this->section("content") ?>
<div class="container py-4">
    <div class="row">
        <!-- User Profile Management -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5>User Profile Management</h5>
                </div>
                <div class="card-body">
                    <form id="profileForm" action="<?= base_url("settings/update_profile") ?>" method="post" class="needs-validation" novalidate>

                        <div class="form-text mb-3">Personal Information</div>

                        <div class="form-floating mb-3">
                            <input type="text" name="first_name" required placeholder="First Name" value="<?= $firstName ?>" id="firstName" class="form-control">
                            <label for="firstName">First Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="middle_name" placeholder="Middle Name" value="<?= $middleName ?>" id="middleName" class="form-control">
                            <label for="middleName">Middle Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="last_name" required placeholder="Last Name" value="<?= $lastName ?>" id="lastName" class="form-control">
                            <label for="lastName">Last Name</label>
                        </div>

                        <div class="form-floating mb-3">
                            <select name="suffix" id="suffix" class="form-select">
                                <option value="" selected></option>
                                <option <?= $suffix == "Sr." ? "selected" : "" ?> value="Sr.">Sr.</option>
                                <option <?= $suffix == "Jr." ? "selected" : "" ?> value="Jr.">Jr.</option>
                                <option <?= $suffix == "I" ? "selected" : "" ?> value="I">I</option>
                                <option <?= $suffix == "II" ? "selected" : "" ?> value="II">II</option>
                                <option <?= $suffix == "III" ? "selected" : "" ?> value="III">III</option>
                                <option <?= $suffix == "IV" ? "selected" : "" ?> value="IV">IV</option>
                                <option <?= $suffix == "V" ? "selected" : "" ?> value="V">V</option>
                            </select>
                            <label for="suffix">Suffix</label>
                        </div>

                        <div class="form-text mb-3">Contact Information</div>

                        <div class="form-floating mb-3">
                            <input type="tel" name="contact_number" id="contactNumber" value="<?= $contactNumber ?>" class="form-control" required placeholder="Contact Number">
                            <label for="contactNumber">Contact Number</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="email" name="email" required placeholder="Email Address" value="<?= $email ?>" id="email" class="form-control">
                            <label for="email">Email Address</label>
                        </div>

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userProfileUpdate">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Change Password -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5>Change Password</h5>
                </div>
                <div class="card-body">
                    <form id="changePasswordForm" action="<?= base_url("settings/change_password") ?>" method="post" class="needs-validation" novalidate>
                        <div class="form-floating mb-3">
                            <input type="password" name="current_password" required placeholder="Current Password" id="currentPassword" class="form-control">
                            <label for="currentPassword">Current Password</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" name="new_password" required placeholder="New Password" id="newPassword" class="form-control">
                            <label for="newPassword">New Password</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" name="confirm_password" required placeholder="Confirm New Password" id="confirmPassword" class="form-control">
                            <label for="confirmPassword">Confirm New Password</label>
                        </div>
                        <button type="submit" class="btn btn-warning">Update Password</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Beneficiary Management -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5>Beneficiary Management (Max 3)</h5>
                </div>
                <div class="card-body">
                    <?php if ($beneficiariesCount < 3): ?>
                        <form class="needs-validation" novalidate action="<?= base_url("settings/add_beneficiary") ?>" method="post">
                            <!-- <div class="mb-3">
                            <label for="beneficiaryName" class="form-label">Beneficiary Name</label>
                            <input type="text" class="form-control" id="beneficiaryName" placeholder="Enter beneficiary name">
                        </div> -->

                            <div class="form-text mb-3">Personal Information</div>

                            <div class="form-floating mb-3">
                                <input type="text" name="beneficiary_first_name" id="beneficiaryFirstName" class="form-control" required placeholder="First Name">
                                <label for="beneficiaryFirstName">First Name</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" name="beneficiary_middle_name" id="beneficiaryMiddleName" class="form-control" placeholder="Middle Name (Optional)">
                                <label for="beneficiaryMiddleName">Middle Name (Optional)</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" name="beneficiary_last_name" id="beneficiaryLastName" class="form-control" required placeholder="Last Name">
                                <label for="beneficiaryLastName">Last Name</label>
                            </div>

                            <div class="form-floating mb-3">
                                <select name="beneficiary_suffix" id="beneficiarySuffix" class="form-control">
                                    <option value=""></option>
                                    <option value="Sr.">Sr.</option>
                                    <option value="Jr.">Jr.</option>
                                    <option value="I">I</option>
                                    <option value="II">II</option>
                                    <option value="III">III</option>
                                    <option value="IV">IV</option>
                                    <option value="V">V</option>
                                </select>
                                <label for="beneficiarySuffix">Suffix</label>
                            </div>

                            <div class="form-floating mb-3">
                                <select name="relationship" id="relationship" class="form-select" required>
                                    <option value="" disabled selected></option>
                                    <option value="Spouse">Spouse</option>
                                    <option value="Child">Child</option>
                                    <option value="Parent">Parent</option>
                                    <option value="Sibling">Sibling</option>
                                    <option value="Other">Other</option>
                                </select>
                                <label for="relationship">Relationship</label>
                            </div>

                            <div class="form-text mb-3">Contact Information</div>

                            <div class="form-floating mb-3">
                                <input type="tel" name="beneficiary_contact_number" id="beneficiaryContactNumber" class="form-control" required placeholder="Contact Number">
                                <label for="beneficiaryContactNumber">Contact Number</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="email" name="beneficiary_email" id="beneficiaryEmail" class="form-control" required placeholder="Email Address">
                                <label for="beneficiaryEmail">Email Address</label>
                            </div>

                            <button type="submit" class="btn btn-secondary">Add Beneficiary</button>
                        </form>
                    <?php endif; ?>
                    <div class="mt-3">
                        <ul class="list-group">
                            <?php
                            foreach ($beneficiaries as $beneficiary) {
                                $middleName = !empty($beneficiary["middle_name"]) ? " " . $beneficiary["middle_name"] . " " : " ";
                                $suffixName = !empty($beneficiary["suffix_name"]) ? ", " . $beneficiary["suffix_name"] : "";
                                $beneficiaryFullName = $beneficiary["first_name"] . $middleName . $beneficiary["last_name"] . $suffixName;

                                if ($beneficiariesCount == 1) {
                                    $button = "";
                                } else {
                                    $button = "<button type='button' data-bs-toggle='modal' data-bs-full-name='" . $beneficiaryFullName . "' data-bs-target='#removeBeneficiary' class='remove-beneficiary-btn btn btn-danger btn-sm float-end' data-bs-beneficiary-id='" . $beneficiary["id"] . "'>Remove</button>";
                                }

                                echo "<li class='list-group-item'>$beneficiaryFullName $button</li>";


                                // echo "<li class='list-group-item'>$beneficiaryFullName <button type='button' data-bs-toggle='modal' data-bs-full-name='" . $beneficiaryFullName . "' data-bs-target='#removeBeneficiary' class='remove-beneficiary-btn btn btn-danger btn-sm float-end' data-bs-beneficiary-id='" . $beneficiary["id"] . "'>Remove</button></li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->include("modals/confirm_update_profile")  ?>
<?= $this->include("modals/confirm_remove_beneficiary")  ?>

<script src="<?= base_url("js/form_validation.js") ?>"></script>

<script>
    const removeButtons = document.querySelectorAll(".remove-beneficiary-btn");
    const beneficiaryIdHidden = document.getElementById("beneficiaryId");

    removeButtons.forEach(button => {
        button.addEventListener("click", function() {
            const beneficiaryId = this.getAttribute("data-bs-beneficiary-id");
            const beneficiaryFullName = this.getAttribute("data-bs-full-name");

            beneficiaryIdHidden.value = beneficiaryId;

            const removeBeneficiaryBody = document.getElementById("removeBeneficiaryBody");
            removeBeneficiaryBody.textContent = `Are you sure you want to remove ${beneficiaryFullName}?`;
        });
    });
</script>
<?= $this->endSection(); ?>