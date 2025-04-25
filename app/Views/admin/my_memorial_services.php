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
                <th class="text-center">Sorter</th>
                <th class="text-center">Asset</th>
                <th class="text-center">Interred</th>
                <th class="text-center">Date & Time</th>
                <th class="text-center">Service Status</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            use App\Helpers\FormatterHelper;

            if (isset($burialReservations)) {
                foreach ($burialReservations as $row) {
                    $middleName = !empty($row["middle_name"]) ? $row["middle_name"] : "";
                    $suffix = !empty($row["suffix"]) ? ", " . $row["suffix"] : "";
                    $interred = $row["first_name"] . " " . $middleName . " " . $row["last_name"] . $suffix;
                    $dateTime = date("F j, Y h:i A", strtotime($row["date_time"]));

                    $paymentAmount = FormatterHelper::formatPrice($row["payment_amount"]);

                    if ($row["status"] == "Approved" && $row["payment_status"] == "Pending") {
                        // $action = '<a role="button" target="_blank" class="btn btn-primary" href="' . $row["payment_link"] . '"><i class="bi bi-credit-card-fill"></i> Pay ' . $paymentAmount . '</a>';
                        $action = '<a target="_blank" role="button" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Click the button below to securely complete your payment through PayMongo." href="' . $row["payment_link"] . '"><i class="bi bi-credit-card-fill"></i> Pay ' . $paymentAmount . '</a>';
                    } else if ($row["status"] == "Approved" && $row["payment_status"] == "Paid") {
                        $action = '<button type="button" class="btn btn-success" disabled><i class="bi bi-check-circle-fill"></i> Paid</button';
                    } else {
                        $action = "";
                    }

                    $assetId = FormatterHelper::formatAssetId($row['asset_id']);
                    $status = $row["status"] === "Pending" ? "Pending (Please wait for approval)" : $row["status"];

                    echo "<tr>";
                    echo "<td class='text-center'>" . $row["created_at"] . "</td>";
                    echo "<td class='text-center'>" . $assetId . "</td>";
                    echo "<td class='text-center'>" . $interred . "</td>";
                    echo "<td class='text-center'>" . $dateTime . "</td>";
                    echo "<td class='text-center'>" . $status . "</td>";
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
