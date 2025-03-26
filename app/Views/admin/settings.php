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
                    <form id="profileForm">

                        <div class="form-text mb-3">Personal Information</div>

                        <div class="form-floating mb-3">
                            <input type="text" name="first_name" required placeholder="First Name" value="<?= $firstName ?>" id="firstName" class="form-control">
                            <label for="firstName">First Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="middle_name" required placeholder="Middle Name" value="<?= $middleName ?>" id="middleName" class="form-control">
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

                        <button type="submit" class="btn btn-primary">Update Profile</button>
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
                    <form>
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
                    <form>
                        <div class="mb-3">
                            <label for="beneficiaryName" class="form-label">Beneficiary Name</label>
                            <input type="text" class="form-control" id="beneficiaryName" placeholder="Enter beneficiary name">
                        </div>
                        <div class="mb-3">
                            <label for="relationship" class="form-label">Relationship</label>
                            <input type="text" class="form-control" id="relationship" placeholder="Enter relationship">
                        </div>
                        <button type="submit" class="btn btn-secondary">Add Beneficiary</button>
                    </form>
                    <div class="mt-3">
                        <ul class="list-group">
                            <li class="list-group-item">Beneficiary 1 <button class="btn btn-danger btn-sm float-end">Remove</button></li>
                            <li class="list-group-item">Beneficiary 2 <button class="btn btn-danger btn-sm float-end">Remove</button></li>
                            <li class="list-group-item">Beneficiary 3 <button class="btn btn-danger btn-sm float-end">Remove</button></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->include("modals/confirm_update_profile")  ?>
<?= $this->endSection(); ?>
