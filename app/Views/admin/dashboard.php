<?= $this->extend("layouts/admin") ?>

<?= $this->section("content") ?>
<div class="container">
    <!-- Overview Section -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-bg-primary mb-3">
                <div class="card-header">Total Lots</div>
                <div class="card-body">
                    <h5 class="card-title">1,500</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-success mb-3">
                <div class="card-header">Occupied Lots</div>
                <div class="card-body">
                    <h5 class="card-title">1,100</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-warning mb-3">
                <div class="card-header">Available Lots</div>
                <div class="card-body">
                    <h5 class="card-title">400</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-info mb-3">
                <div class="card-header">Recent Burials</div>
                <div class="card-body">
                    <h5 class="card-title">15</h5>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Section -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Burial Statistics</div>
                <div class="card-body">
                    <canvas id="burialChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Lot Availability</div>
                <div class="card-body">
                    <canvas id="lotChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const burialCtx = document.getElementById('burialChart').getContext('2d');
    new Chart(burialCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Burials per Month',
                data: [10, 12, 8, 15, 20, 18],
                backgroundColor: 'purple'
            }]
        }
    });

    const lotCtx = document.getElementById('lotChart').getContext('2d');
    new Chart(lotCtx, {
        type: 'doughnut',
        data: {
            labels: ['Occupied', 'Available'],
            datasets: [{
                data: [1100, 400],
                backgroundColor: ['red', 'green']
            }]
        }
    });
</script>

<?= $this->endSection(); ?>
