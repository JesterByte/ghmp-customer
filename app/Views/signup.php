<?= $this->extend("components/brochure_template") ?>

<?= $this->section("content") ?>
<div class="container mt-5">
    <h2 class="mb-4 text-center"><?= $pageTitle ?></h2>

    <form class="needs-validation row" novalidate action="<?= base_url('signup/submit') ?>" method="post" id="signupForm">
        <div class="col-lg-6">
            <h3 class="form-text">Personal Information</h3>
            <p class="text-muted">Please provide your personal details accurately.</p>

            <div class="form-floating mb-3">
                <input type="text" name="first_name" required placeholder="First Name" id="firstName" class="form-control">
                <label for="firstName">First Name</label>
                <small class="form-text text-muted">Enter your legal first name as it appears on official documents.</small>
                <div class="invalid-feedback">Please enter your first name.</div>
            </div>

            <div class="form-floating mb-3">
                <input type="text" name="middle_name" placeholder="Middle Name (Optional)" id="middleName" class="form-control">
                <label for="middleName">Middle Name (Optional)</label>
                <small class="form-text text-muted">If you have a middle name, enter it here. This field is optional.</small>
            </div>

            <div class="form-floating mb-3">
                <input type="text" name="last_name" required placeholder="Last Name" id="lastName" class="form-control">
                <label for="lastName">Last Name</label>
                <small class="form-text text-muted">Enter your legal last name as it appears on official documents.</small>
                <div class="invalid-feedback">Please enter your last name.</div>
            </div>

            <div class="form-floating mb-3">
                <select name="suffix" id="suffix" class="form-select">
                    <option value="" selected></option>
                    <option value="Sr.">Sr.</option>
                    <option value="Jr.">Jr.</option>
                    <option value="I">I</option>
                    <option value="II">II</option>
                    <option value="III">III</option>
                    <option value="IV">IV</option>
                    <option value="V">V</option>
                </select>
                <label for="suffix">Suffix</label>
                <small class="form-text text-muted">Select a suffix if applicable (e.g., Jr., Sr., III).</small>
            </div>

            <div class="form-floating mb-3">
                <input type="email" name="email" required placeholder="Email Address" id="email" class="form-control">
                <label for="email">Email Address</label>
                <small class="form-text text-muted">Enter a valid email address that you have access to.</small>
                <div class="invalid-feedback">Please enter a valid email address.</div>
            </div>

            <!-- Contact Number Field -->
            <div class="form-floating mb-3">
                <input type="text" name="contact_number" required placeholder="Contact Number" id="contactNumber" class="form-control">
                <label for="contactNumber">Contact Number</label>
                <small class="form-text text-muted">Enter your Philippine mobile number (e.g., 09123456789).</small>
                <div class="invalid-feedback">Please enter a valid Philippine mobile number (e.g., 09123456789).</div>
            </div>

            <div class="form-floating mb-3">
                <input type="password" name="password" required placeholder="Password" id="password" class="form-control">
                <label for="password">Password</label>
                <small class="form-text text-muted">
                    Your password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*#?&).
                </small>
                <div class="invalid-feedback">Password must be at least 8 characters long and include a number and a special character.</div>
            </div>

            <div class="form-floating mb-3">
                <input type="password" name="confirm_password" required placeholder="Confirm Password" id="confirmPassword" class="form-control">
                <label for="confirmPassword">Confirm Password</label>
                <small class="form-text text-muted">Re-enter your password to confirm it.</small>
                <div class="invalid-feedback">Passwords do not match.</div>
            </div>
        </div>
        <div class="col-lg-6">
            <h3 class="form-text">Beneficiary Information</h3>
            <p class="text-muted">Please provide the details of your primary beneficiary. You can add more beneficiaries after signing up.</p>

            <div class="form-floating mb-3">
                <select name="beneficiary_relationship" id="beneficiaryRelationship" class="form-select" required>
                    <option value="" selected disabled>Select Relationship</option>
                    <option value="Spouse">Spouse</option>
                    <option value="Child">Child</option>
                    <option value="Parent">Parent</option>
                    <option value="Sibling">Sibling</option>
                    <option value="Friend">Friend</option>
                    <option value="Other">Other</option>
                </select>
                <label for="beneficiaryRelationship">Relationship to Owner</label>
                <div class="invalid-feedback">Please select a relationship.</div>
            </div>

            <!-- Hidden input field for "Other" -->
            <div class="form-floating mb-3" id="otherRelationshipContainer" style="display: none;">
                <input type="text" name="beneficiary_other_relationship" placeholder="Specify Relationship" id="beneficiaryOtherRelationship" class="form-control">
                <label for="beneficiaryOtherRelationship">Specify Relationship</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" name="beneficiary_first_name" required placeholder="First Name" id="beneficiaryFirstName" class="form-control">
                <label for="beneficiaryFirstName">First Name</label>
                <div class="invalid-feedback">Please enter the beneficiary's first name.</div>
            </div>

            <div class="form-floating mb-3">
                <input type="text" name="beneficiary_middle_name" placeholder="Middle Name (Optional)" id="beneficiaryMiddleName" class="form-control">
                <label for="beneficiaryMiddleName">Middle Name (Optional)</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" name="beneficiary_last_name" required placeholder="Last Name" id="beneficiaryLastName" class="form-control">
                <label for="beneficiaryLastName">Last Name</label>
                <div class="invalid-feedback">Please enter the beneficiary's last name.</div>
            </div>

            <div class="form-floating mb-3">
                <select name="beneficiary_suffix" id="beneficiarySuffix" class="form-select">
                    <option value="" selected></option>
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
                <input type="text" name="beneficiary_contact_number" required placeholder="Contact Number" id="beneficiaryContactNumber" class="form-control">
                <label for="beneficiaryContactNumber">Contact Number</label>
                <small class="form-text text-muted">Enter the beneficiary's Philippine mobile number (e.g., 09123456789).</small>
                <div class="invalid-feedback">Please enter a valid Philippine mobile number (e.g., 09123456789).</div>
            </div>

            <div class="form-floating mb-3">
                <input type="email" name="beneficiary_email" required placeholder="Email Address" id="beneficiaryEmail" class="form-control">
                <label for="beneficiaryEmail">Email Address</label>
                <div class="invalid-feedback">Please enter a valid email address.</div>
            </div>
        </div>

        <div class="d-grid gap-2">
            <button class="btn btn-primary" type="submit">Submit</button>
        </div>
    </form>

    <p class="mt-3">Already have an account? <a href="signin">Sign In</a></p>
</div>

<!-- Add jQuery and custom validation script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $("#beneficiaryRelationship").change(function() {
            if ($(this).val() === "Other") {
                $("#otherRelationshipContainer").show();
                $("#beneficiaryOtherRelationship").attr("required", true);
            } else {
                $("#otherRelationshipContainer").hide();
                $("#beneficiaryOtherRelationship").removeAttr("required");
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        // Real-time validation for email fields
        function validateEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }

        // Real-time validation for Philippine mobile number
        function validateContactNumber(contactNumber) {
            const regex = /^(09\d{9}|\+639\d{9})$/;
            return regex.test(contactNumber);
        }

        // Real-time validation for password
        function validatePassword(password) {
            const regex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/;
            return regex.test(password);
        }

        // Validate First Name and Last Name
        $("#firstName, #lastName, #beneficiaryFirstName, #beneficiaryLastName").on("input", function() {
            if ($(this).val().trim() === "") {
                $(this).addClass("is-invalid").removeClass("is-valid");
            } else {
                $(this).removeClass("is-invalid").addClass("is-valid");
            }
        });

        // Validate Email
        $("#email, #beneficiaryEmail").on("input", function() {
            if (!validateEmail($(this).val())) {
                $(this).addClass("is-invalid").removeClass("is-valid");
            } else {
                $(this).removeClass("is-invalid").addClass("is-valid");
            }
        });

        // Validate Contact Number
        $("#contactNumber, #beneficiaryContactNumber").on("input", function() {
            if (!validateContactNumber($(this).val())) {
                $(this).addClass("is-invalid").removeClass("is-valid");
            } else {
                $(this).removeClass("is-invalid").addClass("is-valid");
            }
        });

        // Validate Password
        $("#password").on("input", function() {
            if (!validatePassword($(this).val())) {
                $(this).addClass("is-invalid").removeClass("is-valid");
            } else {
                $(this).removeClass("is-invalid").addClass("is-valid");
            }
        });

        // Validate Confirm Password
        $("#confirmPassword").on("input", function() {
            if ($(this).val() !== $("#password").val()) {
                $(this).addClass("is-invalid").removeClass("is-valid");
            } else {
                $(this).removeClass("is-invalid").addClass("is-valid");
            }
        });

        // Form submission handler
        $("#signupForm").on("submit", function(e) {
            let isValid = true;

            // Check all required fields
            $("#firstName, #lastName, #email, #password, #confirmPassword, #contactNumber, #beneficiaryRelationship, #beneficiaryFirstName, #beneficiaryLastName, #beneficiaryContactNumber, #beneficiaryEmail").each(function() {
                if ($(this).val().trim() === "") {
                    $(this).addClass("is-invalid").removeClass("is-valid");
                    isValid = false;
                }
            });

            // Check email validity
            $("#email, #beneficiaryEmail").each(function() {
                if (!validateEmail($(this).val())) {
                    $(this).addClass("is-invalid").removeClass("is-valid");
                    isValid = false;
                }
            });

            // Check contact number validity
            if (!validateContactNumber($("#contactNumber").val())) {
                $("#contactNumber").addClass("is-invalid").removeClass("is-valid");
                isValid = false;
            }

            if (!validateContactNumber($("#beneficiaryContactNumber").val())) {
                $("#beneficiaryContactNumber").addClass("is-invalid").removeClass("is-valid");
                isValid = false;
            }

            // Check password validity
            if (!validatePassword($("#password").val())) {
                $("#password").addClass("is-invalid").removeClass("is-valid");
                isValid = false;
            }

            // Check if passwords match
            if ($("#confirmPassword").val() !== $("#password").val()) {
                $("#confirmPassword").addClass("is-invalid").removeClass("is-valid");
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault(); // Prevent form submission if validation fails
            }
        });
    });
</script>
<?= $this->endSection() ?>