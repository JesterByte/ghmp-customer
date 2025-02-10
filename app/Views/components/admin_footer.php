        </div>
    </div>
    <script src="<?= BASE_URL . "js/jquery.js" ?>"></script>
    <script src="<?= BASE_URL . "js/bootstrap.bundle.min.js" ?>"></script>
    <script src="<?= BASE_URL . "js/dataTables.js" ?>"></script>
    <script src="<?= BASE_URL . "js/dataTables.bootstrap5.js" ?>"></script>
    <script src="<?= BASE_URL . "js/dataTables.responsive.js" ?>"></script>
    <script src="<?= BASE_URL . "js/responsive.bootstrap5.js" ?>"></script>

    <?php 
        if ($pageTitle == "My Lots & Estates") {
            echo $this->include("modals/confirm_payment");
        } else if ($pageTitle == "Payment Management") {
            echo $this->include("modals/pay_cash_sale");
            echo $this->include("modals/pay_six_months");
            echo $this->include("modals/pay_down_payment");
        }
    ?>

    <script>
        new DataTable("#table", {
            responsive: true
        })
    </script>
</body>
</html>
