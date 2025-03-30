<?= $this->extend('components/brochure_template') ?>

<?= $this->section('content') ?>
<style>
    #map {
        height: 600px;
        width: 100%;
    }

    .legend {
        background: white;
        padding: 10px;
        margin: 10px;
        border-radius: 5px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
    }

    .legend h4 {
        margin: 0 0 10px;
    }

    .legend div {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
    }

    .legend div span {
        display: inline-block;
        width: 20px;
        height: 20px;
        margin-right: 10px;
    }
</style>
<div class="container mt-4">
    <h1 class="display-4 text-center">Lots & Estates</h1>
    <p class="text-center">Find the lot and estate locations on the map below.</p>

    <!-- Map Container -->
    <div class="rounded shadow" id="map" style="height: 500px; width: 100%;">
    </div>
</div>

<!-- Leaflet.js CDN -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="<?= base_url("js/GhmpMap.js") ?>"></script>

<script>
    const map = new GhmpMap("map", "locator/fetch_lots");
</script>
<?= $this->endSection() ?>