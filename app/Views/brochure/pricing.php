<?= $this->extend('components/brochure_template') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <h1 class="display-4 text-center">Pricing</h1>

    <!-- Pricing Type Selection -->
    <div class="text-center">
        <div class="btn-group mb-4" role="group" aria-label="Pricing Type">
            <button type="button" class="btn btn-outline-primary btn-lg" id="lotBtn">
                <i class="fas fa-cogs"></i> Lot
            </button>
            <button type="button" class="btn btn-outline-primary btn-lg" id="estateBtn">
                <i class="fas fa-home"></i> Estate
            </button>
            <button type="button" class="btn btn-outline-primary btn-lg" id="burialBtn">
                <i class="fas fa-cross"></i> Burial
            </button>
        </div>
    </div>

    <!-- Lot Pricing Filters -->
    <div id="lotFilters" class="d-none">
        <div class="row mb-4">
            <div class="col-md-6">
                <label for="phaseSelect" class="form-label">Phase</label>
                <select class="form-select shadow-sm" id="phaseSelect">
                    <option selected>Choose Phase</option>
                    <option value="1">Phase 1</option>
                    <option value="2">Phase 2</option>
                    <option value="3">Phase 3</option>
                    <option value="4">Phase 4</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="lotTypeSelect" class="form-label">Lot Type</label>
                <select class="form-select shadow-sm" id="lotTypeSelect">
                    <option selected>Choose Lot Type</option>
                    <option value="supreme">Supreme</option>
                    <option value="special">Special</option>
                    <option value="standard">Standard</option>
                </select>
            </div>
        </div>

        <div id="lotPricing" class="mt-4">
            <!-- Dynamic content will be inserted here -->
        </div>
    </div>

    <!-- Estate Pricing Filters -->
    <div id="estateFilters" class="d-none">
        <div class="row mb-4 d-flex justify-content-center">
            <div class="col-md-6">
                <label for="estateTypeSelect" class="form-label">Estate Type</label>
                <select class="form-select shadow-sm" id="estateTypeSelect">
                    <option selected>Choose Estate Type</option>
                    <option value="A">Estate Type A</option>
                    <option value="B">Estate Type B</option>
                    <option value="C">Estate Type C</option>
                </select>
            </div>
        </div>

        <div id="estatePricing" class="mt-4">
            <!-- Dynamic content will be inserted here -->
        </div>
    </div>

    <!-- Burial Pricing Filters -->
    <div id="burialFilters" class="d-none">
        <div class="row mb-4">
            <div class="col-md-6">
                <label for="burialAssetTypeSelect" class="form-label">Asset Type</label>
                <select class="form-select shadow-sm" id="burialAssetTypeSelect">
                    <option selected value="">Choose Asset Type</option>
                    <option value="Lot">Lot</option>
                    <option value="Estate">Estate</option>
                </select>
            </div>
            <div class="col-md-6">
                <div id="burialTypeContainer" class="d-none">
                    <label for="burialTypeSelect" class="form-label">Burial Type</label>
                    <select class="form-select shadow-sm" id="burialTypeSelect">
                        <option selected value="">Choose Burial Type</option>
                    </select>
                </div>
            </div>
        </div>

        <div id="burialPricing" class="mt-4">
            <div id="lotBurialPricing" class="d-none"></div>
            <div id="estateBurialPricing" class="d-none"></div>
        </div>
    </div>
</div>

<script src="<?= BASE_URL ?>/js/jquery.js"></script>

<script>
    // Handle Pricing Type Selection
    $('#lotBtn').click(function() {
        // Remove active class from all buttons
        $('#lotBtn').addClass('active');
        $('#estateBtn').removeClass('active');
        $('#burialBtn').removeClass('active');

        // Show Lot filters and hide others
        $('#lotFilters').removeClass('d-none');
        $('#estateFilters').addClass('d-none');
        $('#burialFilters').addClass('d-none');
    });

    $('#estateBtn').click(function() {
        // Remove active class from all buttons
        $('#estateBtn').addClass('active');
        $('#lotBtn').removeClass('active');
        $('#burialBtn').removeClass('active');

        // Show Estate filters and hide others
        $('#lotFilters').addClass('d-none');
        $('#estateFilters').removeClass('d-none');
        $('#burialFilters').addClass('d-none');
    });

    $('#burialBtn').click(function() {
        // Remove active class from all buttons
        $('#burialBtn').addClass('active');
        $('#lotBtn').removeClass('active');
        $('#estateBtn').removeClass('active');

        // Show Burial filters and hide others
        $('#lotFilters').addClass('d-none');
        $('#estateFilters').addClass('d-none');
        $('#burialFilters').removeClass('d-none');
    });

    // Handle Burial Asset Type Selection
    $('#burialAssetTypeSelect').change(function() {
        const assetType = $(this).val();
        const burialTypeSelect = $('#burialTypeSelect');
        const burialTypeContainer = $('#burialTypeContainer');
        
        // Clear previous burial pricing
        $('#lotBurialPricing, #estateBurialPricing').addClass('d-none').html('');
        
        if (assetType) {
            // Clear and update burial type options
            burialTypeSelect.html('<option selected value="">Choose Burial Type</option>');
            
            if (assetType === 'Lot') {
                const lotOptions = ['Standard', 'Columbarium', 'Bone Transfer'];
                lotOptions.forEach(option => {
                    burialTypeSelect.append(`<option value="${option}">${option}</option>`);
                });
                burialTypeContainer.removeClass('d-none');
            } else if (assetType === 'Estate') {
                const estateOptions = ['Mausoleum', 'Bone Transfer'];
                estateOptions.forEach(option => {
                    burialTypeSelect.append(`<option value="${option}">${option}</option>`);
                });
                burialTypeContainer.removeClass('d-none');
            }
        } else {
            burialTypeContainer.addClass('d-none');
        }
    });

    // Handle burial type selection
    $('#burialTypeSelect').change(function() {
        const assetType = $('#burialAssetTypeSelect').val();
        const burialType = $(this).val();

        if (assetType && burialType) {
            $.ajax({
                url: "<?= base_url("pricing/getBurialPricing") ?>",
                method: 'POST',
                data: {
                    category: assetType,
                    burial_type: burialType
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Raw Burial Response:', response);
                    
                    if (response) {
                        const formatCurrency = (value) => {
                            return new Intl.NumberFormat('en-PH', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }).format(value);
                        };

                        const pricingHtml = `
                            <div class="card shadow-lg border-${assetType === 'Lot' ? 'danger' : 'dark'} mb-3">
                                <div class="card-body">
                                    <h4 class="card-title text-${assetType === 'Lot' ? 'danger' : 'dark'}">
                                        ${assetType} Burial Pricing
                                    </h4>
                                    <p><strong>${response.burial_type}:</strong> ₱${formatCurrency(response.price)}</p>
                                </div>
                            </div>
                        `;
                        
                        const container = assetType === 'Lot' ? '#lotBurialPricing' : '#estateBurialPricing';
                        $(container).html(pricingHtml).removeClass('d-none');
                    }
                },
                error: function() {
                    alert('An error occurred while fetching burial pricing data.');
                }
            });
        }
    });
</script>

<script>
    $(document).ready(function() {
        // Listen for changes on the phase and lot type dropdowns
        $('#phaseSelect, #lotTypeSelect').change(function() {
            const phase = $('#phaseSelect').val();
            const lotType = $('#lotTypeSelect').val();

            // Make sure both phase and lot type are selected
            if (phase && lotType) {
                $.ajax({
                    url: "<?= base_url("pricing/getLotPricing") ?>", // URL to your controller method
                    method: 'POST',
                    data: {
                        phase: phase,
                        lotType: lotType
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Log the raw response
                        console.log('Raw Response:', response);
                        
                        // Clear any previous lot pricing details
                        $('#lotPricing').html('');

                        // Check if response exists and has data
                        if (response) {
                            // Format functions
                            const formatCurrency = (value) => {
                                return new Intl.NumberFormat('en-PH', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }).format(value);
                            };

                            const formatPercentage = (value) => {
                                return `${Math.round(value * 100)}%`;
                            };

                            const lotCard = `
                                <div class="card shadow-lg border-primary mb-3">
                                    <div class="card-body">
                                        <h4 class="card-title text-primary">Lot Pricing Details</h4>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5 class="text-primary">Basic Information</h5>
                                                <p><strong>Phase:</strong> ${response.phase}</p>
                                                <p><strong>Lot Type:</strong> ${response.lot_type}</p>
                                                <p><strong>Base Price:</strong> ₱${formatCurrency(response.lot_price)}</p>
                                                <p><strong>VAT:</strong> ${formatPercentage(response.vat)}</p>
                                                <p><strong>Memorial Care Fee:</strong> ₱${formatCurrency(response.memorial_care_fee)}</p>
                                                <p><strong>Total Purchase Price:</strong> ₱${formatCurrency(response.total_purchase_price)}</p>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <h5 class="text-primary">Cash Payment Options</h5>
                                                <p><strong>Cash Sale Price:</strong> ₱${formatCurrency(response.cash_sale)}</p>
                                                <p><strong>Cash Sale Discount:</strong> ${formatPercentage(response.cash_sale_discount)}</p>
                                                
                                                <h5 class="text-primary mt-3">6 Months Payment Plan</h5>
                                                <p><strong>Down Payment:</strong> ₱${formatCurrency(response.six_months_down_payment)}</p>
                                                <p><strong>Monthly:</strong> ₱${formatCurrency(response.six_months_monthly_amortization)}</p>
                                                <p><strong>Discount:</strong> ${formatPercentage(response.six_months_discount)}</p>
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <h5 class="text-primary">Installment Options</h5>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Term</th>
                                                            <th>Down Payment (${formatPercentage(response.down_payment_rate)})</th>
                                                            <th>Monthly Payment</th>
                                                            <th>Interest Rate</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1 Year</td>
                                                            <td>₱${formatCurrency(response.down_payment)}</td>
                                                            <td>₱${formatCurrency(response.monthly_amortization_one_year)}</td>
                                                            <td>${formatPercentage(response.one_year_interest_rate)}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>2 Years</td>
                                                            <td>₱${formatCurrency(response.down_payment)}</td>
                                                            <td>₱${formatCurrency(response.monthly_amortization_two_years)}</td>
                                                            <td>${formatPercentage(response.two_years_interest_rate)}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>3 Years</td>
                                                            <td>₱${formatCurrency(response.down_payment)}</td>
                                                            <td>₱${formatCurrency(response.monthly_amortization_three_years)}</td>
                                                            <td>${formatPercentage(response.three_years_interest_rate)}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>4 Years</td>
                                                            <td>₱${formatCurrency(response.down_payment)}</td>
                                                            <td>₱${formatCurrency(response.monthly_amortization_four_years)}</td>
                                                            <td>${formatPercentage(response.four_years_interest_rate)}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>5 Years</td>
                                                            <td>₱${formatCurrency(response.down_payment)}</td>
                                                            <td>₱${formatCurrency(response.monthly_amortization_five_years)}</td>
                                                            <td>${formatPercentage(response.five_years_interest_rate)}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            $('#lotPricing').append(lotCard);
                        } else {
                            $('#lotPricing').html('<div class="alert alert-info">No pricing data available for the selected phase and lot type.</div>');
                        }
                    },
                    error: function() {
                        alert('An error occurred while fetching lot pricing data.');
                    }
                });
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        // Listen for changes on the estate type dropdown
        $('#estateTypeSelect').change(function() {
            const estateType = $(this).val();

            if (estateType) {
                $.ajax({
                    url: "<?= base_url("pricing/getEstatePricing") ?>",
                    method: 'POST',
                    data: {
                        estateType: estateType
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log('Raw Estate Response:', response);
                        
                        $('#estatePricing').html('');

                        if (response) {
                            const formatCurrency = (value) => {
                                return new Intl.NumberFormat('en-PH', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }).format(value);
                            };

                            const formatPercentage = (value) => {
                                return `${Math.round(value * 100)}%`;
                            };

                            const estateCard = `
                                <div class="card shadow-lg border-warning mb-3">
                                    <div class="card-body">
                                        <h4 class="card-title text-warning">Estate Pricing Details</h4>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5 class="text-warning">Basic Information</h5>
                                                <p><strong>Estate Type:</strong> ${response.estate}</p>
                                                <p><strong>Base Price:</strong> ₱${formatCurrency(response.lot_price)}</p>
                                                <p><strong>VAT:</strong> ${formatPercentage(response.vat)}</p>
                                                <p><strong>Memorial Care Fee:</strong> ₱${formatCurrency(response.memorial_care_fee)}</p>
                                                <p><strong>Total Purchase Price:</strong> ₱${formatCurrency(response.total_purchase_price)}</p>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <h5 class="text-warning">Cash Payment Options</h5>
                                                <p><strong>Cash Sale Price:</strong> ₱${formatCurrency(response.cash_sale)}</p>
                                                <p><strong>Cash Sale Discount:</strong> ${formatPercentage(response.cash_sale_discount)}</p>
                                                
                                                <h5 class="text-warning mt-3">6 Months Payment Plan</h5>
                                                <p><strong>Down Payment:</strong> ₱${formatCurrency(response.six_months_down_payment)}</p>
                                                <p><strong>Monthly:</strong> ₱${formatCurrency(response.six_months_monthly_amortization)}</p>
                                                <p><strong>Discount:</strong> ${formatPercentage(response.six_months_discount)}</p>
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <h5 class="text-warning">Installment Options</h5>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Term</th>
                                                            <th>Down Payment (${formatPercentage(response.down_payment_rate)})</th>
                                                            <th>Monthly Payment</th>
                                                            <th>Interest Rate</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1 Year</td>
                                                            <td>₱${formatCurrency(response.down_payment)}</td>
                                                            <td>₱${formatCurrency(response.monthly_amortization_one_year)}</td>
                                                            <td>${formatPercentage(response.one_year_interest_rate)}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>2 Years</td>
                                                            <td>₱${formatCurrency(response.down_payment)}</td>
                                                            <td>₱${formatCurrency(response.monthly_amortization_two_years)}</td>
                                                            <td>${formatPercentage(response.two_years_interest_rate)}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>3 Years</td>
                                                            <td>₱${formatCurrency(response.down_payment)}</td>
                                                            <td>₱${formatCurrency(response.monthly_amortization_three_years)}</td>
                                                            <td>${formatPercentage(response.three_years_interest_rate)}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>4 Years</td>
                                                            <td>₱${formatCurrency(response.down_payment)}</td>
                                                            <td>₱${formatCurrency(response.monthly_amortization_four_years)}</td>
                                                            <td>${formatPercentage(response.four_years_interest_rate)}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>5 Years</td>
                                                            <td>₱${formatCurrency(response.down_payment)}</td>
                                                            <td>₱${formatCurrency(response.monthly_amortization_five_years)}</td>
                                                            <td>${formatPercentage(response.five_years_interest_rate)}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            $('#estatePricing').html(estateCard);
                        } else {
                            $('#estatePricing').html('<div class="alert alert-info">No pricing data available for the selected estate type.</div>');
                        }
                    },
                    error: function() {
                        alert('An error occurred while fetching estate pricing data.');
                    }
                });
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#burialAssetTypeSelect').change(function() {
            const assetType = $(this).val();

            if (assetType) {
                $.ajax({
                    url: "<?= base_url("pricing/getBurialPricing") ?>",
                    method: 'POST',
                    data: {
                        category: assetType
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log('Raw Burial Response:', response);
                        
                        // Clear previous pricing details
                        $('#lotBurialPricing, #estateBurialPricing').addClass('d-none');
                        $('#lotBurialPricing, #estateBurialPricing').html('');
                        
                        if (response) {
                            const formatCurrency = (value) => {
                                return new Intl.NumberFormat('en-PH', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }).format(value);
                            };

                            if (assetType === 'Lot') {
                                const lotPricingHtml = `
                                    <div class="card shadow-lg border-danger mb-3">
                                        <div class="card-body">
                                            <h4 class="card-title text-danger">Lot Burial Pricing</h4>
                                            <p><strong>${response.burial_type}:</strong> ₱${formatCurrency(response.price)}</p>
                                        </div>
                                    </div>
                                `;
                                $('#lotBurialPricing').html(lotPricingHtml).removeClass('d-none');
                            } else if (assetType === 'Estate') {
                                const estatePricingHtml = `
                                    <div class="card shadow-lg border-dark mb-3">
                                        <div class="card-body">
                                            <h4 class="card-title text-dark">Estate Burial Pricing</h4>
                                            <p><strong>${response.burial_type}:</strong> ₱${formatCurrency(response.price)}</p>
                                        </div>
                                    </div>
                                `;
                                $('#estateBurialPricing').html(estatePricingHtml).removeClass('d-none');
                            }
                        } else {
                            const container = assetType === 'Lot' ? '#lotBurialPricing' : '#estateBurialPricing';
                            $(container)
                                .html('<div class="alert alert-info">No burial pricing data available for ' + assetType + '.</div>')
                                .removeClass('d-none');
                        }
                    },
                    error: function() {
                        alert('An error occurred while fetching burial pricing data.');
                    }
                });
            }
        });
    });
</script>

<script>
$(document).ready(function() {
    // Get the type parameter from URL
    const urlParams = new URLSearchParams(window.location.search);
    const type = urlParams.get('type');

    // Trigger the appropriate button based on URL parameter
    if (type) {
        switch(type) {
            case 'lot':
                $('#lotBtn').click();
                break;
            case 'estate':
                $('#estateBtn').click();
                break;
            case 'burial':
                $('#burialBtn').click();
                break;
        }
    }
});
</script>

<?= $this->endSection() ?>