        </div>
        </div>
        <script src="<?= BASE_URL . "js/jquery.js" ?>"></script>
        <script src="<?= BASE_URL . "js/bootstrap.bundle.min.js" ?>"></script>
        <script src="<?= BASE_URL . "js/dataTables.js" ?>"></script>
        <script src="<?= BASE_URL . "js/dataTables.bootstrap5.js" ?>"></script>
        <script src="<?= BASE_URL . "js/dataTables.responsive.js" ?>"></script>
        <script src="<?= BASE_URL . "js/responsive.bootstrap5.js" ?>"></script>
        <script src="<?= BASE_URL . "/js/bootstrap-toast.js" ?>"></script>

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

        <script>
            const toast = new Toast();

            let icon = <?= json_encode(isset($_SESSION["flash_message"]["icon"]) ? $_SESSION["flash_message"]["icon"] : "") ?>;
            let message = <?= json_encode(isset($_SESSION["flash_message"]["message"]) ? $_SESSION["flash_message"]["message"] : "") ?>;
            let title = <?= json_encode(isset($_SESSION["flash_message"]["title"]) ? $_SESSION["flash_message"]["title"] : "") ?>;
            let link = <?= json_encode(isset($_SESSION["flash_message"]["link"]) ? $_SESSION["flash_message"]["link"] : "") ?>;
            let linkText = <?= json_encode(isset($_SESSION["flash_message"]["link_text"]) ? $_SESSION["flash_message"]["link_text"] : "") ?>;

            if (icon && message && title) {
                toast.showToast(icon, message, title);
                if (link) {
                    toast.showToast('<i class="bi bi-arrow-counterclockwise"></i>', linkText, "Undo", 5000, link);
                }

                document.querySelector('.toast').addEventListener('hidden.bs.toast', function() {
                    <?php unset($_SESSION["flash_message"]) ?>
                });
            }
        </script>
        </body>

        </html>