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
                    $dateTime = FormatterHelper::formatDate($row["date_time"]);

                    $paymentAmount = FormatterHelper::formatPrice($row["payment_amount"]);

                    $action = '<a role="button" class="btn btn-primary" href="' . $row["payment_link"] . '"><i class="bi bi-credit-card-fill"></i> Pay ' . $paymentAmount . '</a>';

                    echo "<tr>";
                    echo "<td class='text-center'>" . $row['asset_id'] . "</td>";
                    echo "<td class='text-center'>" . $interred . "</td>";
                    echo "<td class='text-center'>" . $dateTime . "</td>";
                    echo "<td class='text-center'>" . $row["status"] . "</td>";
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
