        </div>
    </div>
    <script src="<?= base_url("js/jquery.js") ?>"></script>
    <script src="<?= base_url("js/bootstrap.bundle.min.js") ?>"></script>
    <script src="<?= base_url("js/dataTables.js") ?>"></script>
    <script src="<?= base_url("js/dataTables.bootstrap5.js") ?>"></script>
    <script src="<?= base_url("js/dataTables.responsive.js") ?>"></script>
    <script src="<?= base_url("js/responsive.bootstrap5.js") ?>"></script>

    <?php 
        if ($pageTitle == "My Lots & Estates") {
            echo $this->include("modals/confirm_payment");
        } else if ($pageTitle == "Payment Management") {
            echo $this->include("modals/pay_cash_sale");
            echo $this->include("modals/pay_six_months");
        }
    ?>

    <script>
        new DataTable("#table", {
            responsive: true
        })
    </script>
</body>
</html>
