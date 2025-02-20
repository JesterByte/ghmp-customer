<?= $this->extend("layouts/admin") ?>

<?= $this->section("content") ?>
<!-- <div class="d-flex justify-content-end">
    <div class="btn-group">
    <a href="#" class="btn btn-primary active" aria-current="page">Lot</a>
    <a href="#" class="btn btn-primary">Estate</a>
    </div>
</div> -->
<div class="table-responsive rounded shadow">
    <table id="table" class="table">
        <thead>
            <tr>
                <th>Asset</th>
                <th>Asset Type</th>
                <th>Payment Option</th>
                <th>Reservation Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php

use App\Helpers\FormatterHelper;

            if (isset($table)) {
                foreach ($table as $row) {

                    if ($row["asset_type"] != "Pending" && $row["payment_option"] == "Pending") {
                        $paymentOption = $row["payment_option"] . " <a role='button' href='select_payment_option/{$row["encrypted_asset_id"]}/{$row["encrypted_asset_type"]}' class='btn btn-primary'>Choose</a>";
                    } else if ($row["asset_type"] == "Pending" && $row["payment_option"] == "Pending" && $row["payment_option"] == "Pending") {
                        $paymentOption = $row["payment_option"] . " (Please wait for asset type verification)";
                    } else {
                        $paymentOption = $row["payment_option"];
                    }

                    if ($row["reservation_status"] == "Confirmed" && $row["payment_option"] != "Pending") {
                        $action = '<a target="_blank" href="' . $row["payment_link"] . '" class="btn btn-primary" role="button"><i class="bi bi-credit-card-fill"></i> Pay ' . FormatterHelper::formatPrice($row["payment_amount"]) . '</a>';
                    } else {
                        $action = "";
                    }

                    echo "<tr>";
                    echo "<td>" . $row['asset_id'] . "</td>";
                    echo "<td>" . $row["asset_type"] . "</td>";
                    echo "<td>" . $paymentOption . "</td>";
                    echo "<td>" . $row["reservation_status"] . "</td>";
                    echo "<td>
                        $action
                    </td>";
                    echo "</tr>";
                }
            }

            ?>
        </tbody>
    </table>
</div>
<?= $this->endSection(); ?>
