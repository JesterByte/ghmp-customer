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
                <th class="text-center">Asset</th>
                <th class="text-center">Asset Type</th>
                <th class="text-center">Payment Option</th>
                <th class="text-center">Reservation Status</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php

            use App\Helpers\FormatterHelper;

            if (isset($table)) {
                foreach ($table as $row) {
                    $reservationType = FormatterHelper::determineIdType($row["asset_id"]);

                    if ($row["asset_type"] != "Pending" && $row["payment_option"] == "Pending" && $row["reservation_status"] == "Confirmed") {
                        $paymentOption = $row["payment_option"] . " <a role='button' href='select_payment_option/{$row["encrypted_reservation_id"]}/{$row["encrypted_asset_id"]}/{$row["encrypted_asset_type"]}' class='btn btn-primary'>Choose</a>";
                    } else if ($row["asset_type"] == "Pending" && $row["payment_option"] == "Pending" && $row["reservation_status"] == "Pending") {
                        $paymentOption = $row["payment_option"] . " (Please wait for asset type verification)";
                    } else {
                        $paymentOption = $row["payment_option"];
                    }

                    if ($row["reservation_status"] == "Cancelled") {
                        if ($reservationType == "lot") {
                            $row["asset_type"] = "N//A";
                        }
                        $paymentOption = "N/A";
                    }

                    if ($row["reservation_status"] == "Confirmed" && $row["payment_option"] != "Pending") {

                        if ($row["down_payment_status"] == "Pending") {
                            $action = '<a target="_blank" href="' . $row["down_payment_link"] . '" class="btn btn-primary" role="button"><i class="bi bi-credit-card-fill"></i> Pay Down ' . FormatterHelper::formatPrice($row["down_payment"]) . '</a>';
                        } else {
                            $action = '<a target="_blank" href="' . $row["payment_link"] . '" class="btn btn-primary" role="button"><i class="bi bi-credit-card-fill"></i> Pay ' . FormatterHelper::formatPrice($row["payment_amount"]) . '</a>';
                        }
                    } else if ($row["reservation_status"] == "Completed") {
                        $action = '<button type="button" class="btn btn-success" disabled><i class="bi bi-check-circle-fill"></i> Paid</button';
                    } else {
                        $action = "";
                    }

                    echo "<tr>";
                    echo "<td class='text-center'>" . $row['asset_id'] . "</td>";
                    echo "<td class='text-center'>" . $row["asset_type"] . "</td>";
                    echo "<td class='text-center'>" . $paymentOption . "</td>";
                    echo "<td class='text-center'>" . $row["reservation_status"] . "</td>";
                    echo "<td class='text-center'>
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